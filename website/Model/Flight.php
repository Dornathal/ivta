<?php

namespace Model;

use DateTime;
use Model\Base\Flight as BaseFlight;
use Model\Map\AircraftTableMap;
use Model\Map\AirportTableMap;
use Model\Map\FlightTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Skeleton subclass for representing a row from the 'flights' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Flight extends BaseFlight
{
    const MINUTE = 0;

    private $waitingTimes = array(FlightTableMap::COL_STATUS_PLANNING => 0, FlightTableMap::COL_STATUS_LOADING => 10,
        FlightTableMap::COL_STATUS_EN_ROUTE => 10, FlightTableMap::COL_STATUS_UNLOADING => 10,
        FlightTableMap::COL_STATUS_FINISHED => 0, FlightTableMap::COL_STATUS_ABORTED => 0);
    private $freight_columns = array(FlightTableMap::COL_PACKAGES, FlightTableMap::COL_POST,
        FlightTableMap::COL_PASSENGER_LOW, FlightTableMap::COL_PASSENGER_MID, FlightTableMap::COL_PASSENGER_HIGH);
    private $airway;

    /**
     * Set the value of [status] column.
     *
     * @param  string $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setStatus($v)
    {   parent::setStatus($v);
        $this->setNextStepPossibleAt(time() + $this->waitingTimes[$this->getStatus()] * self::MINUTE);
        return $this;
    }


    /**
     * @return array
     */
    public function queryFlightData()
    {
        $now = new DateTime('now');
        $freights = FreightQuery::create()
            ->findByRouteFlights(array($this->getId()));

        $freight_array = array();
        foreach($freights as $freight){
            $array = $freight->toArray();
            $array['Departure'] = $freight->getDeparture()->toArray();
            $array['Destination'] = $freight->getDestination()->toArray();
            $array['Routes'] = array();
            foreach($freight->getRouteFlights() as $route_point){
                $flight = FlightQuery::create()->findOneById($route_point);
                array_push($array['Routes'], array('Destination' => $flight->getDestination()->toArray(), 'Departure' => $flight->getDeparture()->toArray()));
            }
            array_push($freight_array, $array);
        }

        return array('Departure' => $this->getDeparture()->toArray(), 'Destination' => $this->getDestination()->toArray(),
            'Flight' => $this->toArray(), 'AircraftModel' => $this->getAircraft()->getAircraftModel()->toArray(),
            'NextStepPossibleIn' => $this->getNextStepPossibleAt()->getTimestamp() - $now->getTimestamp(),
            'Freights' => $freight_array
        );
    }

    /**
     * @param Aircraft $aircraft
     * @param Airport $departure
     * @param Airport $destination
     * @return string
     */
    public static function generateFlightNumber(Aircraft $aircraft, Airport $departure, Airport $destination){
        return $aircraft->getAirline()->getICAO() . self::FlightNumberHash($departure->getICAO()) . self::FlightNumberHash($destination->getICAO());
    }
    private static function FlightNumberHash($string){
        $code=0;
        for($i=0; $i<4; $i++)
            $code += ord($string[$i]);
        return $code % 100;

    }

    public static function transaction($func){
        $con = Propel::getConnection(FlightTableMap::DATABASE_NAME);

        $con->beginTransaction();
        try{
            $func();
            $con->commit();
        }catch (Exception $e){
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * @param Pilot $pilot
     * @param Aircraft $aircraft
     * @param Airport $destination
     * @return Flight
     * @throws \ErrorException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public static function planFlight(Pilot $pilot, Aircraft $aircraft, Airport $destination){
        $flight = new Flight();
        self::transaction(function () use ($flight, $pilot, $aircraft, $destination){
        $departure = $aircraft->getAirport();
        $available_freight = $departure->queryFreightDiagram()[0][$destination->getICAO()];

        $aircraftModel = $aircraft->getAircraftModel();

        $flight->setAircraft($aircraft);
        $flight->setAircraftModel($aircraft->getAircraftModel());
        $flight->setDeparture($departure);
        $flight->setDestination($destination);
        $flight->setPilot($pilot);
        $flight->setAirline($pilot->getAirline());
        $flight->setFlightNumber(self::generateFlightNumber($aircraft,$departure,$destination));
        $flight->setStatus(FlightTableMap::COL_STATUS_PLANNING);
        $loaded_freight = $flight->loadFreight($available_freight, $aircraftModel);
        Freight::loadToFlight($flight, $loaded_freight);
        $flight->save();
        });
        return $flight;
    }

    /**
     * @param $available_freight
     * @param AircraftModel $aircraftModel
     * @return mixed
     * @throws \Propel\Runtime\Exception\PropelException
     * @internal param Flight $flight
     */
    private function loadFreight($available_freight, AircraftModel $aircraftModel)
    {
        $loaded_freight = array();
        foreach($this->freight_columns as $name) {
            $name = FlightTableMap::translateFieldName($name, TableMap::TYPE_COLNAME, TableMap::TYPE_PHPNAME);
            $capacity = $aircraftModel->getByName($name);
            $loaded_freight[$name] = min($available_freight[$name], $capacity);
            $this->setByName($name, $loaded_freight[$name]);
        }
        return $loaded_freight;
    }

    private function unloadFreight()
    {
        foreach($this->getFreights() as $freight) {
            $freight->setLocation($this->getDestination());
            $freight->setOnFlight(null);
        }
        $aircraft = $this->getAircraft();
        $aircraft->setNumberFlights($aircraft->getNumberFlights() + 1);
        $aircraft->setFlownDistance($aircraft->getFlownDistance() + $this->getAirway()->getDistance());
        $flownTime = $this->getFlightFinishedAt()->getTimestamp() - $this->getFlightStartedAt()->getTimestamp();
        $aircraft->setFlownTime($aircraft->getFlownTime() + $flownTime);
        $aircraft->setStatus(AircraftTableMap::COL_STATUS_IDLE);
        $aircraft->setAirport($this->getDestination());
    }

    public function nextStatus(){
        self::transaction(function (){
            if(in_array($this->getStatus(), array(FlightTableMap::COL_STATUS_FINISHED, FlightTableMap::COL_STATUS_ABORTED)))
                throw new \Exception("Status is no longer able to change");

            $valueSet = FlightTableMap::getValueSet(FlightTableMap::COL_STATUS);
            $this->setStatus($valueSet[array_search($this->getStatus(), $valueSet) + 1]);


            switch ($this->getStatus()){
                case FlightTableMap::COL_STATUS_LOADING:
                    $this->getAircraft()->setStatus(AircraftTableMap::COL_STATUS_LOADING);
                    foreach($this->getFreights() as $freight) {
                        $freight->addRouteFlight($this->getId());
                        $freight->save();
                    }
                    break;
                case FlightTableMap::COL_STATUS_EN_ROUTE:
                    $this->getAircraft()->setStatus(AircraftTableMap::COL_STATUS_EN_ROUTE);
                    $this->setFlightStartedAt(time()); break;
                case FlightTableMap::COL_STATUS_UNLOADING:
                    $this->getAircraft()->setStatus(AircraftTableMap::COL_STATUS_UNLOADING);
                    $this->setFlightFinishedAt(time()); break;
                case FlightTableMap::COL_STATUS_FINISHED:
                    $this->getAircraft()->setStatus(AircraftTableMap::COL_STATUS_IDLE);
                    $this->unloadFreight(); break;
            }
            $this->save();
        });
    }

    private function getDistance()
    {
        return $this->getAirway()
            ->getDistance();
    }

    /**
     * @return Airway
     */
    private function getAirway()
    {
        if($this->airway == null)
            $this->airway = Airway::getAirway($this->getDeparture(), $this->getDestination());
        return $this->airway;
    }

}

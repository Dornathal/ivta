<?php

namespace Model;

use Model\Base\Airport as BaseAirport;
use Model\Map\AirportTableMap;
use Model\Map\FlightTableMap;
use Model\Map\FreightTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ArrayCollection;

/**
 * Skeleton subclass for representing a row from the 'airports' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Airport extends BaseAirport
{
    private $freightDiagram;

    /**
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     */
    function queryFlightTable()
    {
        $possibleFlightStatus = array(FlightTableMap::COL_STATUS_PLANNING, FlightTableMap::COL_STATUS_LOADING, FlightTableMap::COL_STATUS_UNLOADING);
        $departing = FlightQuery::create()
            ->filterByStatus($possibleFlightStatus)
            ->filterByDeparture($this)
            ->join('Aircraft')
            ->joinDestination()
            ->select(array('flight_number', 'status'))
            ->withColumn('Aircraft.callsign', 'callsign')
            ->withColumn('Destination.icao', 'airport')
            ->find()->toArray();

        $arriving = FlightQuery::create()
            ->filterByStatus($possibleFlightStatus)
            ->filterByDestination($this)
            ->join('Aircraft')
            ->joinDeparture()
            ->select(array('flight_number', 'status'))
            ->withColumn('Aircraft.callsign', 'callsign')
            ->withColumn('Departure.icao', 'airport')
            ->find()->toArray();

        $en_route = FlightQuery::create()
            ->filterByStatus(FlightTableMap::COL_STATUS_EN_ROUTE)
            ->where('flights.departure_id = ?', $this->id)
            ->_or()
            ->where('flights.destination_id = ?', $this->id)
            ->join('Aircraft')
            ->joinDeparture('Departure')
            ->joinDestination('Destination')
            ->select(array('flight_number'))
            ->withColumn('Aircraft.callsign', 'callsign')
            ->withColumn('Destination.icao', 'destination')
            ->withColumn('Departure.icao', 'departure')
            ->find()->toArray();

        return array($departing, $en_route ,$arriving);
    }

    /**
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     */
    function queryFreightDiagram(){
        if($this->freightDiagram != null) return $this->freightDiagram;

        Freight::generateFreight($this);

        $freights = FreightQuery::create()
            ->filterByLocation($this)
            ->filterByDestination($this, Criteria::NOT_EQUAL)
            ->find();

        $container = array('destination' => new ArrayCollection(), 'departure' => new ArrayCollection());
        $value_set = FreightTableMap::getValueSet(FreightTableMap::COL_FREIGHT_TYPE);
        foreach($freights as $freight){
            foreach($container as $key=>$collection){
                $icaos = null;
                if($key == 'departure'){
                    $icaos = array($freight->getDeparture()->getICAO());
                    $distance = $freight->getDestination();
                }else{
                    $icaos = $freight->getNextSteps();
                    $distance = $freight->getDeparture();
                }
                $icaos = AirportQuery::create()
                    ->filterByICAO($icaos, Criteria::IN)
                    //->withDistance($freight->getDeparture()->getLatitude(), $freight->getDeparture()->getLongitude())
                    ->find();
                foreach($icaos as $icao) {
                    if (!array_key_exists($icao->getICAO(), $collection->toArray())) {
                        $collection[$icao->getICAO()] = $this->createFreightArray($value_set);
                    }
                    $collection[$icao->getICAO()][$freight->getFreightType()] += $freight->getAmount();
                    if(!array_key_exists('Distance', $collection[$icao->getICAO()])) {
                        $collection[$icao->getICAO()]['Distance'] = round($distance->getDistanceTo($icao, AirportTableMap::NAUTICAL_MILES_UNIT));
                    }
                }
            }
        }
        $this->freightDiagram = array($container['destination']->toArray(), $container['departure']->toArray());
        return $this->freightDiagram;
    }

    /**
     * @param $valueset
     * @return array
     */
    private function createFreightArray($valueset)
    {
        $tmp = array();
        foreach ($valueset as $key => $value) {
            $tmp[$value] = 0;
        }
        return $tmp;
    }

    public function nextStepsTo(Airport $destination)
    {
        return Airway::getAirway($this, $destination)
            ->getNextSteps();
    }
}

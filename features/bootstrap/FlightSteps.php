<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 14:10
 */
use Behat\Behat\Context\BehatContext;
use Model\Aircraft;
use Model\AircraftQuery;
use Model\Airport;
use Model\AirportQuery;
use Model\Flight;
use Model\FlightQuery;
use Model\Freight;
use Model\Pilot;
use Propel\Runtime\ActiveQuery\Criteria;

class FlightSteps extends \Behat\MinkExtension\Context\RawMinkContext implements \Behat\Behat\Context\Context
{

    private $constantContainer;

    /**
     * @internal param ConstantContainer $constantContainer
     */
    public function __construct()
    {
        $this->constantContainer = new ConstantContainer();
    }

    /**
     * @Transform :flight
     */
    public function castFlightNumberToFlight($flight)
    {
        return FlightQuery::create()->findOneByFlightNumber($flight);
    }

    /**
     * @Then last flight should have status :status
     */
    public function assertFlightStatus($status)
    {
        $flight = FlightQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
        expect($flight->getStatus())->to->equal($status);
    }

    /**
     * @Given :pilot has a flight :flight_number from :from to :to with aircraft :aircraft and status :status
     */
    public function newFlight(Pilot $pilot, $flight_number, Airport $from, Airport $to, Aircraft $aircraft, $status)
    {
        $flight = new Flight();
        $flight->setStatus($status);
        $flight->setFlightNumber($flight_number);
        $flight->setAircraft($aircraft);
        $flight->setAirline($pilot->getAirline());
        $flight->setAircraftModel($aircraft->getAircraftModel());
        $flight->setDeparture($from);
        $flight->setDestination($to);
        $flight->setPilot($pilot);
        $flight->setFlightStartedAt(time()-600);
        $flight->setFlightFinishedAt(time());
        $flight->save();
        return $flight;
    }

    /**
     * @Given flight :flight has :amount :freight_type from :from to :to
     */
    public function assertFlightHasFreight(Flight $flight, $amount, $freight_type, Airport $from, Airport $to)
    {
        $freight = new Freight();
        $freight->setDeparture($from);
        $freight->setDestination($to);
        $freight->setAmount($amount);
        $freight->setFreightType($this->constantContainer->FREIGHT_TYPES[$freight_type]);

        $flight
            ->addFreight($freight)
            ->setByName($this->constantContainer->FREIGHT_TYPES[$freight_type], $amount);
        $flight->save();
        return $freight;
    }

    /**
     * @Given /^I am on the latest flights page$/
     */
    public function visitLatestFlight()
    {
        sleep(1);
        $flight = FlightQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
        $this->visitPath('/aircraft/' . $flight->getAircraft()->getCallsign() . '/' . $flight->getFlightNumber() . '/' . $flight->getId());
    }
}
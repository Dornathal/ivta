<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 14:10
 */
use Behat\Behat\Context\BehatContext;
use Model\AircraftQuery;
use Model\AirportQuery;
use Model\Flight;
use Model\FlightQuery;
use Model\Freight;
use Propel\Runtime\ActiveQuery\Criteria;

class FlightSteps extends BehatContext
{

    private $constantContainer;

    /**
     * @param ConstantContainer $constantContainer
     */
    public function __construct(ConstantContainer $constantContainer)
    {
        $this->constantContainer = $constantContainer;
    }

    /**
     * @Then /^last flight should have status "([^"]*)"$/
     */
    public function flightShouldHaveStatus($status)
    {
        $flight = FlightQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
        expect($flight->getStatus())->to->equal($status);
    }

    /**
     * @Given /^I have a flight "([^"]*)" from "([^"]*)" to "([^"]*)" with aircraft "([^"]*)" and status "([^"]*)"$/
     */
    public function iHaveAFlightFromToWithAircraftAndStatus($flight_number, $from, $to, $callsign, $status)
    {
        $flight = new Flight();
        $flight->setStatus($status);
        $flight->setFlightNumber($flight_number);
        $flight->setAircraft(AircraftQuery::create()->findOneByCallsign($callsign));
        $flight->setDeparture(AirportQuery::create()->findOneByICAO($from));
        $flight->setDestination(AirportQuery::create()->findOneByICAO($to));
        $flight->setPilotId(1);
        $flight->setFlightStartedAt(time()-600);
        $flight->setFlightFinishedAt(time());
        $flight->save();
        return $flight;
    }

    /**
     * @Given /^flight "([^"]*)" has (\d+) ([^"]*) from "([^"]*)" to "([^"]*)"$/
     */
    public function flightHasFreightFromTo($flight_number, $amount, $freight_type, $from, $to)
    {
        $freight = new Freight();
        $freight->setDeparture(AirportQuery::create()->findOneByICAO($from));
        $freight->setDestination(AirportQuery::create()->findOneByICAO($to));
        $freight->setAmount($amount);
        $freight->setFreightType($this->constantContainer->FREIGHT_TYPES[$freight_type]);

        $flight = FlightQuery::create()
            ->findOneByFlightNumber($flight_number)
            ->addFreight($freight)
            ->setByName($this->constantContainer->FREIGHT_TYPES[$freight_type], $amount);
        $flight->save();
        return $freight;
    }

    /**
     * @Given /^I am on the latest flights page$/
     */
    public function iAmOnTheLatestFlightsPage()
    {
        sleep(1);
        $flight = FlightQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne();
        $this->getMainContext()->visit('/aircraft/' . $flight->getAircraft()->getCallsign() . '/' . $flight->getFlightNumber() . '/' . $flight->getId());
    }
}
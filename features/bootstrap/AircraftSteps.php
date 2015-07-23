<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 14:07
 */
use Behat\Behat\Context\BehatContext;

class AircraftSteps extends BehatContext
{
    /**
     * @Given /^I am on the "([^"]*)" aircraft site$/
     */
    public function iAmOnTheAircraftSite($callsign)
    {
        $this->getMainContext()->visit('/aircraft/' . $callsign);
    }

    /**
     * @Given /^aircraft "([^"]*)" should be located at "([^"]*)"$/
     */
    public function aircraftShouldBeLocatedAt($callsign, $icao)
    {
        $aircraft = \Model\AircraftQuery::create()
            ->findOneByCallsign($callsign);
        expect($aircraft->getAirport()->getICAO())->to->equal($icao);
    }

    /**
     * @Given /^aircraft "([^"]*)" has status "([^"]*)"$/
     */
    public function aircraftHasStatus($callsign, $status)
    {
        $aircraft = \Model\AircraftQuery::create()
            ->findOneByCallsign($callsign);
        $aircraft->setStatus($status);
        $aircraft->save();
    }

    /**
     * @Then /^aircraft "([^"]*)" should have status "([^"]*)"$/
     */
    public function aircraftShouldHaveStatus($callsign, $status)
    {
        $aircraft = \Model\AircraftQuery::create()
            ->findOneByCallsign($callsign);
        expect($aircraft->getStatus())->to->equal($status);
    }

}
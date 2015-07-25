<?php
use Behat\MinkExtension\Context\RawMinkContext;
use Model\Aircraft;
use Model\AircraftQuery;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 14:07
 */

class AircraftSteps extends RawMinkContext implements \Behat\Behat\Context\Context
{

    /**
     * @Transform :aircraft
     */
    public function castCallsignToAircraft($aircraft)
    {
        return AircraftQuery::create()
            ->findOneByCallsign($aircraft);
    }

    /**
     * @Given I am on the :callsign aircraft site
     */
    public function iAmOnTheAircraftSite($callsign)
    {
        $this->visitPath('/aircraft/' . $callsign);
    }

    /**
     * @Given aircraft :aircraft should be located at :icao
     */
    public function aircraftShouldBeLocatedAt(Aircraft $aircraft, $icao)
    {
        expect($aircraft->getAirport()->getICAO())->to->equal($icao);
    }

    /**
     * @Given aircraft :aircraft has status :status
     */
    public function aircraftHasStatus(Aircraft $aircraft, $status)
    {
        $aircraft->setStatus($status);
        $aircraft->save();
    }

    /**
     * @Then aircraft :aircraft should have status :status
     */
    public function aircraftShouldHaveStatus(Aircraft $aircraft, $status)
    {
        expect($aircraft->getStatus())->to->equal($status);
    }

    /**
     * @Then there should be aircraft :aircraft
     */
    public function thereShouldBeAircraft(Aircraft $aircraft)
    {
        expect($aircraft)->to->not->be->null;
    }

}
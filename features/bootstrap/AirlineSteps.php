<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:06
 */
use Behat\Behat\Context\BehatContext;
use \Model\AirlineQuery;
use \Model\AircraftTypeQuery;
use \Model\Aircraft;

class AirlineSteps extends BehatContext
{
    /**
     * @Given /^I am on the airlines site$/
     */
    public function iAmOnTheAirlinesSite()
    {
        $this->getMainContext()->visit('/airlines');
    }

    /**
     * @Given /^airline "([^"]*)" owns an "([^"]*)" with callsign "([^"]*)"$/
     */
    public function airlineOwnsAnWithCallsign($icao, $model, $callsign)
    {
        $airline = AirlineQuery::create()
            ->findOneByICAO($icao);
        $aircraft_type = AircraftTypeQuery::create()
            ->findOneByModel($model);

        $aircraft = new Aircraft();
        $aircraft->setCallsign($callsign)
            ->setAirline($airline)
            ->setAircraftType($aircraft_type)
            ->save();
        return $aircraft;
    }

    /**
     * @Given /^airline "([^"]*)" owns aircraft_model "([^"]*)" with callsign "([^"]*)" at airport "([^"]*)"$/
     */
    public function airlineOwnsAircraftModelWithCallsignAtAirport($icao, $model, $callsign, $location)
    {
        $airport = \Model\AirportQuery::create()
            ->findOneByICAO($location);

        $aircraft = $this->airlineOwnsAnWithCallsign($icao, $model, $callsign)
            ->setAirport($airport);
        $aircraft->setCoordinates($airport->getLatitude(), $airport->getLongitude());
        $aircraft->save();
        return $aircraft;
    }
}

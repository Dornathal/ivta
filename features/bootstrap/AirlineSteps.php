<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:06
 */
use Behat\MinkExtension\Context\RawMinkContext;
use Model\AircraftType;
use Model\Airline;
use \Model\AirlineQuery;
use \Model\Aircraft;
use Model\Airport;

class AirlineSteps extends RawMinkContext implements  \Behat\Behat\Context\Context
{
    /**
     * @Transform :airline
     */
    public function castICAOToAirline($airline)
    {
        return AirlineQuery::create()->findOneByICAO($airline);
    }

    /**
     * @Given airline :airline owns an :model with callsign :callsign
     */
    public function newAircraftOfAirline(Airline $airline, AircraftType $model, $callsign)
    {
        $aircraft = new Aircraft();
        $aircraft->setCallsign($callsign)
            ->setAirline($airline)
            ->setAircraftType($model)
            ->save();
        return $aircraft;
    }

    /**
     * @Given I am on the airlines site
     */
    public function iAmOnTheAirlinesSite()
    {
        $this->visitPath('/airlines');
    }

    /**
     * @Given I am on the :icao airlines site
     */
    public function iAmOnTheAirlinesSite2($icao)
    {
        $this->visitPath('/airlines/' . $icao);
        echo 'Screenshot';
        echo $this->getSession()->getCurrentUrl();
    }

    /**
     * @Given airline :airline owns aircraft_model :model with callsign :callsign at airport :airport
     */
    public function newAircraftOfAirlineAt(Airline $airline, AircraftType $model, $callsign, Airport $airport)
    {
        $aircraft = $this->newAircraftOfAirline($airline, $model, $callsign);
        $aircraft->setAirport($airport);
        $aircraft->setCoordinates($airport->getLatitude(), $airport->getLongitude());
        $aircraft->save();
        return $aircraft;
    }
}

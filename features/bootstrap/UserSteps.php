<?php
use Model\Aircraft;
use Model\AircraftModel;
use Model\Airline;
use Model\Airport;
use Model\Pilot;
use Model\Flight;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 25.07.15
 * Time: 18:36
 */

class UserSteps extends \Behat\MinkExtension\Context\RawMinkContext implements \Behat\Behat\Context\Context{
    private $token;

    /**
     * @Transform :pilot
     */
    public function castTokenToPilot($token)
    {
        return \Model\PilotQuery::create()->findOneByToken($token);
    }

    /**
     * @Given I am logged in as :token
     */
    public function loginAs($token)
    {
        $this->visitPath('/login?IVAOTOKEN='.$token.'&site=/');
        $this->token = $token;
    }

    public static function addUser(){
        $user = new \Model\Pilot();
        $user->setToken('PILOT');
        $user->setName('Behat Runner');
        $user->setAirlineId(214);
        $user->setRank('PILOT');
        $user->save();
    }

    /**
     * @Given pilot :pilot owns an :model with callsign :callsign
     */
    public function newAircraftOfAirline(Pilot $pilot, AircraftModel $model, $callsign)
    {
        $aircraft = new Aircraft();
        $aircraft->setCallsign($callsign)
            ->setAirline($pilot->getAirline())
            ->setPilot($pilot)
            ->setAircraftModel($model)
            ->save();
        return $aircraft;
    }

    /**
     * @Given pilot :pilot owns aircraft_model :model with callsign :callsign at airport :airport
     */
    public function newAircraftOfAirlineAt(Pilot $pilot, AircraftModel $model, $callsign, Airport $airport)
    {
        Flight::transaction(function () use ($pilot, $airport, $model, $callsign){
            $aircraft = $this->newAircraftOfAirline($pilot, $model, $callsign);
            $aircraft->setAirport($airport);
            $aircraft->setCoordinates($airport->getLatitude(), $airport->getLongitude());
            $aircraft->save();
        });
    }

    /**
     * @Then there should be a cookie :cookie
     */
    public function thereShouldBeACookie($arg1)
    {
        expect($this->getSession()->getCookie('IVAOTOKEN'))->to->equal($arg1);
    }

    /**
     * @When IVAO sends callback :callback
     */
    public function ivaoSendsCallback($token)
    {
        $this->loginAs($token);
    }

    /**
     * @Given I have a saldo of :saldo
     */
    public function iHaveASaldoOf($saldo)
    {
        $user = $this->getLoggedInPilot();
        $user->setSaldo($saldo);
        $user->save();
    }

    /**
     * @Given I should have a saldo of :saldo
     */
    public function assertSaldo($saldo)
    {
        expect($this->getLoggedInPilot()->getSaldo())->to->equal(intval($saldo));
    }

    /**
     * @Given I am subscribed to airline :airline
     */
    public function setAirlineTo(Airline $airline)
    {
        $this->getLoggedInPilot()->setAirline($airline);
        $this->getLoggedInPilot()->save();
    }

    /**
     * @return \Model\Pilot
     */
    private function getLoggedInPilot()
    {
        return $this->castTokenToPilot($this->token);
    }

    /**
     * @Given /^I am on my profile$/
     */
    public function iAmOnMyProfile()
    {
        $this->visitPath('/profile');
    }
}
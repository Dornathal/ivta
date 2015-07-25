<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:43
 */
use Behat\Behat\Context\BehatContext;
use Model\Airline;
use Model\AirlineQuery;

class UserSteps extends BehatContext
{
    /**
     * @var \Model\Pilot
     */
    private $loggedIn;
    /**
     * @Given /^I am logged in as ([^"]*)/
     */
    public function userIsLoggedInAs($token)
    {
        $this->getMainContext()->visit('/login?IVAOTOKEN='.$token.'&site=/');
        $this->loggedIn = \Model\PilotQuery::create()->findOneByToken($token);
    }

    public static function addUser(){
        $user = new \Model\Pilot();
        $user->setToken('PILOT');
        $user->setName('Behat Runner');
        $user->setRank('PILOT');
        $user->save();
    }

    /**
     * @Then /^there should be a cookie "([^"]*)"$/
     */
    public function thereShouldBeACookie($arg1)
    {
        $session = $this->getMainContext()->getMink()->getSession();
        expect($session->getCookie('IVAOTOKEN'))->to->equal($arg1);
    }

    /**
     * @When /^IVAO sends callback "([^"]*)"$/
     */
    public function ivaoSendsCallback($token)
    {
        $this->userIsLoggedInAs($token);
    }

    /**
     * @Given /^I have a saldo of (\d+)$/
     */
    public function iHaveASaldoOf($saldo)
    {
        $this->loggedIn->setSaldo($saldo);
        $this->loggedIn->save();
    }

    /**
     * @Given /^I should have a saldo of (\d+)$/
     */
    public function assertSaldo($arg1)
    {
        expect($this->loggedIn->getSaldo())->to->equal($arg1);
    }

    /**
     * @Given /^I am subscribed to airline :airline$/
     * @param Airline $airline
     */
    public function setAirlineTo(Airline $airline)
    {
        $this->loggedIn->setAirline($airline);
    }
}
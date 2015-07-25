<?php
use Model\Airline;

/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 25.07.15
 * Time: 18:36
 */

class UserSteps extends \Behat\MinkExtension\Context\RawMinkContext implements \Behat\Behat\Context\Context{
    private $token;

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
        $user->setRank('PILOT');
        $user->save();
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
     * @param Airline $airline
     */
    public function setAirlineTo(Airline $airline)
    {
        $this->getLoggedInPilot()->setAirline($airline);
    }

    /**
     * @return \Model\Pilot
     */
    private function getLoggedInPilot()
    {
        return \Model\PilotQuery::create()->findOneByToken($this->token);
    }
}
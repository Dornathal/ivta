<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:43
 */
use Behat\Behat\Context\BehatContext;

class UserSteps extends BehatContext
{
    /**
     * @Given /^user is logged in as ([^"]*)/
     */
    public function userIsLoggedInAs($token)
    {
        $this->getMainContext()->visit('/login?IVAOTOKEN='.$token.'&site=/');
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
}
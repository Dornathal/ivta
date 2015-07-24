<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 24.07.15
 * Time: 11:52
 */
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Context\Step\Then;
use Model\AirportQuery;

class AirportSteps extends BehatContext
{
    /**
     * @Given /^I am on the airports site$/
     */
    public function iAmOnTheAirportsSite()
    {
        $this->getMainContext()->visit('/airports');
    }

    /**
     * @Given /^I am on the "([^"]*)" airports site$/
     */
    public function iAmOnTheAirportsSite1($arg1)
    {
        $this->getMainContext()->visit('/airports/' . $arg1);
    }

    /**
     * @Given /^I have "([^"]*)" not generating freight$/
     */
    public function iHaveNotGeneratingFreight($arg1)
    {
        foreach (explode("|", $arg1) as $icao) {
            $this->iAmOnTheAirportsSite1($icao);
            $freightGen = \Model\FreightGenerationQuery::create()
                ->findOneByAirportId(AirportQuery::create()->findOneByICAO($icao)->getId());
            $freightGen->setNextGeneration(time() + 100000000);
            $freightGen->save();
        }
    }

    /**
     * @Then /^I should( not)? see "([^"]*)" in "([^"]*)"$/
     */
    public function iShouldNotSeeIn($arg1, $arg2, $arg3)
    {
        $value = ($arg1 == " not") ? 'noone ' . (($arg3 == 'Departure') ? 'departing' : 'arriving') : $arg2;
        return new Then("I should see \"$value\"");
    }

    /**
     * @Given /^airport "([^"]*)" has size "([^"]*)"$/
     */
    public function airportHasSize($icao, $size)
    {
        AirportQuery::create()
            ->findOneByICAO($icao)
            ->setSize($size)
            ->save();
    }

}
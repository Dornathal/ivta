<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 24.07.15
 * Time: 11:52
 */
use Behat\MinkExtension\Context\RawMinkContext;
use Model\Airport;
use Model\AirportQuery;

class AirportSteps extends RawMinkContext implements \Behat\Behat\Context\Context
{

    /**
     * @Transform :airport
     */
    public function findAirportInDatabase($icao)
    {
        return AirportQuery::create()->findOneByICAO($icao);
    }

    /**
     * @Given I am on the airports site
     */
    public function iAmOnTheAirportsSite()
    {
        $this->visitPath('/airports');
    }

    /**
     * @Given I am on the :icao airports site
     */
    public function iAmOnTheAirportsSite1($icao)
    {
        $this->visitPath('/airports/' . $icao);
    }

    /**
     * @Given I have :airport not generating freight
     */
    public function iHaveNotGeneratingFreight(Airport $airport)
    {
        $this->iAmOnTheAirportsSite1($airport->getICAO());
        $freightGen = \Model\FreightGenerationQuery::create()
            ->findOneByAirportId($airport->getId());
        $freightGen->setNextGeneration(time() + 100000000);
        $freightGen->save();

    }

    /**
     * @Then I should not see :text in :element
     */
    public function iShouldNotSeeIn($arg2, $arg3)
    {
        $value = 'noone ' . (($arg3 == 'Departure') ? 'departing' : 'arriving');
        $this->iShouldSeeIn($value, $arg3);
    }

    /**
     * @Then I should see :text in :element
     */
    public function iShouldSeeIn($arg2, $arg3)
    {
        $this->getSession()->getPage()->hasContent($arg2);
    }
    /**
     * @Given airport :airport has size :size
     */
    public function airportHasSize(Airport $airport, $size)
    {
        $airport->setSize($size)->save();
    }

}
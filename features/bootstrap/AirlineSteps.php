<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:06
 */
use Behat\MinkExtension\Context\RawMinkContext;
use \Model\AirlineQuery;

class AirlineSteps extends RawMinkContext implements \Behat\Behat\Context\Context
{
    /**
     * @Transform :airline
     */
    public function castICAOToAirline($airline)
    {
        return AirlineQuery::create()->findOneByICAO($airline);
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
}

<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:54
 */
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Context\Context;
use Model\AirportQuery;
use Model\FreightQuery;

class FreightSteps extends \Behat\MinkExtension\Context\RawMinkContext implements  Context
{
    private $constantContainer;

    /**
     * @internal param ConstantContainer $constantContainer
     */
    public function __construct()
    {
        $this->constantContainer = new ConstantContainer();
    }

    /**
     * @Given /^there (?:is|are) (\d+) ([^"]*) at "([^"]*)" from "([^"]*)" to "([^"]*)"$/
     */
    public function thereArePackagesAtFromTo($amount, $type, $at, $from, $to)
    {
        $to = explode("|", $to);

        $freight = new \Model\Freight();
        $freight->setAmount($amount)
            ->setFreightType($this->constantContainer->FREIGHT_TYPES[$type]);
        $freight->setDeparture(AirportQuery::create()->findOneByICAO($from))
            ->setDestination(AirportQuery::create()->findOneByICAO($to[0]))
            ->setLocation(AirportQuery::create()->findOneByICAO($at));
        $freight->save();
        return $freight;
    }

    /**
     * @Then /^there should be (\d+) ([^"]*) at "([^"]*)" to "([^"]*)"$/
     */
    public function thereShouldBeAtTo($amount, $freight_type, $location, $destination)
    {
        $count = FreightQuery::create()
            ->filterByAmount($amount)
            ->filterByFreightType($this->constantContainer->FREIGHT_TYPES[$freight_type])
            ->filterByLocation(AirportQuery::create()->findOneByICAO($location))
            ->filterByDestination(AirportQuery::create()->findOneByICAO($destination))
            ->count();
        expect($count)->to->be->not->equal(0);
    }

    /**
     * @Then /^I should see (\d+) ([^"]*) (from|to) "([^"]*)"$/
     */
    public function iShouldSeeFrom($amount, $freight_type, $direction, $airport)
    {
        $page = $this->getSession()->getPage();
        $str = '';
        foreach($this->constantContainer->FREIGHT_TYPES as $key=>$value){
            $str .= "<td>".(($key == $freight_type)?$amount:0)."</td>";
        }
        $strpos = strpos($page->getContent(), "<td>{$airport}</td>{$str}");
        expect($strpos)->not->equal(false);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 23.07.15
 * Time: 13:54
 */
use Behat\Behat\Context\BehatContext;
use Model\AirportQuery;
use Model\FreightQuery;

class FreightSteps extends BehatContext
{
    public static function FREIGHT_TYPES($type)
    {
        $arr = array('Packages' => "Packages", 'Post' => "Post", 'Economy' => "PassengerLow",
            'Business' => "PassengerMid", 'First Class' => "PassengerHigh");
        return $arr[$type];
    }

    /**
     * @Given /^there (?:is|are) (\d+) ([^"]*) at "([^"]*)" from "([^"]*)" to "([^"]*)"$/
     */
    public function thereArePackagesAtFromTo($amount, $type, $at, $from, $to)
    {
        //$airports = AirportQuery::create()
        //    ->filterByICAO(array($from, $at, $to))
        //    ->find();

        $from = AirportQuery::create()->findOneByICAO($from);
        $to = AirportQuery::create()->findOneByICAO($to);
        $at = AirportQuery::create()->findOneByICAO($at);

        $freight = new \Model\Freight();
        $freight->setAmount($amount)
            ->setFreightType(self::FREIGHT_TYPES($type));
        $freight->setDeparture($from)
            ->setDestination($to)
            ->setLocation($at);
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
            ->filterByFreightType(self::FREIGHT_TYPES($freight_type))
            ->filterByLocation(AirportQuery::create()->findOneByICAO($location))
            ->filterByDestination(AirportQuery::create()->findOneByICAO($destination))
            ->count();
        expect($count)->to->be->not->equal(0);
    }
}
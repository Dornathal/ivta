<?php

namespace Model;

use Model\Base\Freight as BaseFreight;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'frights' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Freight extends BaseFreight
{
    public static $amount_modifier = ['Packages' => 10, 'Post' => 2, 'PassengerLow' => 1, 'PassengerMid' => 0.45, 'PassengerHigh' => 0.2];

    public static function loadToFlight(Flight $flight, $loaded_freight)
    {
        $freights = FreightQuery::create()
            ->filterByLocation($flight->getDeparture())
            ->orderByAmount()
            ->findByNextSteps(array($flight->getDestination()->getICAO()));

        print_r($freights->toArray());

        foreach($freights as $freight){
            $to_be_loaded = $loaded_freight[$freight->getFreightType()];
            if($to_be_loaded > 0) {
                if ($freight->getAmount() > $to_be_loaded){
                    $freight = self::splitFreight($freight, $to_be_loaded);
                }
                $freight->setOnFlight($flight);
                $freight->setLocation(null);
                $loaded_freight[$freight->getFreightType()] -= $freight->getAmount();
            }
        }

        foreach($loaded_freight as $key => $loaded){
            if($loaded > 0)
                throw new \ErrorException("Did not find enough Freight to load ". $loaded ." ". $key ." at ". $flight->getDeparture()->getICAO());
        }
    }

    private static function splitFreight(Freight $freight, $to_be_loaded)
    {
        $newFreight = $freight->copy();
        $newFreight->setAmount($freight->getAmount()-$to_be_loaded);
        $newFreight->save();
        $freight->setAmount($to_be_loaded);
        return $freight;
    }

    /**
     * Declares an association between this object and a ChildAirport object.
     *
     * @param  Airport $location
     * @return $this|\Model\Freight The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLocation(Airport $location = null)
    {
        parent::setLocation($location);
        if($location != null){
            $this->setNextSteps($location->nextStepsTo($this->getDestination()));
        }
        return $this;
    }

    public static function generateFreight(Airport $airport){

        Flight::transaction(function() use ($airport){
            $freightGeneration = $airport->getFreightGeneration();
            if($freightGeneration == null){
                $freightGeneration = new FreightGeneration();
                $freightGeneration->setAirport($airport);
                $freightGeneration->save();
            }
            $generationTimes = $freightGeneration->getGenerationTimes();

            if($generationTimes <= 0) return;
            $destination = FreightGenerationQuery::create()
                ->orderByNextGeneration()
                ->filterByNextGeneration(time(), Criteria::LESS_THAN)
                ->limit($generationTimes)
                ->filterByAirport($airport, Criteria::NOT_EQUAL)
                ->find();

            foreach($destination as $target){
                $freight = new Freight();
                $freight->setFreightType(array_keys(self::$amount_modifier)[mt_rand(0, sizeof(self::$amount_modifier) - 1)]);
                $amount = min($airport->getFreightGeneration()->getCapacity(), $target->getCapacity());
                $freight->setAmount(ceil($amount * self::$amount_modifier[$freight->getFreightType()]));
                if(mt_rand(0, 10) < 6){
                    $freight->setDeparture($airport);
                    $freight->setDestination($target->getAirport());
                }else{
                    $freight->setDestination($airport);
                    $freight->setDeparture($target->getAirport());
                }
                $freight->setLocation($freight->getDeparture());

                $available = FreightQuery::create()
                    ->filterByDeparture($freight->getDeparture())
                    ->filterByLocation($freight->getLocation())
                    ->filterByDestination($freight->getDestination())
                    ->filterByFreightType($freight->getFreightType())
                    ->findOne();
                if($available != null) {
                    $available->setAmount($available->getAmount() + $freight->getAmount());
                    $freight = $available;
                }
                $airport->getFreightGeneration()->GeneratedNewFreight($amount);
                $target->GeneratedNewFreight($amount);
                $target->save();
                $freight->save();
            }
            $airport->save();
        });
    }

}

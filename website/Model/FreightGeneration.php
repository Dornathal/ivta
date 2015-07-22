<?php

namespace Model;

use Model\Base\FreightGeneration as BaseFreightGeneration;
use Model\Map\FlightTableMap;

/**
 * Skeleton subclass for representing a row from the 'freight_generations' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class FreightGeneration extends BaseFreightGeneration
{
    private $DAY = 60 * 60 * 24;

    /**
     * Initializes internal state of Model\Base\FreightGeneration object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->setNextGeneration(time());
        $this->setLastUpdatedAt(time() - $this->DAY * 2);
    }

    /**
     * Get the [capacity] column value.
     *
     * @return int
     */
    public function getCapacity()
    {
        $this->update();
        return parent::getCapacity();
    }

    /**
     * Get the [every] column value.
     *
     * @return int
     */
    public function getEvery()
    {
        $this->update();
        return parent::getEvery();
    }

    public function getGenerationTimes()
    {
        $this->update();
        return min(10, max(0, round((time() - $this->getNextGeneration()->getTimestamp())/ $this->getEvery() + 1)));
    }

    public function GeneratedNewFreight($generatedAmount)
    {
        $v = $this->getNextGeneration()->getTimestamp() + $this->getEvery() * $generatedAmount / $this->getCapacity();
        $this->setNextGeneration($v);
    }

    private function update()
    {
        if($this->getLastUpdatedAt()->getTimestamp() - time() > $this->DAY)
            return;
        $flights = FlightQuery::create()
            ->filterByDeparture($this->getAirport())
            ->_or()
            ->filterByDestination($this->getAirport())
            ->filterByStatus(FlightTableMap::COL_STATUS_FINISHED)
            ->recentlyCreated('7')
            ->find();
        if($flights->count() == 0){
            $this->setCapacity(1);
            $this->setEvery(0.01 * $this->DAY);
        }else{
            $amount = 0;
            foreach ($flights as $flight) {
                $aircraftType = $flight->getAircraft()->getAircraftType();
                for($i=0; $i < sizeof(Freight::$amount_modifier); $i++){
                    $amount += $aircraftType->getByName(array_keys(Freight::$amount_modifier)[$i])
                        / Freight::$amount_modifier[array_keys(Freight::$amount_modifier)[$i]];
                }
            }
            $this->setCapacity($amount / $flights->count());
            $this->setEvery(7 * $this->DAY / $flights->count());
        }
        $this->setLastUpdatedAt(time());
        $this->save();
    }


}

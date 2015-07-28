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
    const DAY = 86400;
    const CalculateOverPastDays = 7;

    /**
     * Initializes internal state of Model\Base\FreightGeneration object.
     * @see applyDefaults()
     */
    public function applyDefaultValues()
    {
        parent::applyDefaultValues();
        $this->setNextGenerationAt(time() - self::CalculateOverPastDays * self::DAY);
        $this->setNextUpdateAt(time());
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
        return min(10, max(0, round((time() - $this->getNextGenerationAt()->getTimestamp()) / $this->getEvery() + 1)));
    }

    public function GeneratedNewFreight($generatedAmount)
    {
        if($generatedAmount == 0) return;
        $v = $this->getNextGenerationAt()->getTimestamp() + $this->getEvery() * $generatedAmount / $this->getCapacity();
        $this->setNextGenerationAt($v);
    }

    private function update()
    {
        if($this->getNextUpdateAt()->getTimestamp() > time())
            return;
        $this->setNextUpdateAt(time() + self::DAY);
        $flights = FlightQuery::create()
            ->filterByDeparture($this->getAirport())
            ->_or()
            ->filterByDestination($this->getAirport())
            ->filterByStatus(FlightTableMap::COL_STATUS_FINISHED)
            ->recentlyCreated(self::CalculateOverPastDays)
            ->find();
        if($flights->count() == 0){
            $this->setCapacity(1);
            $this->setEvery(self::CalculateOverPastDays * self::DAY);
        }else{
            $amount = 0;
            foreach ($flights as $flight) {
                $aircraft = $flight->getAircraft();
                for($i=0; $i < sizeof(Freight::$amount_modifier); $i++){
                    $amount += $aircraft->getByName(array_keys(Freight::$amount_modifier)[$i])
                        / Freight::$amount_modifier[array_keys(Freight::$amount_modifier)[$i]];
                }
            }
            $this->setCapacity($amount / $flights->count());
            $this->setEvery(self::CalculateOverPastDays * self::DAY / $flights->count());
        }
        $this->save();
    }
}

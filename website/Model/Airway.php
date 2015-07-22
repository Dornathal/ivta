<?php

namespace Model;

use Model\Base\Airway as BaseAirway;
use Model\Map\AirportTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for representing a row from the 'airways' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Airway extends BaseAirway
{
    const UPDATE_EVERY = 60 * 60 * 24 * 4;
    const NEXT_STEP_NUMBER = 6;

    public static function getAirway(Airport $departure,Airport $destination)
    {
        $airway = AirwayQuery::create()
            ->filterByDestination($destination)
            ->filterByDeparture($departure)
            ->findOne();
        if($airway == null){
            $airway = new Airway();
            $airway->setDestination($destination);
            $airway->setDeparture($departure);
            $airway->calculateNextSteps();
            $airway->save();
        }else{
            if($airway->getNextSteps() < self::NEXT_STEP_NUMBER
                || time() - $airway->getUpdatedAt()->getTimestamp() > self::UPDATE_EVERY)
                $airway->calculateNextSteps();
        }
        return $airway;
    }

    private static function center($a, $b)
    {
        return array(($a['latitude'] + $b['latitude'])/2 ,
            ($a['longitude'] + $b['longitude'])/2);
    }

    public function getDistance(){
        return $this->getDestination()->getDistanceTo($this->getDeparture(), AirportTableMap::NAUTICAL_MILES_UNIT);
    }

    private function calculateNextSteps()
    {
        list($latitude, $longitude) = self::center($this->getDestination()->getCoordinates(), $this->getDeparture()->getCoordinates());

        $nextSteps = AirportQuery::create()
            ->joinFreightGeneration()
            ->filterByDistanceFrom($latitude, $longitude, $this->getDistance() * 0.6)
            ->filterById(array($this->getDepartureId(), $this->getDestinationId()), Criteria::NOT_IN)
            ->orderBy("FreightGeneration.Every", Criteria::ASC)
            ->limit(self::NEXT_STEP_NUMBER)
            ->find();

        $this->setNextSteps(array($this->getDestination()->getICAO()));
        foreach ($nextSteps as $next) {
            $this->addNextStep($next->getICAO());
        }
        $this->save();
    }

}

<?php

namespace Model;

use Model\Base\Aircraft as BaseAircraft;

/**
 * Skeleton subclass for representing a row from the 'aircrafts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Aircraft extends BaseAircraft
{


    /**
     * Aircraft constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setFlownTime(0);
    }

    public function deliverToAirport(Airport $airport)
    {
        $this->setAirport($airport);
        $this->setLatitude($airport->getLatitude());
        $this->setLongitude($airport->getLongitude());
        $this->save();
    }

    public function queryFreight(){
        list($by_destination, $by_departure) = $this->getAirport()->queryFreightDiagram();
        return $by_destination;
    }
}

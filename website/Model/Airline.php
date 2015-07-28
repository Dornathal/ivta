<?php

namespace Model;

use Model\Base\Airline as BaseAirline;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for representing a row from the 'airlines' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Airline extends BaseAirline
{


    public function queryAirplanes(){
        $aircraftQuery = AircraftQuery::create()
            ->filterByAirline($this)
            ->filterByStatus(0, Criteria::NOT_EQUAL);
        return AircraftQuery::populateAircraftTable($aircraftQuery);
    }

    public function queryAirlineView($page)
    {
        return array('airline' => $this->toArray(),
            'aircrafts' => $this->queryAirplanes(),
            'flights' => FlightQuery::queryFlights($page, FlightQuery::create()->filterByAirline($this))
        );
    }


}

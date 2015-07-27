<?php

namespace Model;

use Model\Base\AircraftQuery as BaseAircraftQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'aircrafts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AircraftQuery extends BaseAircraftQuery
{

    public static function populateAircraftTable(AircraftQuery $aircraftQuery)
    {
        return $aircraftQuery
            ->joinAirport()
            ->joinAircraftModel()
            ->select(array('Callsign','FlownDistance','NumberFlights','FlownTime','Status'))
            ->withColumn('Airport.icao', 'Location')
            ->withColumn('AircraftModel.model', 'Model')
            ->find()->toArray();

    }
}

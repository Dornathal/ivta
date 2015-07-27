<?php

namespace Model;

use Model\Base\FlightQuery as BaseFlightQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'flights' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class FlightQuery extends BaseFlightQuery
{
    public static function queryFlights(FlightQuery $flightQuery = null){
        if($flightQuery==null)
            $flightQuery = FlightQuery::create();
        $flightQuery
            ->joinDeparture('Departure')
            ->joinDestination('Destination')
            ->joinAircraft('Aircraft')
            ->select(array('id','flight_number','flight_started_at','flight_finished_at'))
            ->withColumn('Departure.icao', 'Departure')
            ->withColumn('Destination.icao', 'Destination')
            ->withColumn('Aircraft.callsign', 'Callsign');

        return $flightQuery->find()->toArray();
    }
}

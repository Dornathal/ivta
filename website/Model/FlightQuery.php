<?php

namespace Model;

use Model\Base\FlightQuery as BaseFlightQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class FlightQuery extends BaseFlightQuery
{
    public static function queryFlights($page, FlightQuery $flightQuery = null){
        if($flightQuery==null)
            $flightQuery = FlightQuery::create();
        $flightQuery
            ->orderByUpdatedAt(Criteria::DESC)
            ->joinDeparture('Departure')
            ->joinDestination('Destination')
            ->joinAircraft('Aircraft')
            ->joinAircraftModel('Model')
            ->select(array('Id','FlightNumber','FlightStartedAt','FlightFinishedAt','Status'))
            ->withColumn('Departure.icao', 'Departure')
            ->withColumn('Destination.icao', 'Destination')
            ->withColumn('Aircraft.callsign', 'Callsign')
            ->withColumn('Model.model', 'Model');

        $flights = $flightQuery->paginate($page, 7);
        return $flights;
    }
}

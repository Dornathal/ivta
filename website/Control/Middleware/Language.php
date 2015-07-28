<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 12.07.15
 * Time: 13:34
 */

namespace Control\Middleware;


use Slim\Middleware;

class Language extends Middleware
{

    public function call()
    {
        $app = $this->app;

        $insert = array(
            "at_airport" => "At the Airport",
            "aircraft" => "Aircraft",
            "aircraft_model" => "Aircraft Model",
            "aircrafts" => "Aircrafts",
            "airport" => "Airport",
            "airports" => "Airports",
            "airline" => "Airline",
            "airlines" => "Airlines",
            "arriving" => "Arriving" ,
            "brand" => "Brand" ,
            "buy" => "Buy" ,
            "buy_not_possible" => "Not enough Saldo" ,
            "buy_one" => "Buy one " ,
            "by_destinations" => "By Destinations" ,
            "by_departures" => "By Departures" ,
            "callsign" => "Callsign" ,
            "cancel" => "Cancel" ,
            "currently_noone" => "Currently is noone " ,
            "deliver" => "Deliver" ,
            "departing" => "Departing" ,
            "departure" => "Departure" ,
            "destination" => "Destination" ,
            "destinations" => "Destinations" ,
            "en_route" => "Flying" ,
            "flight" => "Flight" ,
            "flight_abort" => "Cancel flight" ,
            "flight_finish" => "Finish" ,
            "flight_start" => "Start flight" ,
            "flight_start_loading" => "Start loading" ,
            "flight_start_unloading" => "Start unloading" ,
            "freight" => "Freight",
            "icao" => "ICAO" ,
            "map" => "Map",
            "name" => "Name",
            "no_aircrafts_available" => "No Aircrafts active.",
            "no_freight_available" => "No freight available.",
            "passenger" => "Passenger",
            "plan_to" => "Plan to ",
            "price_new" => "Price New",
            "price_tag" => "€",
            "saldo" => "Saldo",
            "saldo_current" => "Current saldo",
            "saldo_new" => "New saldo",
            "search" => "Search",
            "show" => "Show",
            "status" => "Status",
            "status_idle" => "Idle",
            "status_loading" => "Loading",
            "status_unloading" => "Unloading",
            "status_en_route" => "Flying",
            "status_finished" => "Finished",
            "status_aborted" => "Canceled",
        );

        $app->view->appendData(array("Language" => $insert));
        $this->next->call();
    }
}
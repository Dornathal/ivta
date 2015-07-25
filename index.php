<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 11.07.15
 * Time: 22:25
 */

use Control\Bootstrap;
use Control\Container;
use Control\EntityCreator;
use Model\AircraftQuery;
use Model\AircraftTypeQuery;
use Model\Airline;
use Model\AirlineQuery;
use Model\Airport;
use Model\AirportQuery;
use Model\Flight;
use Model\FlightQuery;
use Model\Pilot;
use Model\PilotQuery;
use Propel\Runtime\ActiveQuery\Criteria;

require 'vendor/autoload.php';
require 'website/config.php';

define('APPLICATION_PATH', realpath(dirname(__DIR__)));
define('WEBSITE_ROOT', '/index.php');

date_default_timezone_set('UTC');

$app = new \Slim\Slim(array('view' => new \Slim\Views\Twig(), 'debug' => true,
    'cookies.encrypt' => true,
    'cookies.secret_key' => '6yxwi8fg4tr72',
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC
));

$container = new Container();
$bootstrap = new Bootstrap($app, $container);
$app = $bootstrap->bootstrap();

$user = Pilot::login($app);

$app->view()->appendData(array('current_url' => $app->request()->getPath()));

$app->get('/', function () use ($app) {
    $app->render('home.twig');
})->name('home');

/**
 * @param $app
 * @param $aircraft
 */
function showAllAircrafts(Slim\Slim $app, Propel\Runtime\Collection\ObjectCollection $aircraft)
{
    $app->view()->appendData(array('aircrafts' => $aircraft->toArray()));
    $app->render('view_all_aircraft_models.twig');
}

function rank_in(Pilot $user, $array){
    return array_key_exists($user->getRank(), $array);
}

$app->group('/aircraft', function () use ($app, $user) {

    $app->group('/models', function () use ($app, $user) {

        $app->get('', function () use ($app) {
            $model = $app->request()->get('Model');
            if ($model) $app->redirectTo('aircraftmodelview', array("Model" => $model));

            $aircrafts = AircraftTypeQuery::create()->orderByModel()->find();
            showAllAircrafts($app, $aircrafts);
        })->name('aircraftallmodelsview');

        $app->group('/:Model', function () use ($app, $user) {

            $app->post('', function ($Model) use ($app, $user) {
                $aircraft_number = $app->request->post('aircraft_number');
                $aircraft_model = AircraftTypeQuery::create()->findOneByModel($Model);

                $aircraft = $user->buyAircraft($aircraft_model, $aircraft_number);
                $app->redirectTo('airlineview', array("Callsign" => $aircraft->getCallsign()));
            });

            $app->get('', function ($Model) use ($app, $user) {
                $model = $app->request()->get('Model');
                if ($model) $app->redirectTo('aircraftmodelview', array("Model" => $model));

                $aircraft = AircraftTypeQuery::create()->filterByModel($Model . '%', Criteria::LIKE)->find();
                if ($aircraft->count() == 1) {
                    if ($user != null && $user->getAirline() != null)
                        $app->view()->appendData(array('airline' => $user->getAirline()->toArray(), 'user' => $user->toArray()));
                    $app->view()->appendData(array('aircraft' => $aircraft->getFirst()->toArray()));
                    $app->render('view_aircraft_model.twig');
                } else if ($aircraft->count() > 0) {
                    showAllAircrafts($app, $aircraft);
                } else {
                    $app->redirectTo('aircraftallmodelsview');
                }
            })->name('aircraftmodelview');
        });
    });

    $app->group('/:Callsign', function () use ($app, $user) {

        $app->get('', function ($Callsign) use ($app) {
            $aircraft = AircraftQuery::create()->findOneByCallsign($Callsign);

            $app->view()->appendData(array('location' => $aircraft->getAirport()->getICAO(), 'aircraft' => $aircraft->toArray(),
                'freights' => $aircraft->queryFreight()));
            $app->render('view_aircraft.twig');
        })->name('aircraftview');

        $app->get('/plan/:Route',function ($Callsign, $Route) use ($app, $user) {
            $keys = array('departure' => 0, 'destination' => 1);
            $route = explode('-', $Route, 2);

            $aircraft = AircraftQuery::create()->findOneByCallsign($Callsign);
            $destination = AirportQuery::create()->findOneByICAO($route[$keys['destination']]);

            $flight = Flight::planFlight($user, $aircraft, $destination);

            echo $flight->getId();

            $app->redirectTo('flightview', array('Callsign' => $Callsign, 'FlightNumber' => $flight->getFlightNumber(), 'FlightId' => $flight->getId()));
        });

        $app->get('/:FlightNumber/:FlightId', function ($Callsign, $FlightNumber, $FlightId) use ($app) {
            $flight = FlightQuery::create()
                ->findOneById($FlightId);

            $status = $app->request()->get('Status');
            if ($status != null && $status == 'Next') {
                $flight->nextStatus();
                $app->redirectTo('flightview', array('Callsign' => $Callsign, "FlightNumber" => $FlightNumber, "FlightId" => $FlightId));
            }

            $app->view()->appendData($flight->queryFlightData());
            $app->render('flight_details.twig');
        })->name('flightview');
    });
});

$app->group('/airports', function () use ($app) {

    $app->get('', function () use ($app) {
        $icao = $app->request()->get('ICAO');
        if ($icao) $app->redirectTo('airportview', array("icao" => $icao));

        $airports = AirportQuery::create()
            ->joinFreightGeneration()
            ->orderBy('FreightGeneration.Capacity',Criteria::ASC)
            ->limit(10)
            ->find()->toArray();
        $app->view()->appendData(array('airports' => $airports));
        $app->render('view_all_airports.twig');
    });
    $app->group('/:icao', function () use ($app) {

        $app->post('', function ($icao) use ($app) {
            $airport = new Airport();
            $airport->setICAO($icao);

            $creator = new EntityCreator($airport);
            $creator->setRequirement('Name', 'Airport Name', true, 56);
            $creator->setRequirement('Size', 'Airport Size', true, 2);
            if ($creator->build($app))
                $app->redirectTo('airportview', array("icao" => $icao));
        });

        $app->get('', function ($icao) use ($app) {
            if ($app->request()->get('ICAO')) $app->redirectTo('airportview', array("icao" => $app->request()->get('ICAO')));

            $keys = array('departure' => 0, 'destination' => 1);
            $icao = explode('-', $icao, 2);

            $airport = AirportQuery::create()
                ->filterByICAO($icao[0] . '%', Criteria::LIKE);
            if(sizeof($icao) == 2)
                $airport = $airport->filterByName($icao[1]);
            $airport = $airport->limit(10)->find();

            if ($airport->count() == 0) {
                $app->view()->appendData(array('airportData' => array('ICAO' => strtoupper($icao[0]))));
                $app->render('edit_airport.twig');
            } else if ($airport->count() == 1) {
                $airport = $airport->getFirst();
                list($fright_by_destination, $fright_by_departure) = $airport->queryFreightDiagram();
                list($departing, $en_route, $arriving) = $airport->queryFlightTable();

                $app->view()->appendData(array('freights' => array('by_departure' => $fright_by_departure, 'by_destination' => $fright_by_destination)));
                $app->view()->appendData(array('aircrafts' => array('departing' => $departing, 'en_route' => $en_route, 'arriving' => $arriving)));

                $app->view()->appendData(array('airportData' => $airport->toArray()));
                $app->render('view_airport.twig');
            } else {
                echo $airport->count() . ' found.';
                $app->view()->appendData(array('airports' => $airport));
                $app->render('view_all_airports.twig');
            }

        })->name("airportview");
    });
});

$app->group('/airlines', function () use ($app) {

    $app->get('', function () use ($app) {
        $callsign = $app->request()->get('ICAO');
        if ($callsign) $app->redirectTo('airlineview', array("ICAO" => strtoupper($callsign)));

        $airlines = AirlineQuery::create()
            ->orderByICAO()
            ->joinAircraft()
            ->setDistinct()
            ->limit(10)
            ->find()
            ->toArray();
        $app->view()->appendData(array('airlines' => $airlines));
        $app->render('view_all_airlines.twig');
    });

    $app->group('/:ICAO', function () use ($app) {

        $app->get('', function ($icao) use ($app) {
            $airline = AirlineQuery::create()->findOneByICAO($icao);
            if ($airline != null) {
                $app->view()->appendData(array('airline' => $airline->toArray(), 'aircrafts' => $airline->queryAirplanes(), 'airports' => Airport::queryDeliverableAirports()));
                $app->render('view_airline.twig');
            } else if (strlen($icao) <= 4 && strlen($icao) >= 2) {
                $app->view()->appendData(array('airlineData' => array('ICAO' => strtoupper($icao))));
                $app->render('edit_airline.twig');
            }
        })->name('airlineview');
    });
});

$app->get('/import/:type', function ($type) use ($app) {
    $importer = new Import();
    switch($type){
        case 'airports':
            $importer->airports();
            break;
        case 'airlines':
            $importer->airlines();
            break;
    }
});

$app->get('/deliver', function () use ($app) {
    $Callsign = $app->request()->params('Callsign');
    $Icao = $app->request()->params('Icao');
    $aircraft = AircraftQuery::create()->findOneByCallsign($Callsign);
    $airport = AirportQuery::create()->findOneByICAO($Icao);
    $aircraft->deliverToAirport($airport);
    $app->redirectTo('aircraftview', array('Callsign' => $Callsign));
});

$app->get('/login', function () use ($app) {
    $app->setCookie('IVAOTOKEN', $app->request->get('IVAOTOKEN'));
    $app->redirect($app->request->get('site'));
});

$app->get('/logout', function () use ($app) {
    $app->deleteCookie('IVAOTOKEN');
    $app->redirect($app->request->get('site'));
});

$app->run();

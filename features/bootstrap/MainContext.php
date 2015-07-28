<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 22.07.15
 * Time: 23:49
 */

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Model\AircraftQuery;
use Model\AircraftModelQuery;
use Model\AirwayQuery;
use Model\FlightQuery;
use Model\FreightGenerationQuery;
use Model\FreightQuery;
use Model\Map\FlightTableMap;
use Propel\Runtime\Propel;

date_default_timezone_set('UTC');

require 'website/config.php';


class MainContext extends RawMinkContext implements Context
{
    const SQL_FILE = 'resources/default.sql';

    /**
     * FeatureContext constructor.
     */
    public function __construct()
    {
        $this->constantContainer = new ConstantContainer();
    }


    /**
     * @BeforeSuite
     * @param BeforeSuiteScope $event
     */
    public static function setup(BeforeSuiteScope $event)
    {

        Propel::disableInstancePooling();
        $con = Propel::getConnection(FlightTableMap::DATABASE_NAME);
        $sql = file_get_contents(self::SQL_FILE);
        $con->exec($sql);
        $import = new Import();
        $import->airports();
        $import->airlines();
        $import->aircraft_models();
        UserSteps::addUser();
    }

    /** @BeforeScenario*/
    public function before($event)
    {
        $this->cleanDatabase();
    }

    public function cleanDatabase(){
        $queries = array(
            FreightQuery::create(),
            FlightQuery::create(),
            AircraftQuery::create(),
            AirwayQuery::create(),
            FreightGenerationQuery::create()
        );
        foreach ($queries as $query) {
            $query ->deleteAll();
        }
    }

    /**
     * @When I wait :time second
     */
    public function wait($time)
    {
        sleep($time);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 22.07.15
 * Time: 23:49
 */
use Behat\Behat\Event\SuiteEvent;
use Behat\MinkExtension\Context\MinkContext;
use Model\AircraftQuery;
use Model\AircraftTypeQuery;
use Model\AirwayQuery;
use Model\FlightQuery;
use Model\FreightGenerationQuery;
use Model\FreightQuery;
use Model\Map\FlightTableMap;
use Propel\Runtime\Propel;

define('DB_HOST',getenv('OPENSHIFT_MYSQL_DB_HOST'));
define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));

require '.generated/config.php';

date_default_timezone_set('UTC');

class FeatureContext extends MinkContext
{

    const SQL_FILE = 'resources/default.sql';

    /**
     * FeatureContext constructor.
     */
    public function __construct()
    {
        $this->useContext('aircraft', new AircraftSteps());
        $this->useContext('aircraft_model_steps', new AircraftModelSteps());
        $this->useContext('airline_steps', new AirlineSteps());
        $this->useContext('flight_steps', new FlightSteps());
        $this->useContext('freight_steps', new FreightSteps());
        $this->useContext('user_steps', new UserSteps());
    }

    /**
     * @BeforeSuite
     */
    public static function prepare(SuiteEvent $event)
    {
        Propel::disableInstancePooling();
        $con = Propel::getConnection(FlightTableMap::DATABASE_NAME);
        $sql = file_get_contents(self::SQL_FILE);
        $con->exec($sql);
        $import = new Import();
        $import->airports();
        $import->airlines();

        UserSteps::addUser();
    }

    /** @BeforeScenario */
    public function before($event)
    {
        $this->cleanDatabase();
    }

    public function cleanDatabase(){
        $queries = array(
            FreightQuery::create(),
            FlightQuery::create(),
            AircraftQuery::create(),
            AircraftTypeQuery::create(),
            AirwayQuery::create(),
            FreightGenerationQuery::create()
            );
        foreach ($queries as $query) {
            $query ->deleteAll();
        }
    }

    public function spin ($lambda, $wait = 60)
    {
        for ($i = 0; $i < $wait; $i++)
        {
            try {
                if ($lambda($this)) {
                    return true;
                }
            } catch (Exception $e) {
                // do nothing
            }

            sleep(1);
        }

        $backtrace = debug_backtrace();

        throw new Exception(
            "Timeout thrown by " . $backtrace[1]['class'] . "::" . $backtrace[1]['function'] . "()\n" .
            $backtrace[1]['file'] . ", line " . $backtrace[1]['line']
        );
    }

    /**
     * @When /^I wait (\d+) second/
     */
    public function iWaitSecond($time)
    {
        sleep($time);
    }

}
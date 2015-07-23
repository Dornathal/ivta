<?php
/**
 * Created by PhpStorm.
 * User: dornathal
 * Date: 22.07.15
 * Time: 23:49
 */
use Behat\Behat\Event\SuiteEvent;
use Behat\MinkExtension\Context\MinkContext;
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
        $this->useContext('aircraft_model_steps', new AircraftModelSteps());
        echo $this->getMinkParameters();
    }

    /**
     * @BeforeSuite
     */
    public static function prepare(SuiteEvent $event)
    {
        $con = Propel::getConnection(FlightTableMap::DATABASE_NAME);
        $sql = file_get_contents(self::SQL_FILE);
        $con->exec($sql);
    }

    /** @BeforeScenario */
    public function before($event)
    {
        $con = Propel::getConnection(FlightTableMap::DATABASE_NAME);
        //$con->beginTransaction();
    }

    /** @AfterScenario */
    public function after($event)
    {
        $con = Propel::getConnection(FlightTableMap::DATABASE_NAME);
        $con->rollback();
    }

}
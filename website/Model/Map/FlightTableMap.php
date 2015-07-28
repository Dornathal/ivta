<?php

namespace Model\Map;

use Model\Flight;
use Model\FlightQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'flights' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class FlightTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.FlightTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'flights';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Flight';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Flight';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 19;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 19;

    /**
     * the column name for the id field
     */
    const COL_ID = 'flights.id';

    /**
     * the column name for the aircraft_id field
     */
    const COL_AIRCRAFT_ID = 'flights.aircraft_id';

    /**
     * the column name for the aircraft_model_id field
     */
    const COL_AIRCRAFT_MODEL_ID = 'flights.aircraft_model_id';

    /**
     * the column name for the airline_id field
     */
    const COL_AIRLINE_ID = 'flights.airline_id';

    /**
     * the column name for the destination_id field
     */
    const COL_DESTINATION_ID = 'flights.destination_id';

    /**
     * the column name for the departure_id field
     */
    const COL_DEPARTURE_ID = 'flights.departure_id';

    /**
     * the column name for the pilot_id field
     */
    const COL_PILOT_ID = 'flights.pilot_id';

    /**
     * the column name for the flight_number field
     */
    const COL_FLIGHT_NUMBER = 'flights.flight_number';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'flights.status';

    /**
     * the column name for the packages field
     */
    const COL_PACKAGES = 'flights.packages';

    /**
     * the column name for the post field
     */
    const COL_POST = 'flights.post';

    /**
     * the column name for the passenger_low field
     */
    const COL_PASSENGER_LOW = 'flights.passenger_low';

    /**
     * the column name for the passenger_mid field
     */
    const COL_PASSENGER_MID = 'flights.passenger_mid';

    /**
     * the column name for the passenger_high field
     */
    const COL_PASSENGER_HIGH = 'flights.passenger_high';

    /**
     * the column name for the flight_started_at field
     */
    const COL_FLIGHT_STARTED_AT = 'flights.flight_started_at';

    /**
     * the column name for the flight_finished_at field
     */
    const COL_FLIGHT_FINISHED_AT = 'flights.flight_finished_at';

    /**
     * the column name for the next_step_possible_at field
     */
    const COL_NEXT_STEP_POSSIBLE_AT = 'flights.next_step_possible_at';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'flights.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'flights.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the status field */
    const COL_STATUS_PLANNING = 'PLANNING';
    const COL_STATUS_LOADING = 'LOADING';
    const COL_STATUS_EN_ROUTE = 'EN_ROUTE';
    const COL_STATUS_UNLOADING = 'UNLOADING';
    const COL_STATUS_FINISHED = 'FINISHED';
    const COL_STATUS_ABORTED = 'ABORTED';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'AircraftId', 'AircraftModelId', 'AirlineId', 'DestinationId', 'DepartureId', 'PilotId', 'FlightNumber', 'Status', 'Packages', 'Post', 'PassengerLow', 'PassengerMid', 'PassengerHigh', 'FlightStartedAt', 'FlightFinishedAt', 'NextStepPossibleAt', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'aircraftId', 'aircraftModelId', 'airlineId', 'destinationId', 'departureId', 'pilotId', 'flightNumber', 'status', 'packages', 'post', 'passengerLow', 'passengerMid', 'passengerHigh', 'flightStartedAt', 'flightFinishedAt', 'nextStepPossibleAt', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(FlightTableMap::COL_ID, FlightTableMap::COL_AIRCRAFT_ID, FlightTableMap::COL_AIRCRAFT_MODEL_ID, FlightTableMap::COL_AIRLINE_ID, FlightTableMap::COL_DESTINATION_ID, FlightTableMap::COL_DEPARTURE_ID, FlightTableMap::COL_PILOT_ID, FlightTableMap::COL_FLIGHT_NUMBER, FlightTableMap::COL_STATUS, FlightTableMap::COL_PACKAGES, FlightTableMap::COL_POST, FlightTableMap::COL_PASSENGER_LOW, FlightTableMap::COL_PASSENGER_MID, FlightTableMap::COL_PASSENGER_HIGH, FlightTableMap::COL_FLIGHT_STARTED_AT, FlightTableMap::COL_FLIGHT_FINISHED_AT, FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT, FlightTableMap::COL_CREATED_AT, FlightTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'aircraft_id', 'aircraft_model_id', 'airline_id', 'destination_id', 'departure_id', 'pilot_id', 'flight_number', 'status', 'packages', 'post', 'passenger_low', 'passenger_mid', 'passenger_high', 'flight_started_at', 'flight_finished_at', 'next_step_possible_at', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AircraftId' => 1, 'AircraftModelId' => 2, 'AirlineId' => 3, 'DestinationId' => 4, 'DepartureId' => 5, 'PilotId' => 6, 'FlightNumber' => 7, 'Status' => 8, 'Packages' => 9, 'Post' => 10, 'PassengerLow' => 11, 'PassengerMid' => 12, 'PassengerHigh' => 13, 'FlightStartedAt' => 14, 'FlightFinishedAt' => 15, 'NextStepPossibleAt' => 16, 'CreatedAt' => 17, 'UpdatedAt' => 18, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'aircraftId' => 1, 'aircraftModelId' => 2, 'airlineId' => 3, 'destinationId' => 4, 'departureId' => 5, 'pilotId' => 6, 'flightNumber' => 7, 'status' => 8, 'packages' => 9, 'post' => 10, 'passengerLow' => 11, 'passengerMid' => 12, 'passengerHigh' => 13, 'flightStartedAt' => 14, 'flightFinishedAt' => 15, 'nextStepPossibleAt' => 16, 'createdAt' => 17, 'updatedAt' => 18, ),
        self::TYPE_COLNAME       => array(FlightTableMap::COL_ID => 0, FlightTableMap::COL_AIRCRAFT_ID => 1, FlightTableMap::COL_AIRCRAFT_MODEL_ID => 2, FlightTableMap::COL_AIRLINE_ID => 3, FlightTableMap::COL_DESTINATION_ID => 4, FlightTableMap::COL_DEPARTURE_ID => 5, FlightTableMap::COL_PILOT_ID => 6, FlightTableMap::COL_FLIGHT_NUMBER => 7, FlightTableMap::COL_STATUS => 8, FlightTableMap::COL_PACKAGES => 9, FlightTableMap::COL_POST => 10, FlightTableMap::COL_PASSENGER_LOW => 11, FlightTableMap::COL_PASSENGER_MID => 12, FlightTableMap::COL_PASSENGER_HIGH => 13, FlightTableMap::COL_FLIGHT_STARTED_AT => 14, FlightTableMap::COL_FLIGHT_FINISHED_AT => 15, FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT => 16, FlightTableMap::COL_CREATED_AT => 17, FlightTableMap::COL_UPDATED_AT => 18, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'aircraft_id' => 1, 'aircraft_model_id' => 2, 'airline_id' => 3, 'destination_id' => 4, 'departure_id' => 5, 'pilot_id' => 6, 'flight_number' => 7, 'status' => 8, 'packages' => 9, 'post' => 10, 'passenger_low' => 11, 'passenger_mid' => 12, 'passenger_high' => 13, 'flight_started_at' => 14, 'flight_finished_at' => 15, 'next_step_possible_at' => 16, 'created_at' => 17, 'updated_at' => 18, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                FlightTableMap::COL_STATUS => array(
                            self::COL_STATUS_PLANNING,
            self::COL_STATUS_LOADING,
            self::COL_STATUS_EN_ROUTE,
            self::COL_STATUS_UNLOADING,
            self::COL_STATUS_FINISHED,
            self::COL_STATUS_ABORTED,
        ),
    );

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return static::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     * @param string $colname
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        return $valueSets[$colname];
    }

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('flights');
        $this->setPhpName('Flight');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Flight');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('aircraft_id', 'AircraftId', 'INTEGER' , 'aircrafts', 'id', true, null, null);
        $this->addForeignPrimaryKey('aircraft_model_id', 'AircraftModelId', 'INTEGER' , 'aircraft_models', 'id', true, null, null);
        $this->addForeignPrimaryKey('airline_id', 'AirlineId', 'INTEGER' , 'airlines', 'id', true, null, null);
        $this->addForeignPrimaryKey('destination_id', 'DestinationId', 'INTEGER' , 'airports', 'id', true, null, null);
        $this->addForeignPrimaryKey('departure_id', 'DepartureId', 'INTEGER' , 'airports', 'id', true, null, null);
        $this->addForeignPrimaryKey('pilot_id', 'PilotId', 'INTEGER' , 'pilots', 'id', true, null, null);
        $this->addColumn('flight_number', 'FlightNumber', 'VARCHAR', true, 10, null);
        $this->addColumn('status', 'Status', 'ENUM', true, null, 'PLANNING');
        $this->getColumn('status')->setValueSet(array (
  0 => 'PLANNING',
  1 => 'LOADING',
  2 => 'EN_ROUTE',
  3 => 'UNLOADING',
  4 => 'FINISHED',
  5 => 'ABORTED',
));
        $this->addColumn('packages', 'Packages', 'SMALLINT', true, null, 0);
        $this->addColumn('post', 'Post', 'SMALLINT', true, null, 0);
        $this->addColumn('passenger_low', 'PassengerLow', 'SMALLINT', true, null, 0);
        $this->addColumn('passenger_mid', 'PassengerMid', 'SMALLINT', true, null, 0);
        $this->addColumn('passenger_high', 'PassengerHigh', 'SMALLINT', true, null, 0);
        $this->addColumn('flight_started_at', 'FlightStartedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('flight_finished_at', 'FlightFinishedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('next_step_possible_at', 'NextStepPossibleAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Aircraft', '\\Model\\Aircraft', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':aircraft_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('AircraftModel', '\\Model\\AircraftModel', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':aircraft_model_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Airline', '\\Model\\Airline', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':airline_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Destination', '\\Model\\Airport', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':destination_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Departure', '\\Model\\Airport', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':departure_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Pilot', '\\Model\\Pilot', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':pilot_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Freight', '\\Model\\Freight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':flight_id',
    1 => ':id',
  ),
), null, null, 'Freights', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Model\Flight $obj A \Model\Flight object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getId(), (string) $obj->getAircraftId(), (string) $obj->getAircraftModelId(), (string) $obj->getAirlineId(), (string) $obj->getDestinationId(), (string) $obj->getDepartureId(), (string) $obj->getPilotId()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Model\Flight object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Model\Flight) {
                $key = serialize(array((string) $value->getId(), (string) $value->getAircraftId(), (string) $value->getAircraftModelId(), (string) $value->getAirlineId(), (string) $value->getDestinationId(), (string) $value->getDepartureId(), (string) $value->getPilotId()));

            } elseif (is_array($value) && count($value) === 7) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1], (string) $value[2], (string) $value[3], (string) $value[4], (string) $value[5], (string) $value[6]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Model\Flight object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('AircraftId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('DestinationId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('DepartureId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('AircraftId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('DestinationId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('DepartureId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 6 + $offset : static::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('AircraftId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 3 + $offset
                : self::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 4 + $offset
                : self::translateFieldName('DestinationId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 5 + $offset
                : self::translateFieldName('DepartureId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 6 + $offset
                : self::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? FlightTableMap::CLASS_DEFAULT : FlightTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Flight object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FlightTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FlightTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FlightTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FlightTableMap::OM_CLASS;
            /** @var Flight $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FlightTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FlightTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FlightTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Flight $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FlightTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FlightTableMap::COL_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_AIRCRAFT_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_AIRCRAFT_MODEL_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_AIRLINE_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_DESTINATION_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_DEPARTURE_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_PILOT_ID);
            $criteria->addSelectColumn(FlightTableMap::COL_FLIGHT_NUMBER);
            $criteria->addSelectColumn(FlightTableMap::COL_STATUS);
            $criteria->addSelectColumn(FlightTableMap::COL_PACKAGES);
            $criteria->addSelectColumn(FlightTableMap::COL_POST);
            $criteria->addSelectColumn(FlightTableMap::COL_PASSENGER_LOW);
            $criteria->addSelectColumn(FlightTableMap::COL_PASSENGER_MID);
            $criteria->addSelectColumn(FlightTableMap::COL_PASSENGER_HIGH);
            $criteria->addSelectColumn(FlightTableMap::COL_FLIGHT_STARTED_AT);
            $criteria->addSelectColumn(FlightTableMap::COL_FLIGHT_FINISHED_AT);
            $criteria->addSelectColumn(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT);
            $criteria->addSelectColumn(FlightTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(FlightTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.aircraft_id');
            $criteria->addSelectColumn($alias . '.aircraft_model_id');
            $criteria->addSelectColumn($alias . '.airline_id');
            $criteria->addSelectColumn($alias . '.destination_id');
            $criteria->addSelectColumn($alias . '.departure_id');
            $criteria->addSelectColumn($alias . '.pilot_id');
            $criteria->addSelectColumn($alias . '.flight_number');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.packages');
            $criteria->addSelectColumn($alias . '.post');
            $criteria->addSelectColumn($alias . '.passenger_low');
            $criteria->addSelectColumn($alias . '.passenger_mid');
            $criteria->addSelectColumn($alias . '.passenger_high');
            $criteria->addSelectColumn($alias . '.flight_started_at');
            $criteria->addSelectColumn($alias . '.flight_finished_at');
            $criteria->addSelectColumn($alias . '.next_step_possible_at');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(FlightTableMap::DATABASE_NAME)->getTable(FlightTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FlightTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FlightTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FlightTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Flight or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Flight object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FlightTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Flight) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FlightTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(FlightTableMap::COL_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(FlightTableMap::COL_AIRCRAFT_ID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(FlightTableMap::COL_AIRCRAFT_MODEL_ID, $value[2]));
                $criterion->addAnd($criteria->getNewCriterion(FlightTableMap::COL_AIRLINE_ID, $value[3]));
                $criterion->addAnd($criteria->getNewCriterion(FlightTableMap::COL_DESTINATION_ID, $value[4]));
                $criterion->addAnd($criteria->getNewCriterion(FlightTableMap::COL_DEPARTURE_ID, $value[5]));
                $criterion->addAnd($criteria->getNewCriterion(FlightTableMap::COL_PILOT_ID, $value[6]));
                $criteria->addOr($criterion);
            }
        }

        $query = FlightQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FlightTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FlightTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the flights table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FlightQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Flight or Criteria object.
     *
     * @param mixed               $criteria Criteria or Flight object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FlightTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Flight object
        }

        if ($criteria->containsKey(FlightTableMap::COL_ID) && $criteria->keyContainsValue(FlightTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FlightTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = FlightQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FlightTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FlightTableMap::buildTableMap();

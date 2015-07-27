<?php

namespace Model\Map;

use Model\Aircraft;
use Model\AircraftQuery;
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
 * This class defines the structure of the 'aircrafts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AircraftTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.AircraftTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'aircrafts';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Aircraft';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Aircraft';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the id field
     */
    const COL_ID = 'aircrafts.id';

    /**
     * the column name for the aircraft_model_id field
     */
    const COL_AIRCRAFT_MODEL_ID = 'aircrafts.aircraft_model_id';

    /**
     * the column name for the airline_id field
     */
    const COL_AIRLINE_ID = 'aircrafts.airline_id';

    /**
     * the column name for the airport_id field
     */
    const COL_AIRPORT_ID = 'aircrafts.airport_id';

    /**
     * the column name for the pilot_id field
     */
    const COL_PILOT_ID = 'aircrafts.pilot_id';

    /**
     * the column name for the callsign field
     */
    const COL_CALLSIGN = 'aircrafts.callsign';

    /**
     * the column name for the flown_distance field
     */
    const COL_FLOWN_DISTANCE = 'aircrafts.flown_distance';

    /**
     * the column name for the number_flights field
     */
    const COL_NUMBER_FLIGHTS = 'aircrafts.number_flights';

    /**
     * the column name for the flown_time field
     */
    const COL_FLOWN_TIME = 'aircrafts.flown_time';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'aircrafts.status';

    /**
     * the column name for the latitude field
     */
    const COL_LATITUDE = 'aircrafts.latitude';

    /**
     * the column name for the longitude field
     */
    const COL_LONGITUDE = 'aircrafts.longitude';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the status field */
    const COL_STATUS_IDLE = 'IDLE';
    const COL_STATUS_LOADING = 'LOADING';
    const COL_STATUS_EN_ROUTE = 'EN_ROUTE';
    const COL_STATUS_UNLOADING = 'UNLOADING';

    // geocodable behavior

    /**
     * Kilometers unit
     */
    const KILOMETERS_UNIT = 1.609344;

    /**
     * Miles unit
     */
    const MILES_UNIT = 1.1515;

    /**
     * Nautical miles unit
     */
    const NAUTICAL_MILES_UNIT = 0.8684;

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'AircraftModelId', 'AirlineId', 'AirportId', 'PilotId', 'Callsign', 'FlownDistance', 'NumberFlights', 'FlownTime', 'Status', 'Latitude', 'Longitude', ),
        self::TYPE_CAMELNAME     => array('id', 'aircraftModelId', 'airlineId', 'airportId', 'pilotId', 'callsign', 'flownDistance', 'numberFlights', 'flownTime', 'status', 'latitude', 'longitude', ),
        self::TYPE_COLNAME       => array(AircraftTableMap::COL_ID, AircraftTableMap::COL_AIRCRAFT_MODEL_ID, AircraftTableMap::COL_AIRLINE_ID, AircraftTableMap::COL_AIRPORT_ID, AircraftTableMap::COL_PILOT_ID, AircraftTableMap::COL_CALLSIGN, AircraftTableMap::COL_FLOWN_DISTANCE, AircraftTableMap::COL_NUMBER_FLIGHTS, AircraftTableMap::COL_FLOWN_TIME, AircraftTableMap::COL_STATUS, AircraftTableMap::COL_LATITUDE, AircraftTableMap::COL_LONGITUDE, ),
        self::TYPE_FIELDNAME     => array('id', 'aircraft_model_id', 'airline_id', 'airport_id', 'pilot_id', 'callsign', 'flown_distance', 'number_flights', 'flown_time', 'status', 'latitude', 'longitude', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AircraftModelId' => 1, 'AirlineId' => 2, 'AirportId' => 3, 'PilotId' => 4, 'Callsign' => 5, 'FlownDistance' => 6, 'NumberFlights' => 7, 'FlownTime' => 8, 'Status' => 9, 'Latitude' => 10, 'Longitude' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'aircraftModelId' => 1, 'airlineId' => 2, 'airportId' => 3, 'pilotId' => 4, 'callsign' => 5, 'flownDistance' => 6, 'numberFlights' => 7, 'flownTime' => 8, 'status' => 9, 'latitude' => 10, 'longitude' => 11, ),
        self::TYPE_COLNAME       => array(AircraftTableMap::COL_ID => 0, AircraftTableMap::COL_AIRCRAFT_MODEL_ID => 1, AircraftTableMap::COL_AIRLINE_ID => 2, AircraftTableMap::COL_AIRPORT_ID => 3, AircraftTableMap::COL_PILOT_ID => 4, AircraftTableMap::COL_CALLSIGN => 5, AircraftTableMap::COL_FLOWN_DISTANCE => 6, AircraftTableMap::COL_NUMBER_FLIGHTS => 7, AircraftTableMap::COL_FLOWN_TIME => 8, AircraftTableMap::COL_STATUS => 9, AircraftTableMap::COL_LATITUDE => 10, AircraftTableMap::COL_LONGITUDE => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'aircraft_model_id' => 1, 'airline_id' => 2, 'airport_id' => 3, 'pilot_id' => 4, 'callsign' => 5, 'flown_distance' => 6, 'number_flights' => 7, 'flown_time' => 8, 'status' => 9, 'latitude' => 10, 'longitude' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                AircraftTableMap::COL_STATUS => array(
                            self::COL_STATUS_IDLE,
            self::COL_STATUS_LOADING,
            self::COL_STATUS_EN_ROUTE,
            self::COL_STATUS_UNLOADING,
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
        $this->setName('aircrafts');
        $this->setPhpName('Aircraft');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Aircraft');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('aircraft_model_id', 'AircraftModelId', 'INTEGER' , 'aircraft_models', 'id', true, null, null);
        $this->addForeignPrimaryKey('airline_id', 'AirlineId', 'INTEGER' , 'airlines', 'id', true, null, null);
        $this->addForeignKey('airport_id', 'AirportId', 'INTEGER', 'airports', 'id', false, null, null);
        $this->addForeignPrimaryKey('pilot_id', 'PilotId', 'INTEGER' , 'pilots', 'id', true, null, null);
        $this->addColumn('callsign', 'Callsign', 'VARCHAR', true, 7, null);
        $this->addColumn('flown_distance', 'FlownDistance', 'INTEGER', true, null, 0);
        $this->addColumn('number_flights', 'NumberFlights', 'SMALLINT', true, null, 0);
        $this->addColumn('flown_time', 'FlownTime', 'INTEGER', true, null, 0);
        $this->addColumn('status', 'Status', 'ENUM', true, null, 'IDLE');
        $this->getColumn('status')->setValueSet(array (
  0 => 'IDLE',
  1 => 'LOADING',
  2 => 'EN_ROUTE',
  3 => 'UNLOADING',
));
        $this->addColumn('latitude', 'Latitude', 'DOUBLE', false, 10, null);
        $this->addColumn('longitude', 'Longitude', 'DOUBLE', false, 10, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('AircraftModel', '\\Model\\AircraftModel', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':aircraft_model_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Airport', '\\Model\\Airport', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':airport_id',
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
        $this->addRelation('Pilot', '\\Model\\Pilot', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':pilot_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Flight', '\\Model\\Flight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':aircraft_id',
    1 => ':id',
  ),
), null, null, 'Flights', false);
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
            'geocodable' => array('auto_update' => 'false', 'latitude_column' => 'latitude', 'longitude_column' => 'longitude', 'type' => 'DOUBLE', 'size' => '10', 'scale' => '8', 'geocode_ip' => 'false', 'ip_column' => 'ip_address', 'geocode_address' => 'false', 'address_columns' => 'street,locality,region,postal_code,country', 'geocoder_provider' => '\Geocoder\Provider\OpenStreetMapProvider', 'geocoder_adapter' => '\Geocoder\HttpAdapter\CurlHttpAdapter', 'geocoder_api_key' => 'false', 'geocoder_api_key_provider' => 'false', ),
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
     * @param \Model\Aircraft $obj A \Model\Aircraft object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getId(), (string) $obj->getAircraftModelId(), (string) $obj->getAirlineId(), (string) $obj->getPilotId()));
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
     * @param mixed $value A \Model\Aircraft object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Model\Aircraft) {
                $key = serialize(array((string) $value->getId(), (string) $value->getAircraftModelId(), (string) $value->getAirlineId(), (string) $value->getPilotId()));

            } elseif (is_array($value) && count($value) === 4) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1], (string) $value[2], (string) $value[3]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Model\Aircraft object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)]));
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
                : self::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 4 + $offset
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
        return $withPrefix ? AircraftTableMap::CLASS_DEFAULT : AircraftTableMap::OM_CLASS;
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
     * @return array           (Aircraft object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AircraftTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AircraftTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AircraftTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AircraftTableMap::OM_CLASS;
            /** @var Aircraft $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AircraftTableMap::addInstanceToPool($obj, $key);
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
            $key = AircraftTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AircraftTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Aircraft $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AircraftTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AircraftTableMap::COL_ID);
            $criteria->addSelectColumn(AircraftTableMap::COL_AIRCRAFT_MODEL_ID);
            $criteria->addSelectColumn(AircraftTableMap::COL_AIRLINE_ID);
            $criteria->addSelectColumn(AircraftTableMap::COL_AIRPORT_ID);
            $criteria->addSelectColumn(AircraftTableMap::COL_PILOT_ID);
            $criteria->addSelectColumn(AircraftTableMap::COL_CALLSIGN);
            $criteria->addSelectColumn(AircraftTableMap::COL_FLOWN_DISTANCE);
            $criteria->addSelectColumn(AircraftTableMap::COL_NUMBER_FLIGHTS);
            $criteria->addSelectColumn(AircraftTableMap::COL_FLOWN_TIME);
            $criteria->addSelectColumn(AircraftTableMap::COL_STATUS);
            $criteria->addSelectColumn(AircraftTableMap::COL_LATITUDE);
            $criteria->addSelectColumn(AircraftTableMap::COL_LONGITUDE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.aircraft_model_id');
            $criteria->addSelectColumn($alias . '.airline_id');
            $criteria->addSelectColumn($alias . '.airport_id');
            $criteria->addSelectColumn($alias . '.pilot_id');
            $criteria->addSelectColumn($alias . '.callsign');
            $criteria->addSelectColumn($alias . '.flown_distance');
            $criteria->addSelectColumn($alias . '.number_flights');
            $criteria->addSelectColumn($alias . '.flown_time');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.latitude');
            $criteria->addSelectColumn($alias . '.longitude');
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
        return Propel::getServiceContainer()->getDatabaseMap(AircraftTableMap::DATABASE_NAME)->getTable(AircraftTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AircraftTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AircraftTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AircraftTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Aircraft or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Aircraft object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Aircraft) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AircraftTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(AircraftTableMap::COL_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(AircraftTableMap::COL_AIRLINE_ID, $value[2]));
                $criterion->addAnd($criteria->getNewCriterion(AircraftTableMap::COL_PILOT_ID, $value[3]));
                $criteria->addOr($criterion);
            }
        }

        $query = AircraftQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AircraftTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AircraftTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the aircrafts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AircraftQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Aircraft or Criteria object.
     *
     * @param mixed               $criteria Criteria or Aircraft object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Aircraft object
        }

        if ($criteria->containsKey(AircraftTableMap::COL_ID) && $criteria->keyContainsValue(AircraftTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AircraftTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AircraftQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AircraftTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AircraftTableMap::buildTableMap();

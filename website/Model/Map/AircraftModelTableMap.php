<?php

namespace Model\Map;

use Model\AircraftModel;
use Model\AircraftModelQuery;
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
 * This class defines the structure of the 'aircraft_models' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AircraftModelTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.AircraftModelTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'aircraft_models';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\AircraftModel';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.AircraftModel';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 18;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 18;

    /**
     * the column name for the id field
     */
    const COL_ID = 'aircraft_models.id';

    /**
     * the column name for the model field
     */
    const COL_MODEL = 'aircraft_models.model';

    /**
     * the column name for the brand field
     */
    const COL_BRAND = 'aircraft_models.brand';

    /**
     * the column name for the packages field
     */
    const COL_PACKAGES = 'aircraft_models.packages';

    /**
     * the column name for the post field
     */
    const COL_POST = 'aircraft_models.post';

    /**
     * the column name for the passenger_low field
     */
    const COL_PASSENGER_LOW = 'aircraft_models.passenger_low';

    /**
     * the column name for the passenger_mid field
     */
    const COL_PASSENGER_MID = 'aircraft_models.passenger_mid';

    /**
     * the column name for the passenger_high field
     */
    const COL_PASSENGER_HIGH = 'aircraft_models.passenger_high';

    /**
     * the column name for the seats field
     */
    const COL_SEATS = 'aircraft_models.seats';

    /**
     * the column name for the classes field
     */
    const COL_CLASSES = 'aircraft_models.classes';

    /**
     * the column name for the icao field
     */
    const COL_ICAO = 'aircraft_models.icao';

    /**
     * the column name for the wtc field
     */
    const COL_WTC = 'aircraft_models.wtc';

    /**
     * the column name for the engine_type field
     */
    const COL_ENGINE_TYPE = 'aircraft_models.engine_type';

    /**
     * the column name for the engine_count field
     */
    const COL_ENGINE_COUNT = 'aircraft_models.engine_count';

    /**
     * the column name for the flight_range field
     */
    const COL_FLIGHT_RANGE = 'aircraft_models.flight_range';

    /**
     * the column name for the cruising_speed field
     */
    const COL_CRUISING_SPEED = 'aircraft_models.cruising_speed';

    /**
     * the column name for the weight field
     */
    const COL_WEIGHT = 'aircraft_models.weight';

    /**
     * the column name for the value field
     */
    const COL_VALUE = 'aircraft_models.value';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the wtc field */
    const COL_WTC_L = 'L';
    const COL_WTC_M = 'M';
    const COL_WTC_H = 'H';
    const COL_WTC_S = 'S';

    /** The enumerated values for the engine_type field */
    const COL_ENGINE_TYPE_JET = 'JET';
    const COL_ENGINE_TYPE_TURBOPROP = 'TURBOPROP';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Model', 'Brand', 'Packages', 'Post', 'PassengerLow', 'PassengerMid', 'PassengerHigh', 'Seats', 'Classes', 'ICAO', 'WTC', 'EngineType', 'EngineCount', 'FlightRange', 'CruisingSpeed', 'Weight', 'Value', ),
        self::TYPE_CAMELNAME     => array('id', 'model', 'brand', 'packages', 'post', 'passengerLow', 'passengerMid', 'passengerHigh', 'seats', 'classes', 'iCAO', 'wTC', 'engineType', 'engineCount', 'flightRange', 'cruisingSpeed', 'weight', 'value', ),
        self::TYPE_COLNAME       => array(AircraftModelTableMap::COL_ID, AircraftModelTableMap::COL_MODEL, AircraftModelTableMap::COL_BRAND, AircraftModelTableMap::COL_PACKAGES, AircraftModelTableMap::COL_POST, AircraftModelTableMap::COL_PASSENGER_LOW, AircraftModelTableMap::COL_PASSENGER_MID, AircraftModelTableMap::COL_PASSENGER_HIGH, AircraftModelTableMap::COL_SEATS, AircraftModelTableMap::COL_CLASSES, AircraftModelTableMap::COL_ICAO, AircraftModelTableMap::COL_WTC, AircraftModelTableMap::COL_ENGINE_TYPE, AircraftModelTableMap::COL_ENGINE_COUNT, AircraftModelTableMap::COL_FLIGHT_RANGE, AircraftModelTableMap::COL_CRUISING_SPEED, AircraftModelTableMap::COL_WEIGHT, AircraftModelTableMap::COL_VALUE, ),
        self::TYPE_FIELDNAME     => array('id', 'model', 'brand', 'packages', 'post', 'passenger_low', 'passenger_mid', 'passenger_high', 'seats', 'classes', 'icao', 'wtc', 'engine_type', 'engine_count', 'flight_range', 'cruising_speed', 'weight', 'value', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Model' => 1, 'Brand' => 2, 'Packages' => 3, 'Post' => 4, 'PassengerLow' => 5, 'PassengerMid' => 6, 'PassengerHigh' => 7, 'Seats' => 8, 'Classes' => 9, 'ICAO' => 10, 'WTC' => 11, 'EngineType' => 12, 'EngineCount' => 13, 'FlightRange' => 14, 'CruisingSpeed' => 15, 'Weight' => 16, 'Value' => 17, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'model' => 1, 'brand' => 2, 'packages' => 3, 'post' => 4, 'passengerLow' => 5, 'passengerMid' => 6, 'passengerHigh' => 7, 'seats' => 8, 'classes' => 9, 'iCAO' => 10, 'wTC' => 11, 'engineType' => 12, 'engineCount' => 13, 'flightRange' => 14, 'cruisingSpeed' => 15, 'weight' => 16, 'value' => 17, ),
        self::TYPE_COLNAME       => array(AircraftModelTableMap::COL_ID => 0, AircraftModelTableMap::COL_MODEL => 1, AircraftModelTableMap::COL_BRAND => 2, AircraftModelTableMap::COL_PACKAGES => 3, AircraftModelTableMap::COL_POST => 4, AircraftModelTableMap::COL_PASSENGER_LOW => 5, AircraftModelTableMap::COL_PASSENGER_MID => 6, AircraftModelTableMap::COL_PASSENGER_HIGH => 7, AircraftModelTableMap::COL_SEATS => 8, AircraftModelTableMap::COL_CLASSES => 9, AircraftModelTableMap::COL_ICAO => 10, AircraftModelTableMap::COL_WTC => 11, AircraftModelTableMap::COL_ENGINE_TYPE => 12, AircraftModelTableMap::COL_ENGINE_COUNT => 13, AircraftModelTableMap::COL_FLIGHT_RANGE => 14, AircraftModelTableMap::COL_CRUISING_SPEED => 15, AircraftModelTableMap::COL_WEIGHT => 16, AircraftModelTableMap::COL_VALUE => 17, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'model' => 1, 'brand' => 2, 'packages' => 3, 'post' => 4, 'passenger_low' => 5, 'passenger_mid' => 6, 'passenger_high' => 7, 'seats' => 8, 'classes' => 9, 'icao' => 10, 'wtc' => 11, 'engine_type' => 12, 'engine_count' => 13, 'flight_range' => 14, 'cruising_speed' => 15, 'weight' => 16, 'value' => 17, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                AircraftModelTableMap::COL_WTC => array(
                            self::COL_WTC_L,
            self::COL_WTC_M,
            self::COL_WTC_H,
            self::COL_WTC_S,
        ),
                AircraftModelTableMap::COL_ENGINE_TYPE => array(
                            self::COL_ENGINE_TYPE_JET,
            self::COL_ENGINE_TYPE_TURBOPROP,
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
        $this->setName('aircraft_models');
        $this->setPhpName('AircraftModel');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\AircraftModel');
        $this->setPackage('Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('model', 'Model', 'VARCHAR', true, 12, null);
        $this->addColumn('brand', 'Brand', 'VARCHAR', true, 12, null);
        $this->addColumn('packages', 'Packages', 'SMALLINT', true, null, 0);
        $this->addColumn('post', 'Post', 'SMALLINT', true, null, 0);
        $this->addColumn('passenger_low', 'PassengerLow', 'SMALLINT', true, null, 0);
        $this->addColumn('passenger_mid', 'PassengerMid', 'SMALLINT', true, null, 0);
        $this->addColumn('passenger_high', 'PassengerHigh', 'SMALLINT', true, null, 0);
        $this->addColumn('seats', 'Seats', 'SMALLINT', true, null, 3);
        $this->addColumn('classes', 'Classes', 'TINYINT', true, null, 1);
        $this->addColumn('icao', 'ICAO', 'VARCHAR', true, 4, null);
        $this->addColumn('wtc', 'WTC', 'ENUM', true, null, 'M');
        $this->getColumn('wtc')->setValueSet(array (
  0 => 'L',
  1 => 'M',
  2 => 'H',
  3 => 'S',
));
        $this->addColumn('engine_type', 'EngineType', 'ENUM', true, null, 'JET');
        $this->getColumn('engine_type')->setValueSet(array (
  0 => 'JET',
  1 => 'TURBOPROP',
));
        $this->addColumn('engine_count', 'EngineCount', 'TINYINT', true, null, 1);
        $this->addColumn('flight_range', 'FlightRange', 'SMALLINT', true, null, 3000);
        $this->addColumn('cruising_speed', 'CruisingSpeed', 'SMALLINT', true, null, 120);
        $this->addColumn('weight', 'Weight', 'INTEGER', true, null, null);
        $this->addColumn('value', 'Value', 'INTEGER', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Aircraft', '\\Model\\Aircraft', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':aircraft_model_id',
    1 => ':id',
  ),
), null, null, 'Aircrafts', false);
        $this->addRelation('Flight', '\\Model\\Flight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':aircraft_model_id',
    1 => ':id',
  ),
), null, null, 'Flights', false);
    } // buildRelations()

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
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
        return $withPrefix ? AircraftModelTableMap::CLASS_DEFAULT : AircraftModelTableMap::OM_CLASS;
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
     * @return array           (AircraftModel object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AircraftModelTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AircraftModelTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AircraftModelTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AircraftModelTableMap::OM_CLASS;
            /** @var AircraftModel $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AircraftModelTableMap::addInstanceToPool($obj, $key);
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
            $key = AircraftModelTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AircraftModelTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AircraftModel $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AircraftModelTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AircraftModelTableMap::COL_ID);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_MODEL);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_BRAND);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_PACKAGES);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_POST);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_PASSENGER_LOW);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_PASSENGER_MID);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_PASSENGER_HIGH);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_SEATS);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_CLASSES);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_ICAO);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_WTC);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_ENGINE_TYPE);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_ENGINE_COUNT);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_FLIGHT_RANGE);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_CRUISING_SPEED);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_WEIGHT);
            $criteria->addSelectColumn(AircraftModelTableMap::COL_VALUE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.model');
            $criteria->addSelectColumn($alias . '.brand');
            $criteria->addSelectColumn($alias . '.packages');
            $criteria->addSelectColumn($alias . '.post');
            $criteria->addSelectColumn($alias . '.passenger_low');
            $criteria->addSelectColumn($alias . '.passenger_mid');
            $criteria->addSelectColumn($alias . '.passenger_high');
            $criteria->addSelectColumn($alias . '.seats');
            $criteria->addSelectColumn($alias . '.classes');
            $criteria->addSelectColumn($alias . '.icao');
            $criteria->addSelectColumn($alias . '.wtc');
            $criteria->addSelectColumn($alias . '.engine_type');
            $criteria->addSelectColumn($alias . '.engine_count');
            $criteria->addSelectColumn($alias . '.flight_range');
            $criteria->addSelectColumn($alias . '.cruising_speed');
            $criteria->addSelectColumn($alias . '.weight');
            $criteria->addSelectColumn($alias . '.value');
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
        return Propel::getServiceContainer()->getDatabaseMap(AircraftModelTableMap::DATABASE_NAME)->getTable(AircraftModelTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AircraftModelTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AircraftModelTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AircraftModelTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a AircraftModel or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AircraftModel object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftModelTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\AircraftModel) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AircraftModelTableMap::DATABASE_NAME);
            $criteria->add(AircraftModelTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AircraftModelQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AircraftModelTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AircraftModelTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the aircraft_models table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AircraftModelQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AircraftModel or Criteria object.
     *
     * @param mixed               $criteria Criteria or AircraftModel object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftModelTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AircraftModel object
        }


        // Set the correct dbName
        $query = AircraftModelQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AircraftModelTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AircraftModelTableMap::buildTableMap();

<?php

namespace Model\Map;

use Model\Airline;
use Model\AirlineQuery;
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
 * This class defines the structure of the 'airlines' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AirlineTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.AirlineTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'airlines';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Airline';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Airline';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'airlines.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'airlines.name';

    /**
     * the column name for the alias field
     */
    const COL_ALIAS = 'airlines.alias';

    /**
     * the column name for the iata field
     */
    const COL_IATA = 'airlines.iata';

    /**
     * the column name for the icao field
     */
    const COL_ICAO = 'airlines.icao';

    /**
     * the column name for the callsign field
     */
    const COL_CALLSIGN = 'airlines.callsign';

    /**
     * the column name for the country field
     */
    const COL_COUNTRY = 'airlines.country';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'airlines.active';

    /**
     * the column name for the saldo field
     */
    const COL_SALDO = 'airlines.saldo';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Alias', 'IATA', 'ICAO', 'Callsign', 'Country', 'Active', 'Saldo', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'alias', 'iATA', 'iCAO', 'callsign', 'country', 'active', 'saldo', ),
        self::TYPE_COLNAME       => array(AirlineTableMap::COL_ID, AirlineTableMap::COL_NAME, AirlineTableMap::COL_ALIAS, AirlineTableMap::COL_IATA, AirlineTableMap::COL_ICAO, AirlineTableMap::COL_CALLSIGN, AirlineTableMap::COL_COUNTRY, AirlineTableMap::COL_ACTIVE, AirlineTableMap::COL_SALDO, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'alias', 'iata', 'icao', 'callsign', 'country', 'active', 'saldo', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Alias' => 2, 'IATA' => 3, 'ICAO' => 4, 'Callsign' => 5, 'Country' => 6, 'Active' => 7, 'Saldo' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'alias' => 2, 'iATA' => 3, 'iCAO' => 4, 'callsign' => 5, 'country' => 6, 'active' => 7, 'saldo' => 8, ),
        self::TYPE_COLNAME       => array(AirlineTableMap::COL_ID => 0, AirlineTableMap::COL_NAME => 1, AirlineTableMap::COL_ALIAS => 2, AirlineTableMap::COL_IATA => 3, AirlineTableMap::COL_ICAO => 4, AirlineTableMap::COL_CALLSIGN => 5, AirlineTableMap::COL_COUNTRY => 6, AirlineTableMap::COL_ACTIVE => 7, AirlineTableMap::COL_SALDO => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'alias' => 2, 'iata' => 3, 'icao' => 4, 'callsign' => 5, 'country' => 6, 'active' => 7, 'saldo' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

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
        $this->setName('airlines');
        $this->setPhpName('Airline');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Airline');
        $this->setPackage('Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'CHAR', true, 255, null);
        $this->addColumn('alias', 'Alias', 'CHAR', true, 255, null);
        $this->addColumn('iata', 'IATA', 'CHAR', true, 3, null);
        $this->addColumn('icao', 'ICAO', 'CHAR', true, 3, null);
        $this->addColumn('callsign', 'Callsign', 'CHAR', true, 255, null);
        $this->addColumn('country', 'Country', 'CHAR', true, 255, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', true, 1, false);
        $this->addColumn('saldo', 'Saldo', 'INTEGER', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Aircraft', '\\Model\\Aircraft', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':airline_id',
    1 => ':id',
  ),
), null, null, 'Aircrafts', false);
        $this->addRelation('Flight', '\\Model\\Flight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':airline_id',
    1 => ':id',
  ),
), null, null, 'Flights', false);
        $this->addRelation('Pilot', '\\Model\\Pilot', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':airline_id',
    1 => ':id',
  ),
), null, null, 'Pilots', false);
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
        return $withPrefix ? AirlineTableMap::CLASS_DEFAULT : AirlineTableMap::OM_CLASS;
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
     * @return array           (Airline object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AirlineTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AirlineTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AirlineTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AirlineTableMap::OM_CLASS;
            /** @var Airline $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AirlineTableMap::addInstanceToPool($obj, $key);
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
            $key = AirlineTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AirlineTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Airline $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AirlineTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AirlineTableMap::COL_ID);
            $criteria->addSelectColumn(AirlineTableMap::COL_NAME);
            $criteria->addSelectColumn(AirlineTableMap::COL_ALIAS);
            $criteria->addSelectColumn(AirlineTableMap::COL_IATA);
            $criteria->addSelectColumn(AirlineTableMap::COL_ICAO);
            $criteria->addSelectColumn(AirlineTableMap::COL_CALLSIGN);
            $criteria->addSelectColumn(AirlineTableMap::COL_COUNTRY);
            $criteria->addSelectColumn(AirlineTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(AirlineTableMap::COL_SALDO);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.alias');
            $criteria->addSelectColumn($alias . '.iata');
            $criteria->addSelectColumn($alias . '.icao');
            $criteria->addSelectColumn($alias . '.callsign');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.active');
            $criteria->addSelectColumn($alias . '.saldo');
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
        return Propel::getServiceContainer()->getDatabaseMap(AirlineTableMap::DATABASE_NAME)->getTable(AirlineTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AirlineTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AirlineTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AirlineTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Airline or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Airline object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AirlineTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Airline) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AirlineTableMap::DATABASE_NAME);
            $criteria->add(AirlineTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AirlineQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AirlineTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AirlineTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the airlines table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AirlineQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Airline or Criteria object.
     *
     * @param mixed               $criteria Criteria or Airline object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirlineTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Airline object
        }


        // Set the correct dbName
        $query = AirlineQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AirlineTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AirlineTableMap::buildTableMap();

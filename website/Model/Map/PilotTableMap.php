<?php

namespace Model\Map;

use Model\Pilot;
use Model\PilotQuery;
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
 * This class defines the structure of the 'pilots' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PilotTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.PilotTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'pilots';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Pilot';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Pilot';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'pilots.id';

    /**
     * the column name for the airline_id field
     */
    const COL_AIRLINE_ID = 'pilots.airline_id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'pilots.name';

    /**
     * the column name for the token field
     */
    const COL_TOKEN = 'pilots.token';

    /**
     * the column name for the rank field
     */
    const COL_RANK = 'pilots.rank';

    /**
     * the column name for the saldo field
     */
    const COL_SALDO = 'pilots.saldo';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the rank field */
    const COL_RANK_GUEST = 'GUEST';
    const COL_RANK_PILOT = 'PILOT';
    const COL_RANK_ADMIN = 'ADMIN';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'AirlineId', 'Name', 'Token', 'Rank', 'Saldo', ),
        self::TYPE_CAMELNAME     => array('id', 'airlineId', 'name', 'token', 'rank', 'saldo', ),
        self::TYPE_COLNAME       => array(PilotTableMap::COL_ID, PilotTableMap::COL_AIRLINE_ID, PilotTableMap::COL_NAME, PilotTableMap::COL_TOKEN, PilotTableMap::COL_RANK, PilotTableMap::COL_SALDO, ),
        self::TYPE_FIELDNAME     => array('id', 'airline_id', 'name', 'token', 'rank', 'saldo', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AirlineId' => 1, 'Name' => 2, 'Token' => 3, 'Rank' => 4, 'Saldo' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'airlineId' => 1, 'name' => 2, 'token' => 3, 'rank' => 4, 'saldo' => 5, ),
        self::TYPE_COLNAME       => array(PilotTableMap::COL_ID => 0, PilotTableMap::COL_AIRLINE_ID => 1, PilotTableMap::COL_NAME => 2, PilotTableMap::COL_TOKEN => 3, PilotTableMap::COL_RANK => 4, PilotTableMap::COL_SALDO => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'airline_id' => 1, 'name' => 2, 'token' => 3, 'rank' => 4, 'saldo' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                PilotTableMap::COL_RANK => array(
                            self::COL_RANK_GUEST,
            self::COL_RANK_PILOT,
            self::COL_RANK_ADMIN,
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
        $this->setName('pilots');
        $this->setPhpName('Pilot');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Pilot');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('airline_id', 'AirlineId', 'INTEGER', 'airlines', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('token', 'Token', 'VARCHAR', false, 255, null);
        $this->addColumn('rank', 'Rank', 'ENUM', true, null, 'PILOT');
        $this->getColumn('rank')->setValueSet(array (
  0 => 'GUEST',
  1 => 'PILOT',
  2 => 'ADMIN',
));
        $this->addColumn('saldo', 'Saldo', 'INTEGER', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Airline', '\\Model\\Airline', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':airline_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Aircraft', '\\Model\\Aircraft', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':pilot_id',
    1 => ':id',
  ),
), null, null, 'Aircrafts', false);
        $this->addRelation('Flight', '\\Model\\Flight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':pilot_id',
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
        return $withPrefix ? PilotTableMap::CLASS_DEFAULT : PilotTableMap::OM_CLASS;
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
     * @return array           (Pilot object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PilotTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PilotTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PilotTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PilotTableMap::OM_CLASS;
            /** @var Pilot $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PilotTableMap::addInstanceToPool($obj, $key);
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
            $key = PilotTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PilotTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Pilot $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PilotTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PilotTableMap::COL_ID);
            $criteria->addSelectColumn(PilotTableMap::COL_AIRLINE_ID);
            $criteria->addSelectColumn(PilotTableMap::COL_NAME);
            $criteria->addSelectColumn(PilotTableMap::COL_TOKEN);
            $criteria->addSelectColumn(PilotTableMap::COL_RANK);
            $criteria->addSelectColumn(PilotTableMap::COL_SALDO);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.airline_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.token');
            $criteria->addSelectColumn($alias . '.rank');
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
        return Propel::getServiceContainer()->getDatabaseMap(PilotTableMap::DATABASE_NAME)->getTable(PilotTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PilotTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PilotTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PilotTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Pilot or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Pilot object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PilotTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Pilot) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PilotTableMap::DATABASE_NAME);
            $criteria->add(PilotTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PilotQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PilotTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PilotTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the pilots table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PilotQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Pilot or Criteria object.
     *
     * @param mixed               $criteria Criteria or Pilot object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PilotTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Pilot object
        }

        if ($criteria->containsKey(PilotTableMap::COL_ID) && $criteria->keyContainsValue(PilotTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PilotTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PilotQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PilotTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PilotTableMap::buildTableMap();

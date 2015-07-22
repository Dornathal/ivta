<?php

namespace Model\Map;

use Model\Airport;
use Model\AirportQuery;
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
 * This class defines the structure of the 'airports' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AirportTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.AirportTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'airports';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Airport';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Airport';

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
    const COL_ID = 'airports.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'airports.name';

    /**
     * the column name for the city field
     */
    const COL_CITY = 'airports.city';

    /**
     * the column name for the country field
     */
    const COL_COUNTRY = 'airports.country';

    /**
     * the column name for the iata field
     */
    const COL_IATA = 'airports.iata';

    /**
     * the column name for the icao field
     */
    const COL_ICAO = 'airports.icao';

    /**
     * the column name for the altitude field
     */
    const COL_ALTITUDE = 'airports.altitude';

    /**
     * the column name for the timezone field
     */
    const COL_TIMEZONE = 'airports.timezone';

    /**
     * the column name for the dst field
     */
    const COL_DST = 'airports.dst';

    /**
     * the column name for the size field
     */
    const COL_SIZE = 'airports.size';

    /**
     * the column name for the latitude field
     */
    const COL_LATITUDE = 'airports.latitude';

    /**
     * the column name for the longitude field
     */
    const COL_LONGITUDE = 'airports.longitude';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the dst field */
    const COL_DST_E = 'E';
    const COL_DST_A = 'A';
    const COL_DST_S = 'S';
    const COL_DST_O = 'O';
    const COL_DST_Z = 'Z';
    const COL_DST_N = 'N';
    const COL_DST_U = 'U';

    /** The enumerated values for the size field */
    const COL_SIZE_INFO = 'INFO';
    const COL_SIZE_REGIONAL = 'REGIONAL';
    const COL_SIZE_INTERNATIONAL = 'INTERNATIONAL';
    const COL_SIZE_INTERKONTINENTAL = 'INTERKONTINENTAL';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'City', 'Country', 'IATA', 'ICAO', 'Altitude', 'Timezone', 'Dst', 'Size', 'Latitude', 'Longitude', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'city', 'country', 'iATA', 'iCAO', 'altitude', 'timezone', 'dst', 'size', 'latitude', 'longitude', ),
        self::TYPE_COLNAME       => array(AirportTableMap::COL_ID, AirportTableMap::COL_NAME, AirportTableMap::COL_CITY, AirportTableMap::COL_COUNTRY, AirportTableMap::COL_IATA, AirportTableMap::COL_ICAO, AirportTableMap::COL_ALTITUDE, AirportTableMap::COL_TIMEZONE, AirportTableMap::COL_DST, AirportTableMap::COL_SIZE, AirportTableMap::COL_LATITUDE, AirportTableMap::COL_LONGITUDE, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'city', 'country', 'iata', 'icao', 'altitude', 'timezone', 'dst', 'size', 'latitude', 'longitude', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'City' => 2, 'Country' => 3, 'IATA' => 4, 'ICAO' => 5, 'Altitude' => 6, 'Timezone' => 7, 'Dst' => 8, 'Size' => 9, 'Latitude' => 10, 'Longitude' => 11, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'city' => 2, 'country' => 3, 'iATA' => 4, 'iCAO' => 5, 'altitude' => 6, 'timezone' => 7, 'dst' => 8, 'size' => 9, 'latitude' => 10, 'longitude' => 11, ),
        self::TYPE_COLNAME       => array(AirportTableMap::COL_ID => 0, AirportTableMap::COL_NAME => 1, AirportTableMap::COL_CITY => 2, AirportTableMap::COL_COUNTRY => 3, AirportTableMap::COL_IATA => 4, AirportTableMap::COL_ICAO => 5, AirportTableMap::COL_ALTITUDE => 6, AirportTableMap::COL_TIMEZONE => 7, AirportTableMap::COL_DST => 8, AirportTableMap::COL_SIZE => 9, AirportTableMap::COL_LATITUDE => 10, AirportTableMap::COL_LONGITUDE => 11, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'city' => 2, 'country' => 3, 'iata' => 4, 'icao' => 5, 'altitude' => 6, 'timezone' => 7, 'dst' => 8, 'size' => 9, 'latitude' => 10, 'longitude' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                AirportTableMap::COL_DST => array(
                            self::COL_DST_E,
            self::COL_DST_A,
            self::COL_DST_S,
            self::COL_DST_O,
            self::COL_DST_Z,
            self::COL_DST_N,
            self::COL_DST_U,
        ),
                AirportTableMap::COL_SIZE => array(
                            self::COL_SIZE_INFO,
            self::COL_SIZE_REGIONAL,
            self::COL_SIZE_INTERNATIONAL,
            self::COL_SIZE_INTERKONTINENTAL,
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
        $this->setName('airports');
        $this->setPhpName('Airport');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Airport');
        $this->setPackage('Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 128, null);
        $this->addColumn('city', 'City', 'VARCHAR', true, 128, null);
        $this->addColumn('country', 'Country', 'VARCHAR', true, 128, null);
        $this->addColumn('iata', 'IATA', 'CHAR', true, 3, null);
        $this->addColumn('icao', 'ICAO', 'CHAR', true, 4, null);
        $this->addColumn('altitude', 'Altitude', 'FLOAT', true, null, 0);
        $this->addColumn('timezone', 'Timezone', 'FLOAT', true, null, 0);
        $this->addColumn('dst', 'Dst', 'ENUM', true, null, 'U');
        $this->getColumn('dst')->setValueSet(array (
  0 => 'E',
  1 => 'A',
  2 => 'S',
  3 => 'O',
  4 => 'Z',
  5 => 'N',
  6 => 'U',
));
        $this->addColumn('size', 'Size', 'ENUM', true, null, 'INFO');
        $this->getColumn('size')->setValueSet(array (
  0 => 'INFO',
  1 => 'REGIONAL',
  2 => 'INTERNATIONAL',
  3 => 'INTERKONTINENTAL',
));
        $this->addColumn('latitude', 'Latitude', 'DOUBLE', false, 10, null);
        $this->addColumn('longitude', 'Longitude', 'DOUBLE', false, 10, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Aircraft', '\\Model\\Aircraft', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':airport_id',
    1 => ':id',
  ),
), null, null, 'Aircrafts', false);
        $this->addRelation('FlightRelatedByDestinationId', '\\Model\\Flight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':destination_id',
    1 => ':id',
  ),
), null, null, 'FlightsRelatedByDestinationId', false);
        $this->addRelation('FlightRelatedByDepartureId', '\\Model\\Flight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':departure_id',
    1 => ':id',
  ),
), null, null, 'FlightsRelatedByDepartureId', false);
        $this->addRelation('AirwayRelatedByDestinationId', '\\Model\\Airway', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':destination_id',
    1 => ':id',
  ),
), null, null, 'AirwaysRelatedByDestinationId', false);
        $this->addRelation('AirwayRelatedByDepartureId', '\\Model\\Airway', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':departure_id',
    1 => ':id',
  ),
), null, null, 'AirwaysRelatedByDepartureId', false);
        $this->addRelation('FreightRelatedByDestinationId', '\\Model\\Freight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':destination_id',
    1 => ':id',
  ),
), null, null, 'FreightsRelatedByDestinationId', false);
        $this->addRelation('FreightRelatedByDepartureId', '\\Model\\Freight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':departure_id',
    1 => ':id',
  ),
), null, null, 'FreightsRelatedByDepartureId', false);
        $this->addRelation('FreightRelatedByLocationId', '\\Model\\Freight', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':location_id',
    1 => ':id',
  ),
), null, null, 'FreightsRelatedByLocationId', false);
        $this->addRelation('FreightGeneration', '\\Model\\FreightGeneration', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':airport_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Departure', '\\Model\\Airport', RelationMap::MANY_TO_MANY, array(), null, null, 'Departures');
        $this->addRelation('Destination', '\\Model\\Airport', RelationMap::MANY_TO_MANY, array(), null, null, 'Destinations');
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
        return $withPrefix ? AirportTableMap::CLASS_DEFAULT : AirportTableMap::OM_CLASS;
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
     * @return array           (Airport object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AirportTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AirportTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AirportTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AirportTableMap::OM_CLASS;
            /** @var Airport $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AirportTableMap::addInstanceToPool($obj, $key);
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
            $key = AirportTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AirportTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Airport $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AirportTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AirportTableMap::COL_ID);
            $criteria->addSelectColumn(AirportTableMap::COL_NAME);
            $criteria->addSelectColumn(AirportTableMap::COL_CITY);
            $criteria->addSelectColumn(AirportTableMap::COL_COUNTRY);
            $criteria->addSelectColumn(AirportTableMap::COL_IATA);
            $criteria->addSelectColumn(AirportTableMap::COL_ICAO);
            $criteria->addSelectColumn(AirportTableMap::COL_ALTITUDE);
            $criteria->addSelectColumn(AirportTableMap::COL_TIMEZONE);
            $criteria->addSelectColumn(AirportTableMap::COL_DST);
            $criteria->addSelectColumn(AirportTableMap::COL_SIZE);
            $criteria->addSelectColumn(AirportTableMap::COL_LATITUDE);
            $criteria->addSelectColumn(AirportTableMap::COL_LONGITUDE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.iata');
            $criteria->addSelectColumn($alias . '.icao');
            $criteria->addSelectColumn($alias . '.altitude');
            $criteria->addSelectColumn($alias . '.timezone');
            $criteria->addSelectColumn($alias . '.dst');
            $criteria->addSelectColumn($alias . '.size');
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
        return Propel::getServiceContainer()->getDatabaseMap(AirportTableMap::DATABASE_NAME)->getTable(AirportTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AirportTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AirportTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AirportTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Airport or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Airport object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AirportTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Airport) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AirportTableMap::DATABASE_NAME);
            $criteria->add(AirportTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AirportQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AirportTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AirportTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the airports table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AirportQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Airport or Criteria object.
     *
     * @param mixed               $criteria Criteria or Airport object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirportTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Airport object
        }


        // Set the correct dbName
        $query = AirportQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AirportTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AirportTableMap::buildTableMap();

<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Airport as ChildAirport;
use Model\AirportQuery as ChildAirportQuery;
use Model\Map\AirportTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'airports' table.
 *
 *
 *
 * @method     ChildAirportQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAirportQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildAirportQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildAirportQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method     ChildAirportQuery orderByIATA($order = Criteria::ASC) Order by the iata column
 * @method     ChildAirportQuery orderByICAO($order = Criteria::ASC) Order by the icao column
 * @method     ChildAirportQuery orderByAltitude($order = Criteria::ASC) Order by the altitude column
 * @method     ChildAirportQuery orderByTimezone($order = Criteria::ASC) Order by the timezone column
 * @method     ChildAirportQuery orderByDst($order = Criteria::ASC) Order by the dst column
 * @method     ChildAirportQuery orderBySize($order = Criteria::ASC) Order by the size column
 * @method     ChildAirportQuery orderByLatitude($order = Criteria::ASC) Order by the latitude column
 * @method     ChildAirportQuery orderByLongitude($order = Criteria::ASC) Order by the longitude column
 *
 * @method     ChildAirportQuery groupById() Group by the id column
 * @method     ChildAirportQuery groupByName() Group by the name column
 * @method     ChildAirportQuery groupByCity() Group by the city column
 * @method     ChildAirportQuery groupByCountry() Group by the country column
 * @method     ChildAirportQuery groupByIATA() Group by the iata column
 * @method     ChildAirportQuery groupByICAO() Group by the icao column
 * @method     ChildAirportQuery groupByAltitude() Group by the altitude column
 * @method     ChildAirportQuery groupByTimezone() Group by the timezone column
 * @method     ChildAirportQuery groupByDst() Group by the dst column
 * @method     ChildAirportQuery groupBySize() Group by the size column
 * @method     ChildAirportQuery groupByLatitude() Group by the latitude column
 * @method     ChildAirportQuery groupByLongitude() Group by the longitude column
 *
 * @method     ChildAirportQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAirportQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAirportQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAirportQuery leftJoinAircraft($relationAlias = null) Adds a LEFT JOIN clause to the query using the Aircraft relation
 * @method     ChildAirportQuery rightJoinAircraft($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Aircraft relation
 * @method     ChildAirportQuery innerJoinAircraft($relationAlias = null) Adds a INNER JOIN clause to the query using the Aircraft relation
 *
 * @method     ChildAirportQuery leftJoinFlightRelatedByDestinationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FlightRelatedByDestinationId relation
 * @method     ChildAirportQuery rightJoinFlightRelatedByDestinationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FlightRelatedByDestinationId relation
 * @method     ChildAirportQuery innerJoinFlightRelatedByDestinationId($relationAlias = null) Adds a INNER JOIN clause to the query using the FlightRelatedByDestinationId relation
 *
 * @method     ChildAirportQuery leftJoinFlightRelatedByDepartureId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FlightRelatedByDepartureId relation
 * @method     ChildAirportQuery rightJoinFlightRelatedByDepartureId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FlightRelatedByDepartureId relation
 * @method     ChildAirportQuery innerJoinFlightRelatedByDepartureId($relationAlias = null) Adds a INNER JOIN clause to the query using the FlightRelatedByDepartureId relation
 *
 * @method     ChildAirportQuery leftJoinAirwayRelatedByDestinationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AirwayRelatedByDestinationId relation
 * @method     ChildAirportQuery rightJoinAirwayRelatedByDestinationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AirwayRelatedByDestinationId relation
 * @method     ChildAirportQuery innerJoinAirwayRelatedByDestinationId($relationAlias = null) Adds a INNER JOIN clause to the query using the AirwayRelatedByDestinationId relation
 *
 * @method     ChildAirportQuery leftJoinAirwayRelatedByDepartureId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AirwayRelatedByDepartureId relation
 * @method     ChildAirportQuery rightJoinAirwayRelatedByDepartureId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AirwayRelatedByDepartureId relation
 * @method     ChildAirportQuery innerJoinAirwayRelatedByDepartureId($relationAlias = null) Adds a INNER JOIN clause to the query using the AirwayRelatedByDepartureId relation
 *
 * @method     ChildAirportQuery leftJoinFreightRelatedByDestinationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FreightRelatedByDestinationId relation
 * @method     ChildAirportQuery rightJoinFreightRelatedByDestinationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FreightRelatedByDestinationId relation
 * @method     ChildAirportQuery innerJoinFreightRelatedByDestinationId($relationAlias = null) Adds a INNER JOIN clause to the query using the FreightRelatedByDestinationId relation
 *
 * @method     ChildAirportQuery leftJoinFreightRelatedByDepartureId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FreightRelatedByDepartureId relation
 * @method     ChildAirportQuery rightJoinFreightRelatedByDepartureId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FreightRelatedByDepartureId relation
 * @method     ChildAirportQuery innerJoinFreightRelatedByDepartureId($relationAlias = null) Adds a INNER JOIN clause to the query using the FreightRelatedByDepartureId relation
 *
 * @method     ChildAirportQuery leftJoinFreightRelatedByLocationId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FreightRelatedByLocationId relation
 * @method     ChildAirportQuery rightJoinFreightRelatedByLocationId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FreightRelatedByLocationId relation
 * @method     ChildAirportQuery innerJoinFreightRelatedByLocationId($relationAlias = null) Adds a INNER JOIN clause to the query using the FreightRelatedByLocationId relation
 *
 * @method     ChildAirportQuery leftJoinFreightGeneration($relationAlias = null) Adds a LEFT JOIN clause to the query using the FreightGeneration relation
 * @method     ChildAirportQuery rightJoinFreightGeneration($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FreightGeneration relation
 * @method     ChildAirportQuery innerJoinFreightGeneration($relationAlias = null) Adds a INNER JOIN clause to the query using the FreightGeneration relation
 *
 * @method     \Model\AircraftQuery|\Model\FlightQuery|\Model\AirwayQuery|\Model\FreightQuery|\Model\FreightGenerationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAirport findOne(ConnectionInterface $con = null) Return the first ChildAirport matching the query
 * @method     ChildAirport findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAirport matching the query, or a new ChildAirport object populated from the query conditions when no match is found
 *
 * @method     ChildAirport findOneById(int $id) Return the first ChildAirport filtered by the id column
 * @method     ChildAirport findOneByName(string $name) Return the first ChildAirport filtered by the name column
 * @method     ChildAirport findOneByCity(string $city) Return the first ChildAirport filtered by the city column
 * @method     ChildAirport findOneByCountry(string $country) Return the first ChildAirport filtered by the country column
 * @method     ChildAirport findOneByIATA(string $iata) Return the first ChildAirport filtered by the iata column
 * @method     ChildAirport findOneByICAO(string $icao) Return the first ChildAirport filtered by the icao column
 * @method     ChildAirport findOneByAltitude(double $altitude) Return the first ChildAirport filtered by the altitude column
 * @method     ChildAirport findOneByTimezone(double $timezone) Return the first ChildAirport filtered by the timezone column
 * @method     ChildAirport findOneByDst(int $dst) Return the first ChildAirport filtered by the dst column
 * @method     ChildAirport findOneBySize(int $size) Return the first ChildAirport filtered by the size column
 * @method     ChildAirport findOneByLatitude(double $latitude) Return the first ChildAirport filtered by the latitude column
 * @method     ChildAirport findOneByLongitude(double $longitude) Return the first ChildAirport filtered by the longitude column *

 * @method     ChildAirport requirePk($key, ConnectionInterface $con = null) Return the ChildAirport by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOne(ConnectionInterface $con = null) Return the first ChildAirport matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAirport requireOneById(int $id) Return the first ChildAirport filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByName(string $name) Return the first ChildAirport filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByCity(string $city) Return the first ChildAirport filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByCountry(string $country) Return the first ChildAirport filtered by the country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByIATA(string $iata) Return the first ChildAirport filtered by the iata column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByICAO(string $icao) Return the first ChildAirport filtered by the icao column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByAltitude(double $altitude) Return the first ChildAirport filtered by the altitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByTimezone(double $timezone) Return the first ChildAirport filtered by the timezone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByDst(int $dst) Return the first ChildAirport filtered by the dst column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneBySize(int $size) Return the first ChildAirport filtered by the size column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByLatitude(double $latitude) Return the first ChildAirport filtered by the latitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirport requireOneByLongitude(double $longitude) Return the first ChildAirport filtered by the longitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAirport[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAirport objects based on current ModelCriteria
 * @method     ChildAirport[]|ObjectCollection findById(int $id) Return ChildAirport objects filtered by the id column
 * @method     ChildAirport[]|ObjectCollection findByName(string $name) Return ChildAirport objects filtered by the name column
 * @method     ChildAirport[]|ObjectCollection findByCity(string $city) Return ChildAirport objects filtered by the city column
 * @method     ChildAirport[]|ObjectCollection findByCountry(string $country) Return ChildAirport objects filtered by the country column
 * @method     ChildAirport[]|ObjectCollection findByIATA(string $iata) Return ChildAirport objects filtered by the iata column
 * @method     ChildAirport[]|ObjectCollection findByICAO(string $icao) Return ChildAirport objects filtered by the icao column
 * @method     ChildAirport[]|ObjectCollection findByAltitude(double $altitude) Return ChildAirport objects filtered by the altitude column
 * @method     ChildAirport[]|ObjectCollection findByTimezone(double $timezone) Return ChildAirport objects filtered by the timezone column
 * @method     ChildAirport[]|ObjectCollection findByDst(int $dst) Return ChildAirport objects filtered by the dst column
 * @method     ChildAirport[]|ObjectCollection findBySize(int $size) Return ChildAirport objects filtered by the size column
 * @method     ChildAirport[]|ObjectCollection findByLatitude(double $latitude) Return ChildAirport objects filtered by the latitude column
 * @method     ChildAirport[]|ObjectCollection findByLongitude(double $longitude) Return ChildAirport objects filtered by the longitude column
 * @method     ChildAirport[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AirportQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AirportQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Airport', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAirportQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAirportQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAirportQuery) {
            return $criteria;
        }
        $query = new ChildAirportQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildAirport|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AirportTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AirportTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAirport A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, city, country, iata, icao, altitude, timezone, dst, size, latitude, longitude FROM airports WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildAirport $obj */
            $obj = new ChildAirport();
            $obj->hydrate($row);
            AirportTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildAirport|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AirportTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AirportTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AirportTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AirportTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByCountry('%fooValue%'); // WHERE country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $country)) {
                $country = str_replace('*', '%', $country);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the iata column
     *
     * Example usage:
     * <code>
     * $query->filterByIATA('fooValue');   // WHERE iata = 'fooValue'
     * $query->filterByIATA('%fooValue%'); // WHERE iata LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iATA The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByIATA($iATA = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iATA)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $iATA)) {
                $iATA = str_replace('*', '%', $iATA);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_IATA, $iATA, $comparison);
    }

    /**
     * Filter the query on the icao column
     *
     * Example usage:
     * <code>
     * $query->filterByICAO('fooValue');   // WHERE icao = 'fooValue'
     * $query->filterByICAO('%fooValue%'); // WHERE icao LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iCAO The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByICAO($iCAO = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iCAO)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $iCAO)) {
                $iCAO = str_replace('*', '%', $iCAO);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_ICAO, $iCAO, $comparison);
    }

    /**
     * Filter the query on the altitude column
     *
     * Example usage:
     * <code>
     * $query->filterByAltitude(1234); // WHERE altitude = 1234
     * $query->filterByAltitude(array(12, 34)); // WHERE altitude IN (12, 34)
     * $query->filterByAltitude(array('min' => 12)); // WHERE altitude > 12
     * </code>
     *
     * @param     mixed $altitude The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByAltitude($altitude = null, $comparison = null)
    {
        if (is_array($altitude)) {
            $useMinMax = false;
            if (isset($altitude['min'])) {
                $this->addUsingAlias(AirportTableMap::COL_ALTITUDE, $altitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($altitude['max'])) {
                $this->addUsingAlias(AirportTableMap::COL_ALTITUDE, $altitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_ALTITUDE, $altitude, $comparison);
    }

    /**
     * Filter the query on the timezone column
     *
     * Example usage:
     * <code>
     * $query->filterByTimezone(1234); // WHERE timezone = 1234
     * $query->filterByTimezone(array(12, 34)); // WHERE timezone IN (12, 34)
     * $query->filterByTimezone(array('min' => 12)); // WHERE timezone > 12
     * </code>
     *
     * @param     mixed $timezone The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByTimezone($timezone = null, $comparison = null)
    {
        if (is_array($timezone)) {
            $useMinMax = false;
            if (isset($timezone['min'])) {
                $this->addUsingAlias(AirportTableMap::COL_TIMEZONE, $timezone['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timezone['max'])) {
                $this->addUsingAlias(AirportTableMap::COL_TIMEZONE, $timezone['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_TIMEZONE, $timezone, $comparison);
    }

    /**
     * Filter the query on the dst column
     *
     * @param     mixed $dst The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByDst($dst = null, $comparison = null)
    {
        $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_DST);
        if (is_scalar($dst)) {
            if (!in_array($dst, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $dst));
            }
            $dst = array_search($dst, $valueSet);
        } elseif (is_array($dst)) {
            $convertedValues = array();
            foreach ($dst as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $dst = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_DST, $dst, $comparison);
    }

    /**
     * Filter the query on the size column
     *
     * @param     mixed $size The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterBySize($size = null, $comparison = null)
    {
        $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_SIZE);
        if (is_scalar($size)) {
            if (!in_array($size, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $size));
            }
            $size = array_search($size, $valueSet);
        } elseif (is_array($size)) {
            $convertedValues = array();
            foreach ($size as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $size = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_SIZE, $size, $comparison);
    }

    /**
     * Filter the query on the latitude column
     *
     * Example usage:
     * <code>
     * $query->filterByLatitude(1234); // WHERE latitude = 1234
     * $query->filterByLatitude(array(12, 34)); // WHERE latitude IN (12, 34)
     * $query->filterByLatitude(array('min' => 12)); // WHERE latitude > 12
     * </code>
     *
     * @param     mixed $latitude The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByLatitude($latitude = null, $comparison = null)
    {
        if (is_array($latitude)) {
            $useMinMax = false;
            if (isset($latitude['min'])) {
                $this->addUsingAlias(AirportTableMap::COL_LATITUDE, $latitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($latitude['max'])) {
                $this->addUsingAlias(AirportTableMap::COL_LATITUDE, $latitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_LATITUDE, $latitude, $comparison);
    }

    /**
     * Filter the query on the longitude column
     *
     * Example usage:
     * <code>
     * $query->filterByLongitude(1234); // WHERE longitude = 1234
     * $query->filterByLongitude(array(12, 34)); // WHERE longitude IN (12, 34)
     * $query->filterByLongitude(array('min' => 12)); // WHERE longitude > 12
     * </code>
     *
     * @param     mixed $longitude The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function filterByLongitude($longitude = null, $comparison = null)
    {
        if (is_array($longitude)) {
            $useMinMax = false;
            if (isset($longitude['min'])) {
                $this->addUsingAlias(AirportTableMap::COL_LONGITUDE, $longitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($longitude['max'])) {
                $this->addUsingAlias(AirportTableMap::COL_LONGITUDE, $longitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirportTableMap::COL_LONGITUDE, $longitude, $comparison);
    }

    /**
     * Filter the query by a related \Model\Aircraft object
     *
     * @param \Model\Aircraft|ObjectCollection $aircraft the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByAircraft($aircraft, $comparison = null)
    {
        if ($aircraft instanceof \Model\Aircraft) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $aircraft->getAirportId(), $comparison);
        } elseif ($aircraft instanceof ObjectCollection) {
            return $this
                ->useAircraftQuery()
                ->filterByPrimaryKeys($aircraft->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAircraft() only accepts arguments of type \Model\Aircraft or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Aircraft relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinAircraft($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Aircraft');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Aircraft');
        }

        return $this;
    }

    /**
     * Use the Aircraft relation Aircraft object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AircraftQuery A secondary query class using the current class as primary query
     */
    public function useAircraftQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAircraft($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Aircraft', '\Model\AircraftQuery');
    }

    /**
     * Filter the query by a related \Model\Flight object
     *
     * @param \Model\Flight|ObjectCollection $flight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByFlightRelatedByDestinationId($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $flight->getDestinationId(), $comparison);
        } elseif ($flight instanceof ObjectCollection) {
            return $this
                ->useFlightRelatedByDestinationIdQuery()
                ->filterByPrimaryKeys($flight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFlightRelatedByDestinationId() only accepts arguments of type \Model\Flight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FlightRelatedByDestinationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinFlightRelatedByDestinationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FlightRelatedByDestinationId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FlightRelatedByDestinationId');
        }

        return $this;
    }

    /**
     * Use the FlightRelatedByDestinationId relation Flight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FlightQuery A secondary query class using the current class as primary query
     */
    public function useFlightRelatedByDestinationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFlightRelatedByDestinationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FlightRelatedByDestinationId', '\Model\FlightQuery');
    }

    /**
     * Filter the query by a related \Model\Flight object
     *
     * @param \Model\Flight|ObjectCollection $flight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByFlightRelatedByDepartureId($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $flight->getDepartureId(), $comparison);
        } elseif ($flight instanceof ObjectCollection) {
            return $this
                ->useFlightRelatedByDepartureIdQuery()
                ->filterByPrimaryKeys($flight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFlightRelatedByDepartureId() only accepts arguments of type \Model\Flight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FlightRelatedByDepartureId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinFlightRelatedByDepartureId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FlightRelatedByDepartureId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FlightRelatedByDepartureId');
        }

        return $this;
    }

    /**
     * Use the FlightRelatedByDepartureId relation Flight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FlightQuery A secondary query class using the current class as primary query
     */
    public function useFlightRelatedByDepartureIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFlightRelatedByDepartureId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FlightRelatedByDepartureId', '\Model\FlightQuery');
    }

    /**
     * Filter the query by a related \Model\Airway object
     *
     * @param \Model\Airway|ObjectCollection $airway the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByAirwayRelatedByDestinationId($airway, $comparison = null)
    {
        if ($airway instanceof \Model\Airway) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $airway->getDestinationId(), $comparison);
        } elseif ($airway instanceof ObjectCollection) {
            return $this
                ->useAirwayRelatedByDestinationIdQuery()
                ->filterByPrimaryKeys($airway->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAirwayRelatedByDestinationId() only accepts arguments of type \Model\Airway or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AirwayRelatedByDestinationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinAirwayRelatedByDestinationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AirwayRelatedByDestinationId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AirwayRelatedByDestinationId');
        }

        return $this;
    }

    /**
     * Use the AirwayRelatedByDestinationId relation Airway object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirwayQuery A secondary query class using the current class as primary query
     */
    public function useAirwayRelatedByDestinationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAirwayRelatedByDestinationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AirwayRelatedByDestinationId', '\Model\AirwayQuery');
    }

    /**
     * Filter the query by a related \Model\Airway object
     *
     * @param \Model\Airway|ObjectCollection $airway the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByAirwayRelatedByDepartureId($airway, $comparison = null)
    {
        if ($airway instanceof \Model\Airway) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $airway->getDepartureId(), $comparison);
        } elseif ($airway instanceof ObjectCollection) {
            return $this
                ->useAirwayRelatedByDepartureIdQuery()
                ->filterByPrimaryKeys($airway->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAirwayRelatedByDepartureId() only accepts arguments of type \Model\Airway or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AirwayRelatedByDepartureId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinAirwayRelatedByDepartureId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AirwayRelatedByDepartureId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AirwayRelatedByDepartureId');
        }

        return $this;
    }

    /**
     * Use the AirwayRelatedByDepartureId relation Airway object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirwayQuery A secondary query class using the current class as primary query
     */
    public function useAirwayRelatedByDepartureIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAirwayRelatedByDepartureId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AirwayRelatedByDepartureId', '\Model\AirwayQuery');
    }

    /**
     * Filter the query by a related \Model\Freight object
     *
     * @param \Model\Freight|ObjectCollection $freight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByFreightRelatedByDestinationId($freight, $comparison = null)
    {
        if ($freight instanceof \Model\Freight) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $freight->getDestinationId(), $comparison);
        } elseif ($freight instanceof ObjectCollection) {
            return $this
                ->useFreightRelatedByDestinationIdQuery()
                ->filterByPrimaryKeys($freight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFreightRelatedByDestinationId() only accepts arguments of type \Model\Freight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FreightRelatedByDestinationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinFreightRelatedByDestinationId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FreightRelatedByDestinationId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FreightRelatedByDestinationId');
        }

        return $this;
    }

    /**
     * Use the FreightRelatedByDestinationId relation Freight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FreightQuery A secondary query class using the current class as primary query
     */
    public function useFreightRelatedByDestinationIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFreightRelatedByDestinationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FreightRelatedByDestinationId', '\Model\FreightQuery');
    }

    /**
     * Filter the query by a related \Model\Freight object
     *
     * @param \Model\Freight|ObjectCollection $freight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByFreightRelatedByDepartureId($freight, $comparison = null)
    {
        if ($freight instanceof \Model\Freight) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $freight->getDepartureId(), $comparison);
        } elseif ($freight instanceof ObjectCollection) {
            return $this
                ->useFreightRelatedByDepartureIdQuery()
                ->filterByPrimaryKeys($freight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFreightRelatedByDepartureId() only accepts arguments of type \Model\Freight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FreightRelatedByDepartureId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinFreightRelatedByDepartureId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FreightRelatedByDepartureId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FreightRelatedByDepartureId');
        }

        return $this;
    }

    /**
     * Use the FreightRelatedByDepartureId relation Freight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FreightQuery A secondary query class using the current class as primary query
     */
    public function useFreightRelatedByDepartureIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFreightRelatedByDepartureId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FreightRelatedByDepartureId', '\Model\FreightQuery');
    }

    /**
     * Filter the query by a related \Model\Freight object
     *
     * @param \Model\Freight|ObjectCollection $freight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByFreightRelatedByLocationId($freight, $comparison = null)
    {
        if ($freight instanceof \Model\Freight) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $freight->getLocationId(), $comparison);
        } elseif ($freight instanceof ObjectCollection) {
            return $this
                ->useFreightRelatedByLocationIdQuery()
                ->filterByPrimaryKeys($freight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFreightRelatedByLocationId() only accepts arguments of type \Model\Freight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FreightRelatedByLocationId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinFreightRelatedByLocationId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FreightRelatedByLocationId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FreightRelatedByLocationId');
        }

        return $this;
    }

    /**
     * Use the FreightRelatedByLocationId relation Freight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FreightQuery A secondary query class using the current class as primary query
     */
    public function useFreightRelatedByLocationIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFreightRelatedByLocationId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FreightRelatedByLocationId', '\Model\FreightQuery');
    }

    /**
     * Filter the query by a related \Model\FreightGeneration object
     *
     * @param \Model\FreightGeneration|ObjectCollection $freightGeneration the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByFreightGeneration($freightGeneration, $comparison = null)
    {
        if ($freightGeneration instanceof \Model\FreightGeneration) {
            return $this
                ->addUsingAlias(AirportTableMap::COL_ID, $freightGeneration->getAirportId(), $comparison);
        } elseif ($freightGeneration instanceof ObjectCollection) {
            return $this
                ->useFreightGenerationQuery()
                ->filterByPrimaryKeys($freightGeneration->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFreightGeneration() only accepts arguments of type \Model\FreightGeneration or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FreightGeneration relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function joinFreightGeneration($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FreightGeneration');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FreightGeneration');
        }

        return $this;
    }

    /**
     * Use the FreightGeneration relation FreightGeneration object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FreightGenerationQuery A secondary query class using the current class as primary query
     */
    public function useFreightGenerationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFreightGeneration($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FreightGeneration', '\Model\FreightGenerationQuery');
    }

    /**
     * Filter the query by a related Airport object
     * using the airways table as cross reference
     *
     * @param Airport $airport the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByDeparture($airport, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useAirwayRelatedByDestinationIdQuery()
            ->filterByDeparture($airport, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Airport object
     * using the airways table as cross reference
     *
     * @param Airport $airport the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirportQuery The current query, for fluid interface
     */
    public function filterByDestination($airport, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useAirwayRelatedByDepartureIdQuery()
            ->filterByDestination($airport, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAirport $airport Object to remove from the list of results
     *
     * @return $this|ChildAirportQuery The current query, for fluid interface
     */
    public function prune($airport = null)
    {
        if ($airport) {
            $this->addUsingAlias(AirportTableMap::COL_ID, $airport->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the airports table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirportTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AirportTableMap::clearInstancePool();
            AirportTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirportTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AirportTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AirportTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AirportTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // geocodable behavior

    /**
     * Adds distance from a given origin column to query.
     *
     * @param double $latitude       The latitude of the origin point.
     * @param double $longitude      The longitude of the origin point.
     * @param double $unit           The unit measure.
     *
     * @return \Model\AirportQuery The current query, for fluid interface
     */
    public function withDistance($latitude, $longitude, $unit = AirportTableMap::KILOMETERS_UNIT)
    {
        if (AirportTableMap::MILES_UNIT === $unit) {
            $earthRadius = 3959;
        } elseif (AirportTableMap::NAUTICAL_MILES_UNIT === $unit) {
            $earthRadius = 3440;
        } else {
            $earthRadius = 6371;
        }

        $sql = 'ABS(%s * ACOS(%s * COS(RADIANS(%s)) * COS(RADIANS(%s) - %s) + %s * SIN(RADIANS(%s))))';
        $preparedSql = sprintf($sql,
            $earthRadius,
            cos(deg2rad($latitude)),
            $this->getAliasedColName(AirportTableMap::COL_LATITUDE),
            $this->getAliasedColName(AirportTableMap::COL_LONGITUDE),
            deg2rad($longitude),
            sin(deg2rad($latitude)),
            $this->getAliasedColName(AirportTableMap::COL_LATITUDE)
        );

        return $this
            ->withColumn($preparedSql, 'Distance');
    }

    /**
     * Filters objects by distance from a given origin.
     *
     * @param double $latitude       The latitude of the origin point.
     * @param double $longitude      The longitude of the origin point.
     * @param double $distance       The distance between the origin and the objects to find.
     * @param double $unit           The unit measure.
     * @param Criteria $comparison   Comparison sign (default is: `<`).
     *
     * @return \Model\AirportQuery The current query, for fluid interface
     */
    public function filterByDistanceFrom($latitude, $longitude, $distance, $unit = AirportTableMap::KILOMETERS_UNIT, $comparison = Criteria::LESS_THAN)
    {
        if (AirportTableMap::MILES_UNIT === $unit) {
            $earthRadius = 3959;
        } elseif (AirportTableMap::NAUTICAL_MILES_UNIT === $unit) {
            $earthRadius = 3440;
        } else {
            $earthRadius = 6371;
        }

        $sql = 'ABS(%s * ACOS(%s * COS(RADIANS(%s)) * COS(RADIANS(%s) - %s) + %s * SIN(RADIANS(%s))))';
        $preparedSql = sprintf($sql,
            $earthRadius,
            cos(deg2rad($latitude)),
            $this->getAliasedColName(AirportTableMap::COL_LATITUDE),
            $this->getAliasedColName(AirportTableMap::COL_LONGITUDE),
            deg2rad($longitude),
            sin(deg2rad($latitude)),
            $this->getAliasedColName(AirportTableMap::COL_LATITUDE)
        );

        return $this
            ->withColumn($preparedSql, 'Distance')
            ->where(sprintf('%s %s ?', $preparedSql, $comparison), $distance, PDO::PARAM_STR)
            ;
    }
    /**
     * Filters objects near a given \Model\Airport object.
     *
     * @param \Model\Airport $airport A \Model\Airport object.
     * @param double $distance The distance between the origin and the objects to find.
     * @param double $unit     The unit measure.
     *
     * @return \Model\AirportQuery The current query, for fluid interface
     */
    public function filterNear(\Model\Airport $airport, $distance = 5, $unit = AirportTableMap::KILOMETERS_UNIT)
    {
        return $this
            ->filterByDistanceFrom(
                $airport->getLatitude(),
                $airport->getLongitude(),
                $distance, $unit
            );
    }

} // AirportQuery

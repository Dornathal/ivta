<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Aircraft as ChildAircraft;
use Model\AircraftQuery as ChildAircraftQuery;
use Model\Map\AircraftTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'aircrafts' table.
 *
 *
 *
 * @method     ChildAircraftQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAircraftQuery orderByAircraftModelId($order = Criteria::ASC) Order by the aircraft_model_id column
 * @method     ChildAircraftQuery orderByAirlineId($order = Criteria::ASC) Order by the airline_id column
 * @method     ChildAircraftQuery orderByAirportId($order = Criteria::ASC) Order by the airport_id column
 * @method     ChildAircraftQuery orderByPilotId($order = Criteria::ASC) Order by the pilot_id column
 * @method     ChildAircraftQuery orderByPackages($order = Criteria::ASC) Order by the packages column
 * @method     ChildAircraftQuery orderByPost($order = Criteria::ASC) Order by the post column
 * @method     ChildAircraftQuery orderByPassengerLow($order = Criteria::ASC) Order by the passenger_low column
 * @method     ChildAircraftQuery orderByPassengerMid($order = Criteria::ASC) Order by the passenger_mid column
 * @method     ChildAircraftQuery orderByPassengerHigh($order = Criteria::ASC) Order by the passenger_high column
 * @method     ChildAircraftQuery orderByCallsign($order = Criteria::ASC) Order by the callsign column
 * @method     ChildAircraftQuery orderByFlownDistance($order = Criteria::ASC) Order by the flown_distance column
 * @method     ChildAircraftQuery orderByNumberFlights($order = Criteria::ASC) Order by the number_flights column
 * @method     ChildAircraftQuery orderByFlownTime($order = Criteria::ASC) Order by the flown_time column
 * @method     ChildAircraftQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildAircraftQuery orderByLatitude($order = Criteria::ASC) Order by the latitude column
 * @method     ChildAircraftQuery orderByLongitude($order = Criteria::ASC) Order by the longitude column
 *
 * @method     ChildAircraftQuery groupById() Group by the id column
 * @method     ChildAircraftQuery groupByAircraftModelId() Group by the aircraft_model_id column
 * @method     ChildAircraftQuery groupByAirlineId() Group by the airline_id column
 * @method     ChildAircraftQuery groupByAirportId() Group by the airport_id column
 * @method     ChildAircraftQuery groupByPilotId() Group by the pilot_id column
 * @method     ChildAircraftQuery groupByPackages() Group by the packages column
 * @method     ChildAircraftQuery groupByPost() Group by the post column
 * @method     ChildAircraftQuery groupByPassengerLow() Group by the passenger_low column
 * @method     ChildAircraftQuery groupByPassengerMid() Group by the passenger_mid column
 * @method     ChildAircraftQuery groupByPassengerHigh() Group by the passenger_high column
 * @method     ChildAircraftQuery groupByCallsign() Group by the callsign column
 * @method     ChildAircraftQuery groupByFlownDistance() Group by the flown_distance column
 * @method     ChildAircraftQuery groupByNumberFlights() Group by the number_flights column
 * @method     ChildAircraftQuery groupByFlownTime() Group by the flown_time column
 * @method     ChildAircraftQuery groupByStatus() Group by the status column
 * @method     ChildAircraftQuery groupByLatitude() Group by the latitude column
 * @method     ChildAircraftQuery groupByLongitude() Group by the longitude column
 *
 * @method     ChildAircraftQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAircraftQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAircraftQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAircraftQuery leftJoinAircraftModel($relationAlias = null) Adds a LEFT JOIN clause to the query using the AircraftModel relation
 * @method     ChildAircraftQuery rightJoinAircraftModel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AircraftModel relation
 * @method     ChildAircraftQuery innerJoinAircraftModel($relationAlias = null) Adds a INNER JOIN clause to the query using the AircraftModel relation
 *
 * @method     ChildAircraftQuery leftJoinAirport($relationAlias = null) Adds a LEFT JOIN clause to the query using the Airport relation
 * @method     ChildAircraftQuery rightJoinAirport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Airport relation
 * @method     ChildAircraftQuery innerJoinAirport($relationAlias = null) Adds a INNER JOIN clause to the query using the Airport relation
 *
 * @method     ChildAircraftQuery leftJoinAirline($relationAlias = null) Adds a LEFT JOIN clause to the query using the Airline relation
 * @method     ChildAircraftQuery rightJoinAirline($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Airline relation
 * @method     ChildAircraftQuery innerJoinAirline($relationAlias = null) Adds a INNER JOIN clause to the query using the Airline relation
 *
 * @method     ChildAircraftQuery leftJoinPilot($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pilot relation
 * @method     ChildAircraftQuery rightJoinPilot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pilot relation
 * @method     ChildAircraftQuery innerJoinPilot($relationAlias = null) Adds a INNER JOIN clause to the query using the Pilot relation
 *
 * @method     ChildAircraftQuery leftJoinFlight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Flight relation
 * @method     ChildAircraftQuery rightJoinFlight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Flight relation
 * @method     ChildAircraftQuery innerJoinFlight($relationAlias = null) Adds a INNER JOIN clause to the query using the Flight relation
 *
 * @method     \Model\AircraftModelQuery|\Model\AirportQuery|\Model\AirlineQuery|\Model\PilotQuery|\Model\FlightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAircraft findOne(ConnectionInterface $con = null) Return the first ChildAircraft matching the query
 * @method     ChildAircraft findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAircraft matching the query, or a new ChildAircraft object populated from the query conditions when no match is found
 *
 * @method     ChildAircraft findOneById(int $id) Return the first ChildAircraft filtered by the id column
 * @method     ChildAircraft findOneByAircraftModelId(int $aircraft_model_id) Return the first ChildAircraft filtered by the aircraft_model_id column
 * @method     ChildAircraft findOneByAirlineId(int $airline_id) Return the first ChildAircraft filtered by the airline_id column
 * @method     ChildAircraft findOneByAirportId(int $airport_id) Return the first ChildAircraft filtered by the airport_id column
 * @method     ChildAircraft findOneByPilotId(int $pilot_id) Return the first ChildAircraft filtered by the pilot_id column
 * @method     ChildAircraft findOneByPackages(int $packages) Return the first ChildAircraft filtered by the packages column
 * @method     ChildAircraft findOneByPost(int $post) Return the first ChildAircraft filtered by the post column
 * @method     ChildAircraft findOneByPassengerLow(int $passenger_low) Return the first ChildAircraft filtered by the passenger_low column
 * @method     ChildAircraft findOneByPassengerMid(int $passenger_mid) Return the first ChildAircraft filtered by the passenger_mid column
 * @method     ChildAircraft findOneByPassengerHigh(int $passenger_high) Return the first ChildAircraft filtered by the passenger_high column
 * @method     ChildAircraft findOneByCallsign(string $callsign) Return the first ChildAircraft filtered by the callsign column
 * @method     ChildAircraft findOneByFlownDistance(int $flown_distance) Return the first ChildAircraft filtered by the flown_distance column
 * @method     ChildAircraft findOneByNumberFlights(int $number_flights) Return the first ChildAircraft filtered by the number_flights column
 * @method     ChildAircraft findOneByFlownTime(int $flown_time) Return the first ChildAircraft filtered by the flown_time column
 * @method     ChildAircraft findOneByStatus(int $status) Return the first ChildAircraft filtered by the status column
 * @method     ChildAircraft findOneByLatitude(double $latitude) Return the first ChildAircraft filtered by the latitude column
 * @method     ChildAircraft findOneByLongitude(double $longitude) Return the first ChildAircraft filtered by the longitude column *

 * @method     ChildAircraft requirePk($key, ConnectionInterface $con = null) Return the ChildAircraft by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOne(ConnectionInterface $con = null) Return the first ChildAircraft matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAircraft requireOneById(int $id) Return the first ChildAircraft filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByAircraftModelId(int $aircraft_model_id) Return the first ChildAircraft filtered by the aircraft_model_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByAirlineId(int $airline_id) Return the first ChildAircraft filtered by the airline_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByAirportId(int $airport_id) Return the first ChildAircraft filtered by the airport_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByPilotId(int $pilot_id) Return the first ChildAircraft filtered by the pilot_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByPackages(int $packages) Return the first ChildAircraft filtered by the packages column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByPost(int $post) Return the first ChildAircraft filtered by the post column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByPassengerLow(int $passenger_low) Return the first ChildAircraft filtered by the passenger_low column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByPassengerMid(int $passenger_mid) Return the first ChildAircraft filtered by the passenger_mid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByPassengerHigh(int $passenger_high) Return the first ChildAircraft filtered by the passenger_high column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByCallsign(string $callsign) Return the first ChildAircraft filtered by the callsign column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByFlownDistance(int $flown_distance) Return the first ChildAircraft filtered by the flown_distance column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByNumberFlights(int $number_flights) Return the first ChildAircraft filtered by the number_flights column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByFlownTime(int $flown_time) Return the first ChildAircraft filtered by the flown_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByStatus(int $status) Return the first ChildAircraft filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByLatitude(double $latitude) Return the first ChildAircraft filtered by the latitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraft requireOneByLongitude(double $longitude) Return the first ChildAircraft filtered by the longitude column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAircraft[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAircraft objects based on current ModelCriteria
 * @method     ChildAircraft[]|ObjectCollection findById(int $id) Return ChildAircraft objects filtered by the id column
 * @method     ChildAircraft[]|ObjectCollection findByAircraftModelId(int $aircraft_model_id) Return ChildAircraft objects filtered by the aircraft_model_id column
 * @method     ChildAircraft[]|ObjectCollection findByAirlineId(int $airline_id) Return ChildAircraft objects filtered by the airline_id column
 * @method     ChildAircraft[]|ObjectCollection findByAirportId(int $airport_id) Return ChildAircraft objects filtered by the airport_id column
 * @method     ChildAircraft[]|ObjectCollection findByPilotId(int $pilot_id) Return ChildAircraft objects filtered by the pilot_id column
 * @method     ChildAircraft[]|ObjectCollection findByPackages(int $packages) Return ChildAircraft objects filtered by the packages column
 * @method     ChildAircraft[]|ObjectCollection findByPost(int $post) Return ChildAircraft objects filtered by the post column
 * @method     ChildAircraft[]|ObjectCollection findByPassengerLow(int $passenger_low) Return ChildAircraft objects filtered by the passenger_low column
 * @method     ChildAircraft[]|ObjectCollection findByPassengerMid(int $passenger_mid) Return ChildAircraft objects filtered by the passenger_mid column
 * @method     ChildAircraft[]|ObjectCollection findByPassengerHigh(int $passenger_high) Return ChildAircraft objects filtered by the passenger_high column
 * @method     ChildAircraft[]|ObjectCollection findByCallsign(string $callsign) Return ChildAircraft objects filtered by the callsign column
 * @method     ChildAircraft[]|ObjectCollection findByFlownDistance(int $flown_distance) Return ChildAircraft objects filtered by the flown_distance column
 * @method     ChildAircraft[]|ObjectCollection findByNumberFlights(int $number_flights) Return ChildAircraft objects filtered by the number_flights column
 * @method     ChildAircraft[]|ObjectCollection findByFlownTime(int $flown_time) Return ChildAircraft objects filtered by the flown_time column
 * @method     ChildAircraft[]|ObjectCollection findByStatus(int $status) Return ChildAircraft objects filtered by the status column
 * @method     ChildAircraft[]|ObjectCollection findByLatitude(double $latitude) Return ChildAircraft objects filtered by the latitude column
 * @method     ChildAircraft[]|ObjectCollection findByLongitude(double $longitude) Return ChildAircraft objects filtered by the longitude column
 * @method     ChildAircraft[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AircraftQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AircraftQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Aircraft', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAircraftQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAircraftQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAircraftQuery) {
            return $criteria;
        }
        $query = new ChildAircraftQuery();
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
     * $obj = $c->findPk(array(12, 34, 56, 78), $con);
     * </code>
     *
     * @param array[$id, $aircraft_model_id, $airline_id, $pilot_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildAircraft|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AircraftTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2], (string) $key[3]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AircraftTableMap::DATABASE_NAME);
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
     * @return ChildAircraft A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, aircraft_model_id, airline_id, airport_id, pilot_id, packages, post, passenger_low, passenger_mid, passenger_high, callsign, flown_distance, number_flights, flown_time, status, latitude, longitude FROM aircrafts WHERE id = :p0 AND aircraft_model_id = :p1 AND airline_id = :p2 AND pilot_id = :p3';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->bindValue(':p3', $key[3], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildAircraft $obj */
            $obj = new ChildAircraft();
            $obj->hydrate($row);
            AircraftTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2], (string) $key[3])));
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
     * @return ChildAircraft|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(AircraftTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(AircraftTableMap::COL_AIRLINE_ID, $key[2], Criteria::EQUAL);
        $this->addUsingAlias(AircraftTableMap::COL_PILOT_ID, $key[3], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(AircraftTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(AircraftTableMap::COL_AIRLINE_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $cton3 = $this->getNewCriterion(AircraftTableMap::COL_PILOT_ID, $key[3], Criteria::EQUAL);
            $cton0->addAnd($cton3);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the aircraft_model_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAircraftModelId(1234); // WHERE aircraft_model_id = 1234
     * $query->filterByAircraftModelId(array(12, 34)); // WHERE aircraft_model_id IN (12, 34)
     * $query->filterByAircraftModelId(array('min' => 12)); // WHERE aircraft_model_id > 12
     * </code>
     *
     * @see       filterByAircraftModel()
     *
     * @param     mixed $aircraftModelId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByAircraftModelId($aircraftModelId = null, $comparison = null)
    {
        if (is_array($aircraftModelId)) {
            $useMinMax = false;
            if (isset($aircraftModelId['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $aircraftModelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($aircraftModelId['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $aircraftModelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $aircraftModelId, $comparison);
    }

    /**
     * Filter the query on the airline_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAirlineId(1234); // WHERE airline_id = 1234
     * $query->filterByAirlineId(array(12, 34)); // WHERE airline_id IN (12, 34)
     * $query->filterByAirlineId(array('min' => 12)); // WHERE airline_id > 12
     * </code>
     *
     * @see       filterByAirline()
     *
     * @param     mixed $airlineId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByAirlineId($airlineId = null, $comparison = null)
    {
        if (is_array($airlineId)) {
            $useMinMax = false;
            if (isset($airlineId['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_AIRLINE_ID, $airlineId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($airlineId['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_AIRLINE_ID, $airlineId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_AIRLINE_ID, $airlineId, $comparison);
    }

    /**
     * Filter the query on the airport_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAirportId(1234); // WHERE airport_id = 1234
     * $query->filterByAirportId(array(12, 34)); // WHERE airport_id IN (12, 34)
     * $query->filterByAirportId(array('min' => 12)); // WHERE airport_id > 12
     * </code>
     *
     * @see       filterByAirport()
     *
     * @param     mixed $airportId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByAirportId($airportId = null, $comparison = null)
    {
        if (is_array($airportId)) {
            $useMinMax = false;
            if (isset($airportId['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_AIRPORT_ID, $airportId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($airportId['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_AIRPORT_ID, $airportId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_AIRPORT_ID, $airportId, $comparison);
    }

    /**
     * Filter the query on the pilot_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPilotId(1234); // WHERE pilot_id = 1234
     * $query->filterByPilotId(array(12, 34)); // WHERE pilot_id IN (12, 34)
     * $query->filterByPilotId(array('min' => 12)); // WHERE pilot_id > 12
     * </code>
     *
     * @see       filterByPilot()
     *
     * @param     mixed $pilotId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPilotId($pilotId = null, $comparison = null)
    {
        if (is_array($pilotId)) {
            $useMinMax = false;
            if (isset($pilotId['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PILOT_ID, $pilotId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pilotId['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PILOT_ID, $pilotId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_PILOT_ID, $pilotId, $comparison);
    }

    /**
     * Filter the query on the packages column
     *
     * Example usage:
     * <code>
     * $query->filterByPackages(1234); // WHERE packages = 1234
     * $query->filterByPackages(array(12, 34)); // WHERE packages IN (12, 34)
     * $query->filterByPackages(array('min' => 12)); // WHERE packages > 12
     * </code>
     *
     * @param     mixed $packages The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPackages($packages = null, $comparison = null)
    {
        if (is_array($packages)) {
            $useMinMax = false;
            if (isset($packages['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PACKAGES, $packages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($packages['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PACKAGES, $packages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_PACKAGES, $packages, $comparison);
    }

    /**
     * Filter the query on the post column
     *
     * Example usage:
     * <code>
     * $query->filterByPost(1234); // WHERE post = 1234
     * $query->filterByPost(array(12, 34)); // WHERE post IN (12, 34)
     * $query->filterByPost(array('min' => 12)); // WHERE post > 12
     * </code>
     *
     * @param     mixed $post The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPost($post = null, $comparison = null)
    {
        if (is_array($post)) {
            $useMinMax = false;
            if (isset($post['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_POST, $post['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($post['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_POST, $post['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_POST, $post, $comparison);
    }

    /**
     * Filter the query on the passenger_low column
     *
     * Example usage:
     * <code>
     * $query->filterByPassengerLow(1234); // WHERE passenger_low = 1234
     * $query->filterByPassengerLow(array(12, 34)); // WHERE passenger_low IN (12, 34)
     * $query->filterByPassengerLow(array('min' => 12)); // WHERE passenger_low > 12
     * </code>
     *
     * @param     mixed $passengerLow The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPassengerLow($passengerLow = null, $comparison = null)
    {
        if (is_array($passengerLow)) {
            $useMinMax = false;
            if (isset($passengerLow['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_LOW, $passengerLow['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerLow['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_LOW, $passengerLow['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_LOW, $passengerLow, $comparison);
    }

    /**
     * Filter the query on the passenger_mid column
     *
     * Example usage:
     * <code>
     * $query->filterByPassengerMid(1234); // WHERE passenger_mid = 1234
     * $query->filterByPassengerMid(array(12, 34)); // WHERE passenger_mid IN (12, 34)
     * $query->filterByPassengerMid(array('min' => 12)); // WHERE passenger_mid > 12
     * </code>
     *
     * @param     mixed $passengerMid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPassengerMid($passengerMid = null, $comparison = null)
    {
        if (is_array($passengerMid)) {
            $useMinMax = false;
            if (isset($passengerMid['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_MID, $passengerMid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerMid['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_MID, $passengerMid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_MID, $passengerMid, $comparison);
    }

    /**
     * Filter the query on the passenger_high column
     *
     * Example usage:
     * <code>
     * $query->filterByPassengerHigh(1234); // WHERE passenger_high = 1234
     * $query->filterByPassengerHigh(array(12, 34)); // WHERE passenger_high IN (12, 34)
     * $query->filterByPassengerHigh(array('min' => 12)); // WHERE passenger_high > 12
     * </code>
     *
     * @param     mixed $passengerHigh The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPassengerHigh($passengerHigh = null, $comparison = null)
    {
        if (is_array($passengerHigh)) {
            $useMinMax = false;
            if (isset($passengerHigh['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_HIGH, $passengerHigh['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerHigh['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_HIGH, $passengerHigh['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_PASSENGER_HIGH, $passengerHigh, $comparison);
    }

    /**
     * Filter the query on the callsign column
     *
     * Example usage:
     * <code>
     * $query->filterByCallsign('fooValue');   // WHERE callsign = 'fooValue'
     * $query->filterByCallsign('%fooValue%'); // WHERE callsign LIKE '%fooValue%'
     * </code>
     *
     * @param     string $callsign The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByCallsign($callsign = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($callsign)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $callsign)) {
                $callsign = str_replace('*', '%', $callsign);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_CALLSIGN, $callsign, $comparison);
    }

    /**
     * Filter the query on the flown_distance column
     *
     * Example usage:
     * <code>
     * $query->filterByFlownDistance(1234); // WHERE flown_distance = 1234
     * $query->filterByFlownDistance(array(12, 34)); // WHERE flown_distance IN (12, 34)
     * $query->filterByFlownDistance(array('min' => 12)); // WHERE flown_distance > 12
     * </code>
     *
     * @param     mixed $flownDistance The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByFlownDistance($flownDistance = null, $comparison = null)
    {
        if (is_array($flownDistance)) {
            $useMinMax = false;
            if (isset($flownDistance['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_FLOWN_DISTANCE, $flownDistance['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($flownDistance['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_FLOWN_DISTANCE, $flownDistance['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_FLOWN_DISTANCE, $flownDistance, $comparison);
    }

    /**
     * Filter the query on the number_flights column
     *
     * Example usage:
     * <code>
     * $query->filterByNumberFlights(1234); // WHERE number_flights = 1234
     * $query->filterByNumberFlights(array(12, 34)); // WHERE number_flights IN (12, 34)
     * $query->filterByNumberFlights(array('min' => 12)); // WHERE number_flights > 12
     * </code>
     *
     * @param     mixed $numberFlights The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByNumberFlights($numberFlights = null, $comparison = null)
    {
        if (is_array($numberFlights)) {
            $useMinMax = false;
            if (isset($numberFlights['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_NUMBER_FLIGHTS, $numberFlights['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numberFlights['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_NUMBER_FLIGHTS, $numberFlights['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_NUMBER_FLIGHTS, $numberFlights, $comparison);
    }

    /**
     * Filter the query on the flown_time column
     *
     * Example usage:
     * <code>
     * $query->filterByFlownTime(1234); // WHERE flown_time = 1234
     * $query->filterByFlownTime(array(12, 34)); // WHERE flown_time IN (12, 34)
     * $query->filterByFlownTime(array('min' => 12)); // WHERE flown_time > 12
     * </code>
     *
     * @param     mixed $flownTime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByFlownTime($flownTime = null, $comparison = null)
    {
        if (is_array($flownTime)) {
            $useMinMax = false;
            if (isset($flownTime['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_FLOWN_TIME, $flownTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($flownTime['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_FLOWN_TIME, $flownTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_FLOWN_TIME, $flownTime, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        $valueSet = AircraftTableMap::getValueSet(AircraftTableMap::COL_STATUS);
        if (is_scalar($status)) {
            if (!in_array($status, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $status));
            }
            $status = array_search($status, $valueSet);
        } elseif (is_array($status)) {
            $convertedValues = array();
            foreach ($status as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $status = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_STATUS, $status, $comparison);
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
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByLatitude($latitude = null, $comparison = null)
    {
        if (is_array($latitude)) {
            $useMinMax = false;
            if (isset($latitude['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_LATITUDE, $latitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($latitude['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_LATITUDE, $latitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_LATITUDE, $latitude, $comparison);
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
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByLongitude($longitude = null, $comparison = null)
    {
        if (is_array($longitude)) {
            $useMinMax = false;
            if (isset($longitude['min'])) {
                $this->addUsingAlias(AircraftTableMap::COL_LONGITUDE, $longitude['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($longitude['max'])) {
                $this->addUsingAlias(AircraftTableMap::COL_LONGITUDE, $longitude['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftTableMap::COL_LONGITUDE, $longitude, $comparison);
    }

    /**
     * Filter the query by a related \Model\AircraftModel object
     *
     * @param \Model\AircraftModel|ObjectCollection $aircraftModel The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByAircraftModel($aircraftModel, $comparison = null)
    {
        if ($aircraftModel instanceof \Model\AircraftModel) {
            return $this
                ->addUsingAlias(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $aircraftModel->getId(), $comparison);
        } elseif ($aircraftModel instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $aircraftModel->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAircraftModel() only accepts arguments of type \Model\AircraftModel or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AircraftModel relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function joinAircraftModel($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AircraftModel');

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
            $this->addJoinObject($join, 'AircraftModel');
        }

        return $this;
    }

    /**
     * Use the AircraftModel relation AircraftModel object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AircraftModelQuery A secondary query class using the current class as primary query
     */
    public function useAircraftModelQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAircraftModel($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AircraftModel', '\Model\AircraftModelQuery');
    }

    /**
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByAirport($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(AircraftTableMap::COL_AIRPORT_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AircraftTableMap::COL_AIRPORT_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAirport() only accepts arguments of type \Model\Airport or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Airport relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function joinAirport($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Airport');

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
            $this->addJoinObject($join, 'Airport');
        }

        return $this;
    }

    /**
     * Use the Airport relation Airport object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirportQuery A secondary query class using the current class as primary query
     */
    public function useAirportQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAirport($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Airport', '\Model\AirportQuery');
    }

    /**
     * Filter the query by a related \Model\Airline object
     *
     * @param \Model\Airline|ObjectCollection $airline The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByAirline($airline, $comparison = null)
    {
        if ($airline instanceof \Model\Airline) {
            return $this
                ->addUsingAlias(AircraftTableMap::COL_AIRLINE_ID, $airline->getId(), $comparison);
        } elseif ($airline instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AircraftTableMap::COL_AIRLINE_ID, $airline->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAirline() only accepts arguments of type \Model\Airline or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Airline relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function joinAirline($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Airline');

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
            $this->addJoinObject($join, 'Airline');
        }

        return $this;
    }

    /**
     * Use the Airline relation Airline object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirlineQuery A secondary query class using the current class as primary query
     */
    public function useAirlineQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAirline($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Airline', '\Model\AirlineQuery');
    }

    /**
     * Filter the query by a related \Model\Pilot object
     *
     * @param \Model\Pilot|ObjectCollection $pilot The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByPilot($pilot, $comparison = null)
    {
        if ($pilot instanceof \Model\Pilot) {
            return $this
                ->addUsingAlias(AircraftTableMap::COL_PILOT_ID, $pilot->getId(), $comparison);
        } elseif ($pilot instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AircraftTableMap::COL_PILOT_ID, $pilot->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPilot() only accepts arguments of type \Model\Pilot or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Pilot relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function joinPilot($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Pilot');

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
            $this->addJoinObject($join, 'Pilot');
        }

        return $this;
    }

    /**
     * Use the Pilot relation Pilot object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PilotQuery A secondary query class using the current class as primary query
     */
    public function usePilotQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPilot($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Pilot', '\Model\PilotQuery');
    }

    /**
     * Filter the query by a related \Model\Flight object
     *
     * @param \Model\Flight|ObjectCollection $flight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAircraftQuery The current query, for fluid interface
     */
    public function filterByFlight($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(AircraftTableMap::COL_ID, $flight->getAircraftId(), $comparison);
        } elseif ($flight instanceof ObjectCollection) {
            return $this
                ->useFlightQuery()
                ->filterByPrimaryKeys($flight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFlight() only accepts arguments of type \Model\Flight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Flight relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function joinFlight($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Flight');

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
            $this->addJoinObject($join, 'Flight');
        }

        return $this;
    }

    /**
     * Use the Flight relation Flight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FlightQuery A secondary query class using the current class as primary query
     */
    public function useFlightQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFlight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Flight', '\Model\FlightQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAircraft $aircraft Object to remove from the list of results
     *
     * @return $this|ChildAircraftQuery The current query, for fluid interface
     */
    public function prune($aircraft = null)
    {
        if ($aircraft) {
            $this->addCond('pruneCond0', $this->getAliasedColName(AircraftTableMap::COL_ID), $aircraft->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(AircraftTableMap::COL_AIRCRAFT_MODEL_ID), $aircraft->getAircraftModelId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(AircraftTableMap::COL_AIRLINE_ID), $aircraft->getAirlineId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond3', $this->getAliasedColName(AircraftTableMap::COL_PILOT_ID), $aircraft->getPilotId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2', 'pruneCond3'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the aircrafts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AircraftTableMap::clearInstancePool();
            AircraftTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AircraftTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AircraftTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AircraftTableMap::clearRelatedInstancePool();

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
     * @return \Model\AircraftQuery The current query, for fluid interface
     */
    public function withDistance($latitude, $longitude, $unit = AircraftTableMap::KILOMETERS_UNIT)
    {
        if (AircraftTableMap::MILES_UNIT === $unit) {
            $earthRadius = 3959;
        } elseif (AircraftTableMap::NAUTICAL_MILES_UNIT === $unit) {
            $earthRadius = 3440;
        } else {
            $earthRadius = 6371;
        }

        $sql = 'ABS(%s * ACOS(%s * COS(RADIANS(%s)) * COS(RADIANS(%s) - %s) + %s * SIN(RADIANS(%s))))';
        $preparedSql = sprintf($sql,
            $earthRadius,
            cos(deg2rad($latitude)),
            $this->getAliasedColName(AircraftTableMap::COL_LATITUDE),
            $this->getAliasedColName(AircraftTableMap::COL_LONGITUDE),
            deg2rad($longitude),
            sin(deg2rad($latitude)),
            $this->getAliasedColName(AircraftTableMap::COL_LATITUDE)
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
     * @return \Model\AircraftQuery The current query, for fluid interface
     */
    public function filterByDistanceFrom($latitude, $longitude, $distance, $unit = AircraftTableMap::KILOMETERS_UNIT, $comparison = Criteria::LESS_THAN)
    {
        if (AircraftTableMap::MILES_UNIT === $unit) {
            $earthRadius = 3959;
        } elseif (AircraftTableMap::NAUTICAL_MILES_UNIT === $unit) {
            $earthRadius = 3440;
        } else {
            $earthRadius = 6371;
        }

        $sql = 'ABS(%s * ACOS(%s * COS(RADIANS(%s)) * COS(RADIANS(%s) - %s) + %s * SIN(RADIANS(%s))))';
        $preparedSql = sprintf($sql,
            $earthRadius,
            cos(deg2rad($latitude)),
            $this->getAliasedColName(AircraftTableMap::COL_LATITUDE),
            $this->getAliasedColName(AircraftTableMap::COL_LONGITUDE),
            deg2rad($longitude),
            sin(deg2rad($latitude)),
            $this->getAliasedColName(AircraftTableMap::COL_LATITUDE)
        );

        return $this
            ->withColumn($preparedSql, 'Distance')
            ->where(sprintf('%s %s ?', $preparedSql, $comparison), $distance, PDO::PARAM_STR)
            ;
    }
    /**
     * Filters objects near a given \Model\Aircraft object.
     *
     * @param \Model\Aircraft $aircraft A \Model\Aircraft object.
     * @param double $distance The distance between the origin and the objects to find.
     * @param double $unit     The unit measure.
     *
     * @return \Model\AircraftQuery The current query, for fluid interface
     */
    public function filterNear(\Model\Aircraft $aircraft, $distance = 5, $unit = AircraftTableMap::KILOMETERS_UNIT)
    {
        return $this
            ->filterByDistanceFrom(
                $aircraft->getLatitude(),
                $aircraft->getLongitude(),
                $distance, $unit
            );
    }

} // AircraftQuery

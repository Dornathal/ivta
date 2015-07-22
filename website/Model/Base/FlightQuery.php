<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Flight as ChildFlight;
use Model\FlightQuery as ChildFlightQuery;
use Model\Map\FlightTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'flights' table.
 *
 *
 *
 * @method     ChildFlightQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFlightQuery orderByAircraftId($order = Criteria::ASC) Order by the aircraft_id column
 * @method     ChildFlightQuery orderByDestinationId($order = Criteria::ASC) Order by the destination_id column
 * @method     ChildFlightQuery orderByDepartureId($order = Criteria::ASC) Order by the departure_id column
 * @method     ChildFlightQuery orderByPilotId($order = Criteria::ASC) Order by the pilot_id column
 * @method     ChildFlightQuery orderByFlightNumber($order = Criteria::ASC) Order by the flight_number column
 * @method     ChildFlightQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildFlightQuery orderByPackages($order = Criteria::ASC) Order by the packages column
 * @method     ChildFlightQuery orderByPost($order = Criteria::ASC) Order by the post column
 * @method     ChildFlightQuery orderByPassengerLow($order = Criteria::ASC) Order by the passenger_low column
 * @method     ChildFlightQuery orderByPassengerMid($order = Criteria::ASC) Order by the passenger_mid column
 * @method     ChildFlightQuery orderByPassengerHigh($order = Criteria::ASC) Order by the passenger_high column
 * @method     ChildFlightQuery orderByFlightStartedAt($order = Criteria::ASC) Order by the flight_started_at column
 * @method     ChildFlightQuery orderByFlightFinishedAt($order = Criteria::ASC) Order by the flight_finished_at column
 * @method     ChildFlightQuery orderByNextStepPossibleAt($order = Criteria::ASC) Order by the next_step_possible_at column
 * @method     ChildFlightQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildFlightQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildFlightQuery groupById() Group by the id column
 * @method     ChildFlightQuery groupByAircraftId() Group by the aircraft_id column
 * @method     ChildFlightQuery groupByDestinationId() Group by the destination_id column
 * @method     ChildFlightQuery groupByDepartureId() Group by the departure_id column
 * @method     ChildFlightQuery groupByPilotId() Group by the pilot_id column
 * @method     ChildFlightQuery groupByFlightNumber() Group by the flight_number column
 * @method     ChildFlightQuery groupByStatus() Group by the status column
 * @method     ChildFlightQuery groupByPackages() Group by the packages column
 * @method     ChildFlightQuery groupByPost() Group by the post column
 * @method     ChildFlightQuery groupByPassengerLow() Group by the passenger_low column
 * @method     ChildFlightQuery groupByPassengerMid() Group by the passenger_mid column
 * @method     ChildFlightQuery groupByPassengerHigh() Group by the passenger_high column
 * @method     ChildFlightQuery groupByFlightStartedAt() Group by the flight_started_at column
 * @method     ChildFlightQuery groupByFlightFinishedAt() Group by the flight_finished_at column
 * @method     ChildFlightQuery groupByNextStepPossibleAt() Group by the next_step_possible_at column
 * @method     ChildFlightQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildFlightQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildFlightQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFlightQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFlightQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFlightQuery leftJoinAircraft($relationAlias = null) Adds a LEFT JOIN clause to the query using the Aircraft relation
 * @method     ChildFlightQuery rightJoinAircraft($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Aircraft relation
 * @method     ChildFlightQuery innerJoinAircraft($relationAlias = null) Adds a INNER JOIN clause to the query using the Aircraft relation
 *
 * @method     ChildFlightQuery leftJoinDestination($relationAlias = null) Adds a LEFT JOIN clause to the query using the Destination relation
 * @method     ChildFlightQuery rightJoinDestination($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Destination relation
 * @method     ChildFlightQuery innerJoinDestination($relationAlias = null) Adds a INNER JOIN clause to the query using the Destination relation
 *
 * @method     ChildFlightQuery leftJoinDeparture($relationAlias = null) Adds a LEFT JOIN clause to the query using the Departure relation
 * @method     ChildFlightQuery rightJoinDeparture($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Departure relation
 * @method     ChildFlightQuery innerJoinDeparture($relationAlias = null) Adds a INNER JOIN clause to the query using the Departure relation
 *
 * @method     ChildFlightQuery leftJoinPilot($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pilot relation
 * @method     ChildFlightQuery rightJoinPilot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pilot relation
 * @method     ChildFlightQuery innerJoinPilot($relationAlias = null) Adds a INNER JOIN clause to the query using the Pilot relation
 *
 * @method     ChildFlightQuery leftJoinFreight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Freight relation
 * @method     ChildFlightQuery rightJoinFreight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Freight relation
 * @method     ChildFlightQuery innerJoinFreight($relationAlias = null) Adds a INNER JOIN clause to the query using the Freight relation
 *
 * @method     \Model\AircraftQuery|\Model\AirportQuery|\Model\PilotQuery|\Model\FreightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFlight findOne(ConnectionInterface $con = null) Return the first ChildFlight matching the query
 * @method     ChildFlight findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFlight matching the query, or a new ChildFlight object populated from the query conditions when no match is found
 *
 * @method     ChildFlight findOneById(int $id) Return the first ChildFlight filtered by the id column
 * @method     ChildFlight findOneByAircraftId(int $aircraft_id) Return the first ChildFlight filtered by the aircraft_id column
 * @method     ChildFlight findOneByDestinationId(int $destination_id) Return the first ChildFlight filtered by the destination_id column
 * @method     ChildFlight findOneByDepartureId(int $departure_id) Return the first ChildFlight filtered by the departure_id column
 * @method     ChildFlight findOneByPilotId(int $pilot_id) Return the first ChildFlight filtered by the pilot_id column
 * @method     ChildFlight findOneByFlightNumber(string $flight_number) Return the first ChildFlight filtered by the flight_number column
 * @method     ChildFlight findOneByStatus(int $status) Return the first ChildFlight filtered by the status column
 * @method     ChildFlight findOneByPackages(int $packages) Return the first ChildFlight filtered by the packages column
 * @method     ChildFlight findOneByPost(int $post) Return the first ChildFlight filtered by the post column
 * @method     ChildFlight findOneByPassengerLow(int $passenger_low) Return the first ChildFlight filtered by the passenger_low column
 * @method     ChildFlight findOneByPassengerMid(int $passenger_mid) Return the first ChildFlight filtered by the passenger_mid column
 * @method     ChildFlight findOneByPassengerHigh(int $passenger_high) Return the first ChildFlight filtered by the passenger_high column
 * @method     ChildFlight findOneByFlightStartedAt(string $flight_started_at) Return the first ChildFlight filtered by the flight_started_at column
 * @method     ChildFlight findOneByFlightFinishedAt(string $flight_finished_at) Return the first ChildFlight filtered by the flight_finished_at column
 * @method     ChildFlight findOneByNextStepPossibleAt(string $next_step_possible_at) Return the first ChildFlight filtered by the next_step_possible_at column
 * @method     ChildFlight findOneByCreatedAt(string $created_at) Return the first ChildFlight filtered by the created_at column
 * @method     ChildFlight findOneByUpdatedAt(string $updated_at) Return the first ChildFlight filtered by the updated_at column *

 * @method     ChildFlight requirePk($key, ConnectionInterface $con = null) Return the ChildFlight by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOne(ConnectionInterface $con = null) Return the first ChildFlight matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFlight requireOneById(int $id) Return the first ChildFlight filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByAircraftId(int $aircraft_id) Return the first ChildFlight filtered by the aircraft_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByDestinationId(int $destination_id) Return the first ChildFlight filtered by the destination_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByDepartureId(int $departure_id) Return the first ChildFlight filtered by the departure_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByPilotId(int $pilot_id) Return the first ChildFlight filtered by the pilot_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByFlightNumber(string $flight_number) Return the first ChildFlight filtered by the flight_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByStatus(int $status) Return the first ChildFlight filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByPackages(int $packages) Return the first ChildFlight filtered by the packages column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByPost(int $post) Return the first ChildFlight filtered by the post column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByPassengerLow(int $passenger_low) Return the first ChildFlight filtered by the passenger_low column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByPassengerMid(int $passenger_mid) Return the first ChildFlight filtered by the passenger_mid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByPassengerHigh(int $passenger_high) Return the first ChildFlight filtered by the passenger_high column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByFlightStartedAt(string $flight_started_at) Return the first ChildFlight filtered by the flight_started_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByFlightFinishedAt(string $flight_finished_at) Return the first ChildFlight filtered by the flight_finished_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByNextStepPossibleAt(string $next_step_possible_at) Return the first ChildFlight filtered by the next_step_possible_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByCreatedAt(string $created_at) Return the first ChildFlight filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFlight requireOneByUpdatedAt(string $updated_at) Return the first ChildFlight filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFlight[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFlight objects based on current ModelCriteria
 * @method     ChildFlight[]|ObjectCollection findById(int $id) Return ChildFlight objects filtered by the id column
 * @method     ChildFlight[]|ObjectCollection findByAircraftId(int $aircraft_id) Return ChildFlight objects filtered by the aircraft_id column
 * @method     ChildFlight[]|ObjectCollection findByDestinationId(int $destination_id) Return ChildFlight objects filtered by the destination_id column
 * @method     ChildFlight[]|ObjectCollection findByDepartureId(int $departure_id) Return ChildFlight objects filtered by the departure_id column
 * @method     ChildFlight[]|ObjectCollection findByPilotId(int $pilot_id) Return ChildFlight objects filtered by the pilot_id column
 * @method     ChildFlight[]|ObjectCollection findByFlightNumber(string $flight_number) Return ChildFlight objects filtered by the flight_number column
 * @method     ChildFlight[]|ObjectCollection findByStatus(int $status) Return ChildFlight objects filtered by the status column
 * @method     ChildFlight[]|ObjectCollection findByPackages(int $packages) Return ChildFlight objects filtered by the packages column
 * @method     ChildFlight[]|ObjectCollection findByPost(int $post) Return ChildFlight objects filtered by the post column
 * @method     ChildFlight[]|ObjectCollection findByPassengerLow(int $passenger_low) Return ChildFlight objects filtered by the passenger_low column
 * @method     ChildFlight[]|ObjectCollection findByPassengerMid(int $passenger_mid) Return ChildFlight objects filtered by the passenger_mid column
 * @method     ChildFlight[]|ObjectCollection findByPassengerHigh(int $passenger_high) Return ChildFlight objects filtered by the passenger_high column
 * @method     ChildFlight[]|ObjectCollection findByFlightStartedAt(string $flight_started_at) Return ChildFlight objects filtered by the flight_started_at column
 * @method     ChildFlight[]|ObjectCollection findByFlightFinishedAt(string $flight_finished_at) Return ChildFlight objects filtered by the flight_finished_at column
 * @method     ChildFlight[]|ObjectCollection findByNextStepPossibleAt(string $next_step_possible_at) Return ChildFlight objects filtered by the next_step_possible_at column
 * @method     ChildFlight[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildFlight objects filtered by the created_at column
 * @method     ChildFlight[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildFlight objects filtered by the updated_at column
 * @method     ChildFlight[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FlightQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\FlightQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Flight', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFlightQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFlightQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFlightQuery) {
            return $criteria;
        }
        $query = new ChildFlightQuery();
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
     * $obj = $c->findPk(array(12, 34, 56, 78, 91), $con);
     * </code>
     *
     * @param array[$id, $aircraft_id, $destination_id, $departure_id, $pilot_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFlight|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FlightTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2], (string) $key[3], (string) $key[4]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FlightTableMap::DATABASE_NAME);
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
     * @return ChildFlight A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, aircraft_id, destination_id, departure_id, pilot_id, flight_number, status, packages, post, passenger_low, passenger_mid, passenger_high, flight_started_at, flight_finished_at, next_step_possible_at, created_at, updated_at FROM flights WHERE id = :p0 AND aircraft_id = :p1 AND destination_id = :p2 AND departure_id = :p3 AND pilot_id = :p4';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->bindValue(':p3', $key[3], PDO::PARAM_INT);
            $stmt->bindValue(':p4', $key[4], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFlight $obj */
            $obj = new ChildFlight();
            $obj->hydrate($row);
            FlightTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2], (string) $key[3], (string) $key[4])));
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
     * @return ChildFlight|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(FlightTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(FlightTableMap::COL_AIRCRAFT_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(FlightTableMap::COL_DESTINATION_ID, $key[2], Criteria::EQUAL);
        $this->addUsingAlias(FlightTableMap::COL_DEPARTURE_ID, $key[3], Criteria::EQUAL);
        $this->addUsingAlias(FlightTableMap::COL_PILOT_ID, $key[4], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(FlightTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(FlightTableMap::COL_AIRCRAFT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(FlightTableMap::COL_DESTINATION_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $cton3 = $this->getNewCriterion(FlightTableMap::COL_DEPARTURE_ID, $key[3], Criteria::EQUAL);
            $cton0->addAnd($cton3);
            $cton4 = $this->getNewCriterion(FlightTableMap::COL_PILOT_ID, $key[4], Criteria::EQUAL);
            $cton0->addAnd($cton4);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the aircraft_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAircraftId(1234); // WHERE aircraft_id = 1234
     * $query->filterByAircraftId(array(12, 34)); // WHERE aircraft_id IN (12, 34)
     * $query->filterByAircraftId(array('min' => 12)); // WHERE aircraft_id > 12
     * </code>
     *
     * @see       filterByAircraft()
     *
     * @param     mixed $aircraftId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByAircraftId($aircraftId = null, $comparison = null)
    {
        if (is_array($aircraftId)) {
            $useMinMax = false;
            if (isset($aircraftId['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_AIRCRAFT_ID, $aircraftId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($aircraftId['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_AIRCRAFT_ID, $aircraftId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_AIRCRAFT_ID, $aircraftId, $comparison);
    }

    /**
     * Filter the query on the destination_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDestinationId(1234); // WHERE destination_id = 1234
     * $query->filterByDestinationId(array(12, 34)); // WHERE destination_id IN (12, 34)
     * $query->filterByDestinationId(array('min' => 12)); // WHERE destination_id > 12
     * </code>
     *
     * @see       filterByDestination()
     *
     * @param     mixed $destinationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByDestinationId($destinationId = null, $comparison = null)
    {
        if (is_array($destinationId)) {
            $useMinMax = false;
            if (isset($destinationId['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_DESTINATION_ID, $destinationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($destinationId['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_DESTINATION_ID, $destinationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_DESTINATION_ID, $destinationId, $comparison);
    }

    /**
     * Filter the query on the departure_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDepartureId(1234); // WHERE departure_id = 1234
     * $query->filterByDepartureId(array(12, 34)); // WHERE departure_id IN (12, 34)
     * $query->filterByDepartureId(array('min' => 12)); // WHERE departure_id > 12
     * </code>
     *
     * @see       filterByDeparture()
     *
     * @param     mixed $departureId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByDepartureId($departureId = null, $comparison = null)
    {
        if (is_array($departureId)) {
            $useMinMax = false;
            if (isset($departureId['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_DEPARTURE_ID, $departureId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($departureId['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_DEPARTURE_ID, $departureId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_DEPARTURE_ID, $departureId, $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPilotId($pilotId = null, $comparison = null)
    {
        if (is_array($pilotId)) {
            $useMinMax = false;
            if (isset($pilotId['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_PILOT_ID, $pilotId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pilotId['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_PILOT_ID, $pilotId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_PILOT_ID, $pilotId, $comparison);
    }

    /**
     * Filter the query on the flight_number column
     *
     * Example usage:
     * <code>
     * $query->filterByFlightNumber('fooValue');   // WHERE flight_number = 'fooValue'
     * $query->filterByFlightNumber('%fooValue%'); // WHERE flight_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $flightNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByFlightNumber($flightNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($flightNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $flightNumber)) {
                $flightNumber = str_replace('*', '%', $flightNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_FLIGHT_NUMBER, $flightNumber, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * @param     mixed $status The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        $valueSet = FlightTableMap::getValueSet(FlightTableMap::COL_STATUS);
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

        return $this->addUsingAlias(FlightTableMap::COL_STATUS, $status, $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPackages($packages = null, $comparison = null)
    {
        if (is_array($packages)) {
            $useMinMax = false;
            if (isset($packages['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_PACKAGES, $packages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($packages['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_PACKAGES, $packages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_PACKAGES, $packages, $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPost($post = null, $comparison = null)
    {
        if (is_array($post)) {
            $useMinMax = false;
            if (isset($post['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_POST, $post['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($post['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_POST, $post['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_POST, $post, $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPassengerLow($passengerLow = null, $comparison = null)
    {
        if (is_array($passengerLow)) {
            $useMinMax = false;
            if (isset($passengerLow['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_PASSENGER_LOW, $passengerLow['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerLow['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_PASSENGER_LOW, $passengerLow['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_PASSENGER_LOW, $passengerLow, $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPassengerMid($passengerMid = null, $comparison = null)
    {
        if (is_array($passengerMid)) {
            $useMinMax = false;
            if (isset($passengerMid['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_PASSENGER_MID, $passengerMid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerMid['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_PASSENGER_MID, $passengerMid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_PASSENGER_MID, $passengerMid, $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPassengerHigh($passengerHigh = null, $comparison = null)
    {
        if (is_array($passengerHigh)) {
            $useMinMax = false;
            if (isset($passengerHigh['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_PASSENGER_HIGH, $passengerHigh['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerHigh['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_PASSENGER_HIGH, $passengerHigh['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_PASSENGER_HIGH, $passengerHigh, $comparison);
    }

    /**
     * Filter the query on the flight_started_at column
     *
     * Example usage:
     * <code>
     * $query->filterByFlightStartedAt('2011-03-14'); // WHERE flight_started_at = '2011-03-14'
     * $query->filterByFlightStartedAt('now'); // WHERE flight_started_at = '2011-03-14'
     * $query->filterByFlightStartedAt(array('max' => 'yesterday')); // WHERE flight_started_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $flightStartedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByFlightStartedAt($flightStartedAt = null, $comparison = null)
    {
        if (is_array($flightStartedAt)) {
            $useMinMax = false;
            if (isset($flightStartedAt['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_FLIGHT_STARTED_AT, $flightStartedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($flightStartedAt['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_FLIGHT_STARTED_AT, $flightStartedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_FLIGHT_STARTED_AT, $flightStartedAt, $comparison);
    }

    /**
     * Filter the query on the flight_finished_at column
     *
     * Example usage:
     * <code>
     * $query->filterByFlightFinishedAt('2011-03-14'); // WHERE flight_finished_at = '2011-03-14'
     * $query->filterByFlightFinishedAt('now'); // WHERE flight_finished_at = '2011-03-14'
     * $query->filterByFlightFinishedAt(array('max' => 'yesterday')); // WHERE flight_finished_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $flightFinishedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByFlightFinishedAt($flightFinishedAt = null, $comparison = null)
    {
        if (is_array($flightFinishedAt)) {
            $useMinMax = false;
            if (isset($flightFinishedAt['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_FLIGHT_FINISHED_AT, $flightFinishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($flightFinishedAt['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_FLIGHT_FINISHED_AT, $flightFinishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_FLIGHT_FINISHED_AT, $flightFinishedAt, $comparison);
    }

    /**
     * Filter the query on the next_step_possible_at column
     *
     * Example usage:
     * <code>
     * $query->filterByNextStepPossibleAt('2011-03-14'); // WHERE next_step_possible_at = '2011-03-14'
     * $query->filterByNextStepPossibleAt('now'); // WHERE next_step_possible_at = '2011-03-14'
     * $query->filterByNextStepPossibleAt(array('max' => 'yesterday')); // WHERE next_step_possible_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $nextStepPossibleAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByNextStepPossibleAt($nextStepPossibleAt = null, $comparison = null)
    {
        if (is_array($nextStepPossibleAt)) {
            $useMinMax = false;
            if (isset($nextStepPossibleAt['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT, $nextStepPossibleAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nextStepPossibleAt['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT, $nextStepPossibleAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT, $nextStepPossibleAt, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FlightTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FlightTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FlightTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Model\Aircraft object
     *
     * @param \Model\Aircraft|ObjectCollection $aircraft The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFlightQuery The current query, for fluid interface
     */
    public function filterByAircraft($aircraft, $comparison = null)
    {
        if ($aircraft instanceof \Model\Aircraft) {
            return $this
                ->addUsingAlias(FlightTableMap::COL_AIRCRAFT_ID, $aircraft->getId(), $comparison);
        } elseif ($aircraft instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FlightTableMap::COL_AIRCRAFT_ID, $aircraft->toKeyValue('Id', 'Id'), $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function joinAircraft($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useAircraftQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAircraft($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Aircraft', '\Model\AircraftQuery');
    }

    /**
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFlightQuery The current query, for fluid interface
     */
    public function filterByDestination($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(FlightTableMap::COL_DESTINATION_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FlightTableMap::COL_DESTINATION_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDestination() only accepts arguments of type \Model\Airport or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Destination relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function joinDestination($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Destination');

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
            $this->addJoinObject($join, 'Destination');
        }

        return $this;
    }

    /**
     * Use the Destination relation Airport object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirportQuery A secondary query class using the current class as primary query
     */
    public function useDestinationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDestination($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Destination', '\Model\AirportQuery');
    }

    /**
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFlightQuery The current query, for fluid interface
     */
    public function filterByDeparture($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(FlightTableMap::COL_DEPARTURE_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FlightTableMap::COL_DEPARTURE_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDeparture() only accepts arguments of type \Model\Airport or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Departure relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function joinDeparture($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Departure');

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
            $this->addJoinObject($join, 'Departure');
        }

        return $this;
    }

    /**
     * Use the Departure relation Airport object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirportQuery A secondary query class using the current class as primary query
     */
    public function useDepartureQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDeparture($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Departure', '\Model\AirportQuery');
    }

    /**
     * Filter the query by a related \Model\Pilot object
     *
     * @param \Model\Pilot|ObjectCollection $pilot The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFlightQuery The current query, for fluid interface
     */
    public function filterByPilot($pilot, $comparison = null)
    {
        if ($pilot instanceof \Model\Pilot) {
            return $this
                ->addUsingAlias(FlightTableMap::COL_PILOT_ID, $pilot->getId(), $comparison);
        } elseif ($pilot instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FlightTableMap::COL_PILOT_ID, $pilot->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFlightQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Freight object
     *
     * @param \Model\Freight|ObjectCollection $freight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFlightQuery The current query, for fluid interface
     */
    public function filterByFreight($freight, $comparison = null)
    {
        if ($freight instanceof \Model\Freight) {
            return $this
                ->addUsingAlias(FlightTableMap::COL_ID, $freight->getFlightId(), $comparison);
        } elseif ($freight instanceof ObjectCollection) {
            return $this
                ->useFreightQuery()
                ->filterByPrimaryKeys($freight->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFreight() only accepts arguments of type \Model\Freight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Freight relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function joinFreight($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Freight');

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
            $this->addJoinObject($join, 'Freight');
        }

        return $this;
    }

    /**
     * Use the Freight relation Freight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FreightQuery A secondary query class using the current class as primary query
     */
    public function useFreightQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFreight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Freight', '\Model\FreightQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFlight $flight Object to remove from the list of results
     *
     * @return $this|ChildFlightQuery The current query, for fluid interface
     */
    public function prune($flight = null)
    {
        if ($flight) {
            $this->addCond('pruneCond0', $this->getAliasedColName(FlightTableMap::COL_ID), $flight->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(FlightTableMap::COL_AIRCRAFT_ID), $flight->getAircraftId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(FlightTableMap::COL_DESTINATION_ID), $flight->getDestinationId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond3', $this->getAliasedColName(FlightTableMap::COL_DEPARTURE_ID), $flight->getDepartureId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond4', $this->getAliasedColName(FlightTableMap::COL_PILOT_ID), $flight->getPilotId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2', 'pruneCond3', 'pruneCond4'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the flights table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FlightTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FlightTableMap::clearInstancePool();
            FlightTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FlightTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FlightTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FlightTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FlightTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildFlightQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FlightTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildFlightQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FlightTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildFlightQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FlightTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildFlightQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FlightTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildFlightQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FlightTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildFlightQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FlightTableMap::COL_CREATED_AT);
    }

} // FlightQuery

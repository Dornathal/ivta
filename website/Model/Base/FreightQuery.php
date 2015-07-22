<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Freight as ChildFreight;
use Model\FreightQuery as ChildFreightQuery;
use Model\Map\FreightTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'freights' table.
 *
 *
 *
 * @method     ChildFreightQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFreightQuery orderByDestinationId($order = Criteria::ASC) Order by the destination_id column
 * @method     ChildFreightQuery orderByDepartureId($order = Criteria::ASC) Order by the departure_id column
 * @method     ChildFreightQuery orderByLocationId($order = Criteria::ASC) Order by the location_id column
 * @method     ChildFreightQuery orderByFlightId($order = Criteria::ASC) Order by the flight_id column
 * @method     ChildFreightQuery orderByFreightType($order = Criteria::ASC) Order by the freight_type column
 * @method     ChildFreightQuery orderByNextSteps($order = Criteria::ASC) Order by the next_steps column
 * @method     ChildFreightQuery orderByRouteFlights($order = Criteria::ASC) Order by the route_flights column
 * @method     ChildFreightQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 *
 * @method     ChildFreightQuery groupById() Group by the id column
 * @method     ChildFreightQuery groupByDestinationId() Group by the destination_id column
 * @method     ChildFreightQuery groupByDepartureId() Group by the departure_id column
 * @method     ChildFreightQuery groupByLocationId() Group by the location_id column
 * @method     ChildFreightQuery groupByFlightId() Group by the flight_id column
 * @method     ChildFreightQuery groupByFreightType() Group by the freight_type column
 * @method     ChildFreightQuery groupByNextSteps() Group by the next_steps column
 * @method     ChildFreightQuery groupByRouteFlights() Group by the route_flights column
 * @method     ChildFreightQuery groupByAmount() Group by the amount column
 *
 * @method     ChildFreightQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFreightQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFreightQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFreightQuery leftJoinDestination($relationAlias = null) Adds a LEFT JOIN clause to the query using the Destination relation
 * @method     ChildFreightQuery rightJoinDestination($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Destination relation
 * @method     ChildFreightQuery innerJoinDestination($relationAlias = null) Adds a INNER JOIN clause to the query using the Destination relation
 *
 * @method     ChildFreightQuery leftJoinDeparture($relationAlias = null) Adds a LEFT JOIN clause to the query using the Departure relation
 * @method     ChildFreightQuery rightJoinDeparture($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Departure relation
 * @method     ChildFreightQuery innerJoinDeparture($relationAlias = null) Adds a INNER JOIN clause to the query using the Departure relation
 *
 * @method     ChildFreightQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method     ChildFreightQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method     ChildFreightQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method     ChildFreightQuery leftJoinOnFlight($relationAlias = null) Adds a LEFT JOIN clause to the query using the OnFlight relation
 * @method     ChildFreightQuery rightJoinOnFlight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OnFlight relation
 * @method     ChildFreightQuery innerJoinOnFlight($relationAlias = null) Adds a INNER JOIN clause to the query using the OnFlight relation
 *
 * @method     \Model\AirportQuery|\Model\FlightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFreight findOne(ConnectionInterface $con = null) Return the first ChildFreight matching the query
 * @method     ChildFreight findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFreight matching the query, or a new ChildFreight object populated from the query conditions when no match is found
 *
 * @method     ChildFreight findOneById(int $id) Return the first ChildFreight filtered by the id column
 * @method     ChildFreight findOneByDestinationId(int $destination_id) Return the first ChildFreight filtered by the destination_id column
 * @method     ChildFreight findOneByDepartureId(int $departure_id) Return the first ChildFreight filtered by the departure_id column
 * @method     ChildFreight findOneByLocationId(int $location_id) Return the first ChildFreight filtered by the location_id column
 * @method     ChildFreight findOneByFlightId(int $flight_id) Return the first ChildFreight filtered by the flight_id column
 * @method     ChildFreight findOneByFreightType(int $freight_type) Return the first ChildFreight filtered by the freight_type column
 * @method     ChildFreight findOneByNextSteps(array $next_steps) Return the first ChildFreight filtered by the next_steps column
 * @method     ChildFreight findOneByRouteFlights(array $route_flights) Return the first ChildFreight filtered by the route_flights column
 * @method     ChildFreight findOneByAmount(int $amount) Return the first ChildFreight filtered by the amount column *

 * @method     ChildFreight requirePk($key, ConnectionInterface $con = null) Return the ChildFreight by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOne(ConnectionInterface $con = null) Return the first ChildFreight matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFreight requireOneById(int $id) Return the first ChildFreight filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByDestinationId(int $destination_id) Return the first ChildFreight filtered by the destination_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByDepartureId(int $departure_id) Return the first ChildFreight filtered by the departure_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByLocationId(int $location_id) Return the first ChildFreight filtered by the location_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByFlightId(int $flight_id) Return the first ChildFreight filtered by the flight_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByFreightType(int $freight_type) Return the first ChildFreight filtered by the freight_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByNextSteps(array $next_steps) Return the first ChildFreight filtered by the next_steps column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByRouteFlights(array $route_flights) Return the first ChildFreight filtered by the route_flights column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreight requireOneByAmount(int $amount) Return the first ChildFreight filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFreight[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFreight objects based on current ModelCriteria
 * @method     ChildFreight[]|ObjectCollection findById(int $id) Return ChildFreight objects filtered by the id column
 * @method     ChildFreight[]|ObjectCollection findByDestinationId(int $destination_id) Return ChildFreight objects filtered by the destination_id column
 * @method     ChildFreight[]|ObjectCollection findByDepartureId(int $departure_id) Return ChildFreight objects filtered by the departure_id column
 * @method     ChildFreight[]|ObjectCollection findByLocationId(int $location_id) Return ChildFreight objects filtered by the location_id column
 * @method     ChildFreight[]|ObjectCollection findByFlightId(int $flight_id) Return ChildFreight objects filtered by the flight_id column
 * @method     ChildFreight[]|ObjectCollection findByFreightType(int $freight_type) Return ChildFreight objects filtered by the freight_type column
 * @method     ChildFreight[]|ObjectCollection findByNextSteps(array $next_steps) Return ChildFreight objects filtered by the next_steps column
 * @method     ChildFreight[]|ObjectCollection findByRouteFlights(array $route_flights) Return ChildFreight objects filtered by the route_flights column
 * @method     ChildFreight[]|ObjectCollection findByAmount(int $amount) Return ChildFreight objects filtered by the amount column
 * @method     ChildFreight[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FreightQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\FreightQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Freight', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFreightQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFreightQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFreightQuery) {
            return $criteria;
        }
        $query = new ChildFreightQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$id, $destination_id, $departure_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFreight|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FreightTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FreightTableMap::DATABASE_NAME);
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
     * @return ChildFreight A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, destination_id, departure_id, location_id, flight_id, freight_type, next_steps, route_flights, amount FROM freights WHERE id = :p0 AND destination_id = :p1 AND departure_id = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFreight $obj */
            $obj = new ChildFreight();
            $obj->hydrate($row);
            FreightTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ChildFreight|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(FreightTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(FreightTableMap::COL_DESTINATION_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(FreightTableMap::COL_DEPARTURE_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(FreightTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(FreightTableMap::COL_DESTINATION_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(FreightTableMap::COL_DEPARTURE_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
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
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FreightTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FreightTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByDestinationId($destinationId = null, $comparison = null)
    {
        if (is_array($destinationId)) {
            $useMinMax = false;
            if (isset($destinationId['min'])) {
                $this->addUsingAlias(FreightTableMap::COL_DESTINATION_ID, $destinationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($destinationId['max'])) {
                $this->addUsingAlias(FreightTableMap::COL_DESTINATION_ID, $destinationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_DESTINATION_ID, $destinationId, $comparison);
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
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByDepartureId($departureId = null, $comparison = null)
    {
        if (is_array($departureId)) {
            $useMinMax = false;
            if (isset($departureId['min'])) {
                $this->addUsingAlias(FreightTableMap::COL_DEPARTURE_ID, $departureId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($departureId['max'])) {
                $this->addUsingAlias(FreightTableMap::COL_DEPARTURE_ID, $departureId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_DEPARTURE_ID, $departureId, $comparison);
    }

    /**
     * Filter the query on the location_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationId(1234); // WHERE location_id = 1234
     * $query->filterByLocationId(array(12, 34)); // WHERE location_id IN (12, 34)
     * $query->filterByLocationId(array('min' => 12)); // WHERE location_id > 12
     * </code>
     *
     * @see       filterByLocation()
     *
     * @param     mixed $locationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByLocationId($locationId = null, $comparison = null)
    {
        if (is_array($locationId)) {
            $useMinMax = false;
            if (isset($locationId['min'])) {
                $this->addUsingAlias(FreightTableMap::COL_LOCATION_ID, $locationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationId['max'])) {
                $this->addUsingAlias(FreightTableMap::COL_LOCATION_ID, $locationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_LOCATION_ID, $locationId, $comparison);
    }

    /**
     * Filter the query on the flight_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFlightId(1234); // WHERE flight_id = 1234
     * $query->filterByFlightId(array(12, 34)); // WHERE flight_id IN (12, 34)
     * $query->filterByFlightId(array('min' => 12)); // WHERE flight_id > 12
     * </code>
     *
     * @see       filterByOnFlight()
     *
     * @param     mixed $flightId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByFlightId($flightId = null, $comparison = null)
    {
        if (is_array($flightId)) {
            $useMinMax = false;
            if (isset($flightId['min'])) {
                $this->addUsingAlias(FreightTableMap::COL_FLIGHT_ID, $flightId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($flightId['max'])) {
                $this->addUsingAlias(FreightTableMap::COL_FLIGHT_ID, $flightId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_FLIGHT_ID, $flightId, $comparison);
    }

    /**
     * Filter the query on the freight_type column
     *
     * @param     mixed $freightType The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByFreightType($freightType = null, $comparison = null)
    {
        $valueSet = FreightTableMap::getValueSet(FreightTableMap::COL_FREIGHT_TYPE);
        if (is_scalar($freightType)) {
            if (!in_array($freightType, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $freightType));
            }
            $freightType = array_search($freightType, $valueSet);
        } elseif (is_array($freightType)) {
            $convertedValues = array();
            foreach ($freightType as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $freightType = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_FREIGHT_TYPE, $freightType, $comparison);
    }

    /**
     * Filter the query on the next_steps column
     *
     * @param     array $nextSteps The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByNextSteps($nextSteps = null, $comparison = null)
    {
        $key = $this->getAliasedColName(FreightTableMap::COL_NEXT_STEPS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($nextSteps as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($nextSteps as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($nextSteps as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(FreightTableMap::COL_NEXT_STEPS, $nextSteps, $comparison);
    }

    /**
     * Filter the query on the next_steps column
     * @param     mixed $nextSteps The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByNextStep($nextSteps = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($nextSteps)) {
                $nextSteps = '%| ' . $nextSteps . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $nextSteps = '%| ' . $nextSteps . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(FreightTableMap::COL_NEXT_STEPS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $nextSteps, $comparison);
            } else {
                $this->addAnd($key, $nextSteps, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(FreightTableMap::COL_NEXT_STEPS, $nextSteps, $comparison);
    }

    /**
     * Filter the query on the route_flights column
     *
     * @param     array $routeFlights The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByRouteFlights($routeFlights = null, $comparison = null)
    {
        $key = $this->getAliasedColName(FreightTableMap::COL_ROUTE_FLIGHTS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($routeFlights as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($routeFlights as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($routeFlights as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(FreightTableMap::COL_ROUTE_FLIGHTS, $routeFlights, $comparison);
    }

    /**
     * Filter the query on the route_flights column
     * @param     mixed $routeFlights The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByRouteFlight($routeFlights = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($routeFlights)) {
                $routeFlights = '%| ' . $routeFlights . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $routeFlights = '%| ' . $routeFlights . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(FreightTableMap::COL_ROUTE_FLIGHTS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $routeFlights, $comparison);
            } else {
                $this->addAnd($key, $routeFlights, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(FreightTableMap::COL_ROUTE_FLIGHTS, $routeFlights, $comparison);
    }

    /**
     * Filter the query on the amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(FreightTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(FreightTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFreightQuery The current query, for fluid interface
     */
    public function filterByDestination($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(FreightTableMap::COL_DESTINATION_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FreightTableMap::COL_DESTINATION_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFreightQuery The current query, for fluid interface
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
     * @return ChildFreightQuery The current query, for fluid interface
     */
    public function filterByDeparture($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(FreightTableMap::COL_DEPARTURE_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FreightTableMap::COL_DEPARTURE_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFreightQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFreightQuery The current query, for fluid interface
     */
    public function filterByLocation($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(FreightTableMap::COL_LOCATION_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FreightTableMap::COL_LOCATION_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLocation() only accepts arguments of type \Model\Airport or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Location relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function joinLocation($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Location');

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
            $this->addJoinObject($join, 'Location');
        }

        return $this;
    }

    /**
     * Use the Location relation Airport object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AirportQuery A secondary query class using the current class as primary query
     */
    public function useLocationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Location', '\Model\AirportQuery');
    }

    /**
     * Filter the query by a related \Model\Flight object
     *
     * @param \Model\Flight|ObjectCollection $flight The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFreightQuery The current query, for fluid interface
     */
    public function filterByOnFlight($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(FreightTableMap::COL_FLIGHT_ID, $flight->getId(), $comparison);
        } elseif ($flight instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FreightTableMap::COL_FLIGHT_ID, $flight->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOnFlight() only accepts arguments of type \Model\Flight or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OnFlight relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function joinOnFlight($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OnFlight');

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
            $this->addJoinObject($join, 'OnFlight');
        }

        return $this;
    }

    /**
     * Use the OnFlight relation Flight object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FlightQuery A secondary query class using the current class as primary query
     */
    public function useOnFlightQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOnFlight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OnFlight', '\Model\FlightQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFreight $freight Object to remove from the list of results
     *
     * @return $this|ChildFreightQuery The current query, for fluid interface
     */
    public function prune($freight = null)
    {
        if ($freight) {
            $this->addCond('pruneCond0', $this->getAliasedColName(FreightTableMap::COL_ID), $freight->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(FreightTableMap::COL_DESTINATION_ID), $freight->getDestinationId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(FreightTableMap::COL_DEPARTURE_ID), $freight->getDepartureId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the freights table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FreightTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FreightTableMap::clearInstancePool();
            FreightTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FreightTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FreightTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FreightTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FreightTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FreightQuery

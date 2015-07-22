<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Airway as ChildAirway;
use Model\AirwayQuery as ChildAirwayQuery;
use Model\Map\AirwayTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'airways' table.
 *
 *
 *
 * @method     ChildAirwayQuery orderByDestinationId($order = Criteria::ASC) Order by the destination_id column
 * @method     ChildAirwayQuery orderByDepartureId($order = Criteria::ASC) Order by the departure_id column
 * @method     ChildAirwayQuery orderByNextSteps($order = Criteria::ASC) Order by the next_steps column
 * @method     ChildAirwayQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildAirwayQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildAirwayQuery groupByDestinationId() Group by the destination_id column
 * @method     ChildAirwayQuery groupByDepartureId() Group by the departure_id column
 * @method     ChildAirwayQuery groupByNextSteps() Group by the next_steps column
 * @method     ChildAirwayQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildAirwayQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildAirwayQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAirwayQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAirwayQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAirwayQuery leftJoinDestination($relationAlias = null) Adds a LEFT JOIN clause to the query using the Destination relation
 * @method     ChildAirwayQuery rightJoinDestination($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Destination relation
 * @method     ChildAirwayQuery innerJoinDestination($relationAlias = null) Adds a INNER JOIN clause to the query using the Destination relation
 *
 * @method     ChildAirwayQuery leftJoinDeparture($relationAlias = null) Adds a LEFT JOIN clause to the query using the Departure relation
 * @method     ChildAirwayQuery rightJoinDeparture($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Departure relation
 * @method     ChildAirwayQuery innerJoinDeparture($relationAlias = null) Adds a INNER JOIN clause to the query using the Departure relation
 *
 * @method     \Model\AirportQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAirway findOne(ConnectionInterface $con = null) Return the first ChildAirway matching the query
 * @method     ChildAirway findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAirway matching the query, or a new ChildAirway object populated from the query conditions when no match is found
 *
 * @method     ChildAirway findOneByDestinationId(int $destination_id) Return the first ChildAirway filtered by the destination_id column
 * @method     ChildAirway findOneByDepartureId(int $departure_id) Return the first ChildAirway filtered by the departure_id column
 * @method     ChildAirway findOneByNextSteps(array $next_steps) Return the first ChildAirway filtered by the next_steps column
 * @method     ChildAirway findOneByCreatedAt(string $created_at) Return the first ChildAirway filtered by the created_at column
 * @method     ChildAirway findOneByUpdatedAt(string $updated_at) Return the first ChildAirway filtered by the updated_at column *

 * @method     ChildAirway requirePk($key, ConnectionInterface $con = null) Return the ChildAirway by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirway requireOne(ConnectionInterface $con = null) Return the first ChildAirway matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAirway requireOneByDestinationId(int $destination_id) Return the first ChildAirway filtered by the destination_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirway requireOneByDepartureId(int $departure_id) Return the first ChildAirway filtered by the departure_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirway requireOneByNextSteps(array $next_steps) Return the first ChildAirway filtered by the next_steps column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirway requireOneByCreatedAt(string $created_at) Return the first ChildAirway filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirway requireOneByUpdatedAt(string $updated_at) Return the first ChildAirway filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAirway[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAirway objects based on current ModelCriteria
 * @method     ChildAirway[]|ObjectCollection findByDestinationId(int $destination_id) Return ChildAirway objects filtered by the destination_id column
 * @method     ChildAirway[]|ObjectCollection findByDepartureId(int $departure_id) Return ChildAirway objects filtered by the departure_id column
 * @method     ChildAirway[]|ObjectCollection findByNextSteps(array $next_steps) Return ChildAirway objects filtered by the next_steps column
 * @method     ChildAirway[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildAirway objects filtered by the created_at column
 * @method     ChildAirway[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildAirway objects filtered by the updated_at column
 * @method     ChildAirway[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AirwayQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AirwayQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Airway', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAirwayQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAirwayQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAirwayQuery) {
            return $criteria;
        }
        $query = new ChildAirwayQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$destination_id, $departure_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildAirway|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AirwayTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AirwayTableMap::DATABASE_NAME);
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
     * @return ChildAirway A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT destination_id, departure_id, next_steps, created_at, updated_at FROM airways WHERE destination_id = :p0 AND departure_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildAirway $obj */
            $obj = new ChildAirway();
            $obj->hydrate($row);
            AirwayTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildAirway|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(AirwayTableMap::COL_DESTINATION_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(AirwayTableMap::COL_DEPARTURE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(AirwayTableMap::COL_DESTINATION_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(AirwayTableMap::COL_DEPARTURE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByDestinationId($destinationId = null, $comparison = null)
    {
        if (is_array($destinationId)) {
            $useMinMax = false;
            if (isset($destinationId['min'])) {
                $this->addUsingAlias(AirwayTableMap::COL_DESTINATION_ID, $destinationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($destinationId['max'])) {
                $this->addUsingAlias(AirwayTableMap::COL_DESTINATION_ID, $destinationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirwayTableMap::COL_DESTINATION_ID, $destinationId, $comparison);
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByDepartureId($departureId = null, $comparison = null)
    {
        if (is_array($departureId)) {
            $useMinMax = false;
            if (isset($departureId['min'])) {
                $this->addUsingAlias(AirwayTableMap::COL_DEPARTURE_ID, $departureId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($departureId['max'])) {
                $this->addUsingAlias(AirwayTableMap::COL_DEPARTURE_ID, $departureId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirwayTableMap::COL_DEPARTURE_ID, $departureId, $comparison);
    }

    /**
     * Filter the query on the next_steps column
     *
     * @param     array $nextSteps The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByNextSteps($nextSteps = null, $comparison = null)
    {
        $key = $this->getAliasedColName(AirwayTableMap::COL_NEXT_STEPS);
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

        return $this->addUsingAlias(AirwayTableMap::COL_NEXT_STEPS, $nextSteps, $comparison);
    }

    /**
     * Filter the query on the next_steps column
     * @param     mixed $nextSteps The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildAirwayQuery The current query, for fluid interface
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
            $key = $this->getAliasedColName(AirwayTableMap::COL_NEXT_STEPS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $nextSteps, $comparison);
            } else {
                $this->addAnd($key, $nextSteps, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(AirwayTableMap::COL_NEXT_STEPS, $nextSteps, $comparison);
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AirwayTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AirwayTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirwayTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AirwayTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AirwayTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirwayTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByDestination($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(AirwayTableMap::COL_DESTINATION_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AirwayTableMap::COL_DESTINATION_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
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
     * @return ChildAirwayQuery The current query, for fluid interface
     */
    public function filterByDeparture($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(AirwayTableMap::COL_DEPARTURE_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AirwayTableMap::COL_DEPARTURE_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildAirwayQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildAirway $airway Object to remove from the list of results
     *
     * @return $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function prune($airway = null)
    {
        if ($airway) {
            $this->addCond('pruneCond0', $this->getAliasedColName(AirwayTableMap::COL_DESTINATION_ID), $airway->getDestinationId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(AirwayTableMap::COL_DEPARTURE_ID), $airway->getDepartureId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the airways table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirwayTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AirwayTableMap::clearInstancePool();
            AirwayTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AirwayTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AirwayTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AirwayTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AirwayTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AirwayTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AirwayTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AirwayTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AirwayTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AirwayTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildAirwayQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AirwayTableMap::COL_CREATED_AT);
    }

} // AirwayQuery

<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\FreightGeneration as ChildFreightGeneration;
use Model\FreightGenerationQuery as ChildFreightGenerationQuery;
use Model\Map\FreightGenerationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'freight_generations' table.
 *
 *
 *
 * @method     ChildFreightGenerationQuery orderByAirportId($order = Criteria::ASC) Order by the airport_id column
 * @method     ChildFreightGenerationQuery orderByNextGenerationAt($order = Criteria::ASC) Order by the next_generation_at column
 * @method     ChildFreightGenerationQuery orderByNextUpdateAt($order = Criteria::ASC) Order by the next_update_at column
 * @method     ChildFreightGenerationQuery orderByCapacity($order = Criteria::ASC) Order by the capacity column
 * @method     ChildFreightGenerationQuery orderByEvery($order = Criteria::ASC) Order by the every column
 *
 * @method     ChildFreightGenerationQuery groupByAirportId() Group by the airport_id column
 * @method     ChildFreightGenerationQuery groupByNextGenerationAt() Group by the next_generation_at column
 * @method     ChildFreightGenerationQuery groupByNextUpdateAt() Group by the next_update_at column
 * @method     ChildFreightGenerationQuery groupByCapacity() Group by the capacity column
 * @method     ChildFreightGenerationQuery groupByEvery() Group by the every column
 *
 * @method     ChildFreightGenerationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFreightGenerationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFreightGenerationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFreightGenerationQuery leftJoinAirport($relationAlias = null) Adds a LEFT JOIN clause to the query using the Airport relation
 * @method     ChildFreightGenerationQuery rightJoinAirport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Airport relation
 * @method     ChildFreightGenerationQuery innerJoinAirport($relationAlias = null) Adds a INNER JOIN clause to the query using the Airport relation
 *
 * @method     \Model\AirportQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFreightGeneration findOne(ConnectionInterface $con = null) Return the first ChildFreightGeneration matching the query
 * @method     ChildFreightGeneration findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFreightGeneration matching the query, or a new ChildFreightGeneration object populated from the query conditions when no match is found
 *
 * @method     ChildFreightGeneration findOneByAirportId(int $airport_id) Return the first ChildFreightGeneration filtered by the airport_id column
 * @method     ChildFreightGeneration findOneByNextGenerationAt(string $next_generation_at) Return the first ChildFreightGeneration filtered by the next_generation_at column
 * @method     ChildFreightGeneration findOneByNextUpdateAt(string $next_update_at) Return the first ChildFreightGeneration filtered by the next_update_at column
 * @method     ChildFreightGeneration findOneByCapacity(int $capacity) Return the first ChildFreightGeneration filtered by the capacity column
 * @method     ChildFreightGeneration findOneByEvery(int $every) Return the first ChildFreightGeneration filtered by the every column *

 * @method     ChildFreightGeneration requirePk($key, ConnectionInterface $con = null) Return the ChildFreightGeneration by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreightGeneration requireOne(ConnectionInterface $con = null) Return the first ChildFreightGeneration matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFreightGeneration requireOneByAirportId(int $airport_id) Return the first ChildFreightGeneration filtered by the airport_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreightGeneration requireOneByNextGenerationAt(string $next_generation_at) Return the first ChildFreightGeneration filtered by the next_generation_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreightGeneration requireOneByNextUpdateAt(string $next_update_at) Return the first ChildFreightGeneration filtered by the next_update_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreightGeneration requireOneByCapacity(int $capacity) Return the first ChildFreightGeneration filtered by the capacity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFreightGeneration requireOneByEvery(int $every) Return the first ChildFreightGeneration filtered by the every column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFreightGeneration[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFreightGeneration objects based on current ModelCriteria
 * @method     ChildFreightGeneration[]|ObjectCollection findByAirportId(int $airport_id) Return ChildFreightGeneration objects filtered by the airport_id column
 * @method     ChildFreightGeneration[]|ObjectCollection findByNextGenerationAt(string $next_generation_at) Return ChildFreightGeneration objects filtered by the next_generation_at column
 * @method     ChildFreightGeneration[]|ObjectCollection findByNextUpdateAt(string $next_update_at) Return ChildFreightGeneration objects filtered by the next_update_at column
 * @method     ChildFreightGeneration[]|ObjectCollection findByCapacity(int $capacity) Return ChildFreightGeneration objects filtered by the capacity column
 * @method     ChildFreightGeneration[]|ObjectCollection findByEvery(int $every) Return ChildFreightGeneration objects filtered by the every column
 * @method     ChildFreightGeneration[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FreightGenerationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\FreightGenerationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\FreightGeneration', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFreightGenerationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFreightGenerationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFreightGenerationQuery) {
            return $criteria;
        }
        $query = new ChildFreightGenerationQuery();
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
     * @return ChildFreightGeneration|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FreightGenerationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FreightGenerationTableMap::DATABASE_NAME);
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
     * @return ChildFreightGeneration A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT airport_id, next_generation_at, next_update_at, capacity, every FROM freight_generations WHERE airport_id = :p0';
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
            /** @var ChildFreightGeneration $obj */
            $obj = new ChildFreightGeneration();
            $obj->hydrate($row);
            FreightGenerationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildFreightGeneration|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $keys, Criteria::IN);
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
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByAirportId($airportId = null, $comparison = null)
    {
        if (is_array($airportId)) {
            $useMinMax = false;
            if (isset($airportId['min'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $airportId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($airportId['max'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $airportId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $airportId, $comparison);
    }

    /**
     * Filter the query on the next_generation_at column
     *
     * Example usage:
     * <code>
     * $query->filterByNextGenerationAt('2011-03-14'); // WHERE next_generation_at = '2011-03-14'
     * $query->filterByNextGenerationAt('now'); // WHERE next_generation_at = '2011-03-14'
     * $query->filterByNextGenerationAt(array('max' => 'yesterday')); // WHERE next_generation_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $nextGenerationAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByNextGenerationAt($nextGenerationAt = null, $comparison = null)
    {
        if (is_array($nextGenerationAt)) {
            $useMinMax = false;
            if (isset($nextGenerationAt['min'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_NEXT_GENERATION_AT, $nextGenerationAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nextGenerationAt['max'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_NEXT_GENERATION_AT, $nextGenerationAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightGenerationTableMap::COL_NEXT_GENERATION_AT, $nextGenerationAt, $comparison);
    }

    /**
     * Filter the query on the next_update_at column
     *
     * Example usage:
     * <code>
     * $query->filterByNextUpdateAt('2011-03-14'); // WHERE next_update_at = '2011-03-14'
     * $query->filterByNextUpdateAt('now'); // WHERE next_update_at = '2011-03-14'
     * $query->filterByNextUpdateAt(array('max' => 'yesterday')); // WHERE next_update_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $nextUpdateAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByNextUpdateAt($nextUpdateAt = null, $comparison = null)
    {
        if (is_array($nextUpdateAt)) {
            $useMinMax = false;
            if (isset($nextUpdateAt['min'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_NEXT_UPDATE_AT, $nextUpdateAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nextUpdateAt['max'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_NEXT_UPDATE_AT, $nextUpdateAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightGenerationTableMap::COL_NEXT_UPDATE_AT, $nextUpdateAt, $comparison);
    }

    /**
     * Filter the query on the capacity column
     *
     * Example usage:
     * <code>
     * $query->filterByCapacity(1234); // WHERE capacity = 1234
     * $query->filterByCapacity(array(12, 34)); // WHERE capacity IN (12, 34)
     * $query->filterByCapacity(array('min' => 12)); // WHERE capacity > 12
     * </code>
     *
     * @param     mixed $capacity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByCapacity($capacity = null, $comparison = null)
    {
        if (is_array($capacity)) {
            $useMinMax = false;
            if (isset($capacity['min'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_CAPACITY, $capacity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($capacity['max'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_CAPACITY, $capacity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightGenerationTableMap::COL_CAPACITY, $capacity, $comparison);
    }

    /**
     * Filter the query on the every column
     *
     * Example usage:
     * <code>
     * $query->filterByEvery(1234); // WHERE every = 1234
     * $query->filterByEvery(array(12, 34)); // WHERE every IN (12, 34)
     * $query->filterByEvery(array('min' => 12)); // WHERE every > 12
     * </code>
     *
     * @param     mixed $every The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByEvery($every = null, $comparison = null)
    {
        if (is_array($every)) {
            $useMinMax = false;
            if (isset($every['min'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_EVERY, $every['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($every['max'])) {
                $this->addUsingAlias(FreightGenerationTableMap::COL_EVERY, $every['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FreightGenerationTableMap::COL_EVERY, $every, $comparison);
    }

    /**
     * Filter the query by a related \Model\Airport object
     *
     * @param \Model\Airport|ObjectCollection $airport The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function filterByAirport($airport, $comparison = null)
    {
        if ($airport instanceof \Model\Airport) {
            return $this
                ->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $airport->getId(), $comparison);
        } elseif ($airport instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $airport->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function joinAirport($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useAirportQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAirport($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Airport', '\Model\AirportQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFreightGeneration $freightGeneration Object to remove from the list of results
     *
     * @return $this|ChildFreightGenerationQuery The current query, for fluid interface
     */
    public function prune($freightGeneration = null)
    {
        if ($freightGeneration) {
            $this->addUsingAlias(FreightGenerationTableMap::COL_AIRPORT_ID, $freightGeneration->getAirportId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the freight_generations table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FreightGenerationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FreightGenerationTableMap::clearInstancePool();
            FreightGenerationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FreightGenerationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FreightGenerationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FreightGenerationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FreightGenerationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FreightGenerationQuery

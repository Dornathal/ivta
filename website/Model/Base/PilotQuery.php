<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Pilot as ChildPilot;
use Model\PilotQuery as ChildPilotQuery;
use Model\Map\PilotTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'pilots' table.
 *
 *
 *
 * @method     ChildPilotQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPilotQuery orderByAirlineId($order = Criteria::ASC) Order by the airline_id column
 * @method     ChildPilotQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPilotQuery orderByToken($order = Criteria::ASC) Order by the token column
 * @method     ChildPilotQuery orderByRank($order = Criteria::ASC) Order by the rank column
 * @method     ChildPilotQuery orderBySaldo($order = Criteria::ASC) Order by the saldo column
 *
 * @method     ChildPilotQuery groupById() Group by the id column
 * @method     ChildPilotQuery groupByAirlineId() Group by the airline_id column
 * @method     ChildPilotQuery groupByName() Group by the name column
 * @method     ChildPilotQuery groupByToken() Group by the token column
 * @method     ChildPilotQuery groupByRank() Group by the rank column
 * @method     ChildPilotQuery groupBySaldo() Group by the saldo column
 *
 * @method     ChildPilotQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPilotQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPilotQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPilotQuery leftJoinAirline($relationAlias = null) Adds a LEFT JOIN clause to the query using the Airline relation
 * @method     ChildPilotQuery rightJoinAirline($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Airline relation
 * @method     ChildPilotQuery innerJoinAirline($relationAlias = null) Adds a INNER JOIN clause to the query using the Airline relation
 *
 * @method     ChildPilotQuery leftJoinFlight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Flight relation
 * @method     ChildPilotQuery rightJoinFlight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Flight relation
 * @method     ChildPilotQuery innerJoinFlight($relationAlias = null) Adds a INNER JOIN clause to the query using the Flight relation
 *
 * @method     \Model\AirlineQuery|\Model\FlightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPilot findOne(ConnectionInterface $con = null) Return the first ChildPilot matching the query
 * @method     ChildPilot findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPilot matching the query, or a new ChildPilot object populated from the query conditions when no match is found
 *
 * @method     ChildPilot findOneById(int $id) Return the first ChildPilot filtered by the id column
 * @method     ChildPilot findOneByAirlineId(int $airline_id) Return the first ChildPilot filtered by the airline_id column
 * @method     ChildPilot findOneByName(string $name) Return the first ChildPilot filtered by the name column
 * @method     ChildPilot findOneByToken(string $token) Return the first ChildPilot filtered by the token column
 * @method     ChildPilot findOneByRank(int $rank) Return the first ChildPilot filtered by the rank column
 * @method     ChildPilot findOneBySaldo(int $saldo) Return the first ChildPilot filtered by the saldo column *

 * @method     ChildPilot requirePk($key, ConnectionInterface $con = null) Return the ChildPilot by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPilot requireOne(ConnectionInterface $con = null) Return the first ChildPilot matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPilot requireOneById(int $id) Return the first ChildPilot filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPilot requireOneByAirlineId(int $airline_id) Return the first ChildPilot filtered by the airline_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPilot requireOneByName(string $name) Return the first ChildPilot filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPilot requireOneByToken(string $token) Return the first ChildPilot filtered by the token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPilot requireOneByRank(int $rank) Return the first ChildPilot filtered by the rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPilot requireOneBySaldo(int $saldo) Return the first ChildPilot filtered by the saldo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPilot[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPilot objects based on current ModelCriteria
 * @method     ChildPilot[]|ObjectCollection findById(int $id) Return ChildPilot objects filtered by the id column
 * @method     ChildPilot[]|ObjectCollection findByAirlineId(int $airline_id) Return ChildPilot objects filtered by the airline_id column
 * @method     ChildPilot[]|ObjectCollection findByName(string $name) Return ChildPilot objects filtered by the name column
 * @method     ChildPilot[]|ObjectCollection findByToken(string $token) Return ChildPilot objects filtered by the token column
 * @method     ChildPilot[]|ObjectCollection findByRank(int $rank) Return ChildPilot objects filtered by the rank column
 * @method     ChildPilot[]|ObjectCollection findBySaldo(int $saldo) Return ChildPilot objects filtered by the saldo column
 * @method     ChildPilot[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PilotQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PilotQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Pilot', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPilotQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPilotQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPilotQuery) {
            return $criteria;
        }
        $query = new ChildPilotQuery();
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
     * @return ChildPilot|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PilotTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PilotTableMap::DATABASE_NAME);
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
     * @return ChildPilot A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, airline_id, name, token, rank, saldo FROM pilots WHERE id = :p0';
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
            /** @var ChildPilot $obj */
            $obj = new ChildPilot();
            $obj->hydrate($row);
            PilotTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPilot|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PilotTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PilotTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PilotTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PilotTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PilotTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterByAirlineId($airlineId = null, $comparison = null)
    {
        if (is_array($airlineId)) {
            $useMinMax = false;
            if (isset($airlineId['min'])) {
                $this->addUsingAlias(PilotTableMap::COL_AIRLINE_ID, $airlineId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($airlineId['max'])) {
                $this->addUsingAlias(PilotTableMap::COL_AIRLINE_ID, $airlineId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PilotTableMap::COL_AIRLINE_ID, $airlineId, $comparison);
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
     * @return $this|ChildPilotQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PilotTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the token column
     *
     * Example usage:
     * <code>
     * $query->filterByToken('fooValue');   // WHERE token = 'fooValue'
     * $query->filterByToken('%fooValue%'); // WHERE token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $token The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterByToken($token = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($token)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $token)) {
                $token = str_replace('*', '%', $token);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PilotTableMap::COL_TOKEN, $token, $comparison);
    }

    /**
     * Filter the query on the rank column
     *
     * @param     mixed $rank The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterByRank($rank = null, $comparison = null)
    {
        $valueSet = PilotTableMap::getValueSet(PilotTableMap::COL_RANK);
        if (is_scalar($rank)) {
            if (!in_array($rank, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $rank));
            }
            $rank = array_search($rank, $valueSet);
        } elseif (is_array($rank)) {
            $convertedValues = array();
            foreach ($rank as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $rank = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PilotTableMap::COL_RANK, $rank, $comparison);
    }

    /**
     * Filter the query on the saldo column
     *
     * Example usage:
     * <code>
     * $query->filterBySaldo(1234); // WHERE saldo = 1234
     * $query->filterBySaldo(array(12, 34)); // WHERE saldo IN (12, 34)
     * $query->filterBySaldo(array('min' => 12)); // WHERE saldo > 12
     * </code>
     *
     * @param     mixed $saldo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function filterBySaldo($saldo = null, $comparison = null)
    {
        if (is_array($saldo)) {
            $useMinMax = false;
            if (isset($saldo['min'])) {
                $this->addUsingAlias(PilotTableMap::COL_SALDO, $saldo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($saldo['max'])) {
                $this->addUsingAlias(PilotTableMap::COL_SALDO, $saldo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PilotTableMap::COL_SALDO, $saldo, $comparison);
    }

    /**
     * Filter the query by a related \Model\Airline object
     *
     * @param \Model\Airline|ObjectCollection $airline The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPilotQuery The current query, for fluid interface
     */
    public function filterByAirline($airline, $comparison = null)
    {
        if ($airline instanceof \Model\Airline) {
            return $this
                ->addUsingAlias(PilotTableMap::COL_AIRLINE_ID, $airline->getId(), $comparison);
        } elseif ($airline instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PilotTableMap::COL_AIRLINE_ID, $airline->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function joinAirline($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useAirlineQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAirline($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Airline', '\Model\AirlineQuery');
    }

    /**
     * Filter the query by a related \Model\Flight object
     *
     * @param \Model\Flight|ObjectCollection $flight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPilotQuery The current query, for fluid interface
     */
    public function filterByFlight($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(PilotTableMap::COL_ID, $flight->getPilotId(), $comparison);
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
     * @return $this|ChildPilotQuery The current query, for fluid interface
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
     * @param   ChildPilot $pilot Object to remove from the list of results
     *
     * @return $this|ChildPilotQuery The current query, for fluid interface
     */
    public function prune($pilot = null)
    {
        if ($pilot) {
            $this->addUsingAlias(PilotTableMap::COL_ID, $pilot->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pilots table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PilotTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PilotTableMap::clearInstancePool();
            PilotTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PilotTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PilotTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PilotTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PilotTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PilotQuery

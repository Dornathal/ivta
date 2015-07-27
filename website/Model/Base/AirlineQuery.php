<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Airline as ChildAirline;
use Model\AirlineQuery as ChildAirlineQuery;
use Model\Map\AirlineTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'airlines' table.
 *
 *
 *
 * @method     ChildAirlineQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAirlineQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildAirlineQuery orderByAlias($order = Criteria::ASC) Order by the alias column
 * @method     ChildAirlineQuery orderByIATA($order = Criteria::ASC) Order by the iata column
 * @method     ChildAirlineQuery orderByICAO($order = Criteria::ASC) Order by the icao column
 * @method     ChildAirlineQuery orderByCallsign($order = Criteria::ASC) Order by the callsign column
 * @method     ChildAirlineQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method     ChildAirlineQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildAirlineQuery orderBySaldo($order = Criteria::ASC) Order by the saldo column
 *
 * @method     ChildAirlineQuery groupById() Group by the id column
 * @method     ChildAirlineQuery groupByName() Group by the name column
 * @method     ChildAirlineQuery groupByAlias() Group by the alias column
 * @method     ChildAirlineQuery groupByIATA() Group by the iata column
 * @method     ChildAirlineQuery groupByICAO() Group by the icao column
 * @method     ChildAirlineQuery groupByCallsign() Group by the callsign column
 * @method     ChildAirlineQuery groupByCountry() Group by the country column
 * @method     ChildAirlineQuery groupByActive() Group by the active column
 * @method     ChildAirlineQuery groupBySaldo() Group by the saldo column
 *
 * @method     ChildAirlineQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAirlineQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAirlineQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAirlineQuery leftJoinAircraft($relationAlias = null) Adds a LEFT JOIN clause to the query using the Aircraft relation
 * @method     ChildAirlineQuery rightJoinAircraft($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Aircraft relation
 * @method     ChildAirlineQuery innerJoinAircraft($relationAlias = null) Adds a INNER JOIN clause to the query using the Aircraft relation
 *
 * @method     ChildAirlineQuery leftJoinFlight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Flight relation
 * @method     ChildAirlineQuery rightJoinFlight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Flight relation
 * @method     ChildAirlineQuery innerJoinFlight($relationAlias = null) Adds a INNER JOIN clause to the query using the Flight relation
 *
 * @method     ChildAirlineQuery leftJoinPilot($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pilot relation
 * @method     ChildAirlineQuery rightJoinPilot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pilot relation
 * @method     ChildAirlineQuery innerJoinPilot($relationAlias = null) Adds a INNER JOIN clause to the query using the Pilot relation
 *
 * @method     \Model\AircraftQuery|\Model\FlightQuery|\Model\PilotQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAirline findOne(ConnectionInterface $con = null) Return the first ChildAirline matching the query
 * @method     ChildAirline findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAirline matching the query, or a new ChildAirline object populated from the query conditions when no match is found
 *
 * @method     ChildAirline findOneById(int $id) Return the first ChildAirline filtered by the id column
 * @method     ChildAirline findOneByName(string $name) Return the first ChildAirline filtered by the name column
 * @method     ChildAirline findOneByAlias(string $alias) Return the first ChildAirline filtered by the alias column
 * @method     ChildAirline findOneByIATA(string $iata) Return the first ChildAirline filtered by the iata column
 * @method     ChildAirline findOneByICAO(string $icao) Return the first ChildAirline filtered by the icao column
 * @method     ChildAirline findOneByCallsign(string $callsign) Return the first ChildAirline filtered by the callsign column
 * @method     ChildAirline findOneByCountry(string $country) Return the first ChildAirline filtered by the country column
 * @method     ChildAirline findOneByActive(boolean $active) Return the first ChildAirline filtered by the active column
 * @method     ChildAirline findOneBySaldo(int $saldo) Return the first ChildAirline filtered by the saldo column *

 * @method     ChildAirline requirePk($key, ConnectionInterface $con = null) Return the ChildAirline by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOne(ConnectionInterface $con = null) Return the first ChildAirline matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAirline requireOneById(int $id) Return the first ChildAirline filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByName(string $name) Return the first ChildAirline filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByAlias(string $alias) Return the first ChildAirline filtered by the alias column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByIATA(string $iata) Return the first ChildAirline filtered by the iata column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByICAO(string $icao) Return the first ChildAirline filtered by the icao column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByCallsign(string $callsign) Return the first ChildAirline filtered by the callsign column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByCountry(string $country) Return the first ChildAirline filtered by the country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneByActive(boolean $active) Return the first ChildAirline filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAirline requireOneBySaldo(int $saldo) Return the first ChildAirline filtered by the saldo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAirline[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAirline objects based on current ModelCriteria
 * @method     ChildAirline[]|ObjectCollection findById(int $id) Return ChildAirline objects filtered by the id column
 * @method     ChildAirline[]|ObjectCollection findByName(string $name) Return ChildAirline objects filtered by the name column
 * @method     ChildAirline[]|ObjectCollection findByAlias(string $alias) Return ChildAirline objects filtered by the alias column
 * @method     ChildAirline[]|ObjectCollection findByIATA(string $iata) Return ChildAirline objects filtered by the iata column
 * @method     ChildAirline[]|ObjectCollection findByICAO(string $icao) Return ChildAirline objects filtered by the icao column
 * @method     ChildAirline[]|ObjectCollection findByCallsign(string $callsign) Return ChildAirline objects filtered by the callsign column
 * @method     ChildAirline[]|ObjectCollection findByCountry(string $country) Return ChildAirline objects filtered by the country column
 * @method     ChildAirline[]|ObjectCollection findByActive(boolean $active) Return ChildAirline objects filtered by the active column
 * @method     ChildAirline[]|ObjectCollection findBySaldo(int $saldo) Return ChildAirline objects filtered by the saldo column
 * @method     ChildAirline[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AirlineQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AirlineQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Airline', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAirlineQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAirlineQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAirlineQuery) {
            return $criteria;
        }
        $query = new ChildAirlineQuery();
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
     * @return ChildAirline|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AirlineTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AirlineTableMap::DATABASE_NAME);
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
     * @return ChildAirline A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, alias, iata, icao, callsign, country, active, saldo FROM airlines WHERE id = :p0';
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
            /** @var ChildAirline $obj */
            $obj = new ChildAirline();
            $obj->hydrate($row);
            AirlineTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAirline|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AirlineTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AirlineTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AirlineTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AirlineTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirlineTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AirlineTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the alias column
     *
     * Example usage:
     * <code>
     * $query->filterByAlias('fooValue');   // WHERE alias = 'fooValue'
     * $query->filterByAlias('%fooValue%'); // WHERE alias LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alias The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByAlias($alias = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alias)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alias)) {
                $alias = str_replace('*', '%', $alias);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AirlineTableMap::COL_ALIAS, $alias, $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AirlineTableMap::COL_IATA, $iATA, $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AirlineTableMap::COL_ICAO, $iCAO, $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AirlineTableMap::COL_CALLSIGN, $callsign, $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AirlineTableMap::COL_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(true); // WHERE active = true
     * $query->filterByActive('yes'); // WHERE active = true
     * </code>
     *
     * @param     boolean|string $active The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AirlineTableMap::COL_ACTIVE, $active, $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function filterBySaldo($saldo = null, $comparison = null)
    {
        if (is_array($saldo)) {
            $useMinMax = false;
            if (isset($saldo['min'])) {
                $this->addUsingAlias(AirlineTableMap::COL_SALDO, $saldo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($saldo['max'])) {
                $this->addUsingAlias(AirlineTableMap::COL_SALDO, $saldo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AirlineTableMap::COL_SALDO, $saldo, $comparison);
    }

    /**
     * Filter the query by a related \Model\Aircraft object
     *
     * @param \Model\Aircraft|ObjectCollection $aircraft the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByAircraft($aircraft, $comparison = null)
    {
        if ($aircraft instanceof \Model\Aircraft) {
            return $this
                ->addUsingAlias(AirlineTableMap::COL_ID, $aircraft->getAirlineId(), $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Flight object
     *
     * @param \Model\Flight|ObjectCollection $flight the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByFlight($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(AirlineTableMap::COL_ID, $flight->getAirlineId(), $comparison);
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Pilot object
     *
     * @param \Model\Pilot|ObjectCollection $pilot the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAirlineQuery The current query, for fluid interface
     */
    public function filterByPilot($pilot, $comparison = null)
    {
        if ($pilot instanceof \Model\Pilot) {
            return $this
                ->addUsingAlias(AirlineTableMap::COL_ID, $pilot->getAirlineId(), $comparison);
        } elseif ($pilot instanceof ObjectCollection) {
            return $this
                ->usePilotQuery()
                ->filterByPrimaryKeys($pilot->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function joinPilot($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePilotQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPilot($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Pilot', '\Model\PilotQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAirline $airline Object to remove from the list of results
     *
     * @return $this|ChildAirlineQuery The current query, for fluid interface
     */
    public function prune($airline = null)
    {
        if ($airline) {
            $this->addUsingAlias(AirlineTableMap::COL_ID, $airline->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the airlines table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirlineTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AirlineTableMap::clearInstancePool();
            AirlineTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AirlineTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AirlineTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AirlineTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AirlineTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AirlineQuery

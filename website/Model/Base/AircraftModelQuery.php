<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\AircraftModel as ChildAircraftModel;
use Model\AircraftModelQuery as ChildAircraftModelQuery;
use Model\Map\AircraftModelTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'aircraft_models' table.
 *
 *
 *
 * @method     ChildAircraftModelQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAircraftModelQuery orderByModel($order = Criteria::ASC) Order by the model column
 * @method     ChildAircraftModelQuery orderByBrand($order = Criteria::ASC) Order by the brand column
 * @method     ChildAircraftModelQuery orderByPackages($order = Criteria::ASC) Order by the packages column
 * @method     ChildAircraftModelQuery orderByPost($order = Criteria::ASC) Order by the post column
 * @method     ChildAircraftModelQuery orderByPassengerLow($order = Criteria::ASC) Order by the passenger_low column
 * @method     ChildAircraftModelQuery orderByPassengerMid($order = Criteria::ASC) Order by the passenger_mid column
 * @method     ChildAircraftModelQuery orderByPassengerHigh($order = Criteria::ASC) Order by the passenger_high column
 * @method     ChildAircraftModelQuery orderByWeight($order = Criteria::ASC) Order by the weight column
 * @method     ChildAircraftModelQuery orderByValue($order = Criteria::ASC) Order by the value column
 *
 * @method     ChildAircraftModelQuery groupById() Group by the id column
 * @method     ChildAircraftModelQuery groupByModel() Group by the model column
 * @method     ChildAircraftModelQuery groupByBrand() Group by the brand column
 * @method     ChildAircraftModelQuery groupByPackages() Group by the packages column
 * @method     ChildAircraftModelQuery groupByPost() Group by the post column
 * @method     ChildAircraftModelQuery groupByPassengerLow() Group by the passenger_low column
 * @method     ChildAircraftModelQuery groupByPassengerMid() Group by the passenger_mid column
 * @method     ChildAircraftModelQuery groupByPassengerHigh() Group by the passenger_high column
 * @method     ChildAircraftModelQuery groupByWeight() Group by the weight column
 * @method     ChildAircraftModelQuery groupByValue() Group by the value column
 *
 * @method     ChildAircraftModelQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAircraftModelQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAircraftModelQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAircraftModelQuery leftJoinAircraft($relationAlias = null) Adds a LEFT JOIN clause to the query using the Aircraft relation
 * @method     ChildAircraftModelQuery rightJoinAircraft($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Aircraft relation
 * @method     ChildAircraftModelQuery innerJoinAircraft($relationAlias = null) Adds a INNER JOIN clause to the query using the Aircraft relation
 *
 * @method     ChildAircraftModelQuery leftJoinFlight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Flight relation
 * @method     ChildAircraftModelQuery rightJoinFlight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Flight relation
 * @method     ChildAircraftModelQuery innerJoinFlight($relationAlias = null) Adds a INNER JOIN clause to the query using the Flight relation
 *
 * @method     \Model\AircraftQuery|\Model\FlightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAircraftModel findOne(ConnectionInterface $con = null) Return the first ChildAircraftModel matching the query
 * @method     ChildAircraftModel findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAircraftModel matching the query, or a new ChildAircraftModel object populated from the query conditions when no match is found
 *
 * @method     ChildAircraftModel findOneById(int $id) Return the first ChildAircraftModel filtered by the id column
 * @method     ChildAircraftModel findOneByModel(string $model) Return the first ChildAircraftModel filtered by the model column
 * @method     ChildAircraftModel findOneByBrand(string $brand) Return the first ChildAircraftModel filtered by the brand column
 * @method     ChildAircraftModel findOneByPackages(int $packages) Return the first ChildAircraftModel filtered by the packages column
 * @method     ChildAircraftModel findOneByPost(int $post) Return the first ChildAircraftModel filtered by the post column
 * @method     ChildAircraftModel findOneByPassengerLow(int $passenger_low) Return the first ChildAircraftModel filtered by the passenger_low column
 * @method     ChildAircraftModel findOneByPassengerMid(int $passenger_mid) Return the first ChildAircraftModel filtered by the passenger_mid column
 * @method     ChildAircraftModel findOneByPassengerHigh(int $passenger_high) Return the first ChildAircraftModel filtered by the passenger_high column
 * @method     ChildAircraftModel findOneByWeight(int $weight) Return the first ChildAircraftModel filtered by the weight column
 * @method     ChildAircraftModel findOneByValue(int $value) Return the first ChildAircraftModel filtered by the value column *

 * @method     ChildAircraftModel requirePk($key, ConnectionInterface $con = null) Return the ChildAircraftModel by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOne(ConnectionInterface $con = null) Return the first ChildAircraftModel matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAircraftModel requireOneById(int $id) Return the first ChildAircraftModel filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByModel(string $model) Return the first ChildAircraftModel filtered by the model column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByBrand(string $brand) Return the first ChildAircraftModel filtered by the brand column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByPackages(int $packages) Return the first ChildAircraftModel filtered by the packages column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByPost(int $post) Return the first ChildAircraftModel filtered by the post column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByPassengerLow(int $passenger_low) Return the first ChildAircraftModel filtered by the passenger_low column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByPassengerMid(int $passenger_mid) Return the first ChildAircraftModel filtered by the passenger_mid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByPassengerHigh(int $passenger_high) Return the first ChildAircraftModel filtered by the passenger_high column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByWeight(int $weight) Return the first ChildAircraftModel filtered by the weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAircraftModel requireOneByValue(int $value) Return the first ChildAircraftModel filtered by the value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAircraftModel[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAircraftModel objects based on current ModelCriteria
 * @method     ChildAircraftModel[]|ObjectCollection findById(int $id) Return ChildAircraftModel objects filtered by the id column
 * @method     ChildAircraftModel[]|ObjectCollection findByModel(string $model) Return ChildAircraftModel objects filtered by the model column
 * @method     ChildAircraftModel[]|ObjectCollection findByBrand(string $brand) Return ChildAircraftModel objects filtered by the brand column
 * @method     ChildAircraftModel[]|ObjectCollection findByPackages(int $packages) Return ChildAircraftModel objects filtered by the packages column
 * @method     ChildAircraftModel[]|ObjectCollection findByPost(int $post) Return ChildAircraftModel objects filtered by the post column
 * @method     ChildAircraftModel[]|ObjectCollection findByPassengerLow(int $passenger_low) Return ChildAircraftModel objects filtered by the passenger_low column
 * @method     ChildAircraftModel[]|ObjectCollection findByPassengerMid(int $passenger_mid) Return ChildAircraftModel objects filtered by the passenger_mid column
 * @method     ChildAircraftModel[]|ObjectCollection findByPassengerHigh(int $passenger_high) Return ChildAircraftModel objects filtered by the passenger_high column
 * @method     ChildAircraftModel[]|ObjectCollection findByWeight(int $weight) Return ChildAircraftModel objects filtered by the weight column
 * @method     ChildAircraftModel[]|ObjectCollection findByValue(int $value) Return ChildAircraftModel objects filtered by the value column
 * @method     ChildAircraftModel[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AircraftModelQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AircraftModelQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\AircraftModel', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAircraftModelQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAircraftModelQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAircraftModelQuery) {
            return $criteria;
        }
        $query = new ChildAircraftModelQuery();
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
     * @return ChildAircraftModel|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AircraftModelTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AircraftModelTableMap::DATABASE_NAME);
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
     * @return ChildAircraftModel A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, model, brand, packages, post, passenger_low, passenger_mid, passenger_high, weight, value FROM aircraft_models WHERE id = :p0';
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
            /** @var ChildAircraftModel $obj */
            $obj = new ChildAircraftModel();
            $obj->hydrate($row);
            AircraftModelTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAircraftModel|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AircraftModelTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AircraftModelTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the model column
     *
     * Example usage:
     * <code>
     * $query->filterByModel('fooValue');   // WHERE model = 'fooValue'
     * $query->filterByModel('%fooValue%'); // WHERE model LIKE '%fooValue%'
     * </code>
     *
     * @param     string $model The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByModel($model = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($model)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $model)) {
                $model = str_replace('*', '%', $model);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_MODEL, $model, $comparison);
    }

    /**
     * Filter the query on the brand column
     *
     * Example usage:
     * <code>
     * $query->filterByBrand('fooValue');   // WHERE brand = 'fooValue'
     * $query->filterByBrand('%fooValue%'); // WHERE brand LIKE '%fooValue%'
     * </code>
     *
     * @param     string $brand The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByBrand($brand = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($brand)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $brand)) {
                $brand = str_replace('*', '%', $brand);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_BRAND, $brand, $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPackages($packages = null, $comparison = null)
    {
        if (is_array($packages)) {
            $useMinMax = false;
            if (isset($packages['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PACKAGES, $packages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($packages['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PACKAGES, $packages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_PACKAGES, $packages, $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPost($post = null, $comparison = null)
    {
        if (is_array($post)) {
            $useMinMax = false;
            if (isset($post['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_POST, $post['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($post['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_POST, $post['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_POST, $post, $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPassengerLow($passengerLow = null, $comparison = null)
    {
        if (is_array($passengerLow)) {
            $useMinMax = false;
            if (isset($passengerLow['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_LOW, $passengerLow['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerLow['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_LOW, $passengerLow['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_LOW, $passengerLow, $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPassengerMid($passengerMid = null, $comparison = null)
    {
        if (is_array($passengerMid)) {
            $useMinMax = false;
            if (isset($passengerMid['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_MID, $passengerMid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerMid['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_MID, $passengerMid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_MID, $passengerMid, $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByPassengerHigh($passengerHigh = null, $comparison = null)
    {
        if (is_array($passengerHigh)) {
            $useMinMax = false;
            if (isset($passengerHigh['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_HIGH, $passengerHigh['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passengerHigh['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_HIGH, $passengerHigh['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_PASSENGER_HIGH, $passengerHigh, $comparison);
    }

    /**
     * Filter the query on the weight column
     *
     * Example usage:
     * <code>
     * $query->filterByWeight(1234); // WHERE weight = 1234
     * $query->filterByWeight(array(12, 34)); // WHERE weight IN (12, 34)
     * $query->filterByWeight(array('min' => 12)); // WHERE weight > 12
     * </code>
     *
     * @param     mixed $weight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByWeight($weight = null, $comparison = null)
    {
        if (is_array($weight)) {
            $useMinMax = false;
            if (isset($weight['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_WEIGHT, $weight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weight['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_WEIGHT, $weight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_WEIGHT, $weight, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue(1234); // WHERE value = 1234
     * $query->filterByValue(array(12, 34)); // WHERE value IN (12, 34)
     * $query->filterByValue(array('min' => 12)); // WHERE value > 12
     * </code>
     *
     * @param     mixed $value The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(AircraftModelTableMap::COL_VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AircraftModelTableMap::COL_VALUE, $value, $comparison);
    }

    /**
     * Filter the query by a related \Model\Aircraft object
     *
     * @param \Model\Aircraft|ObjectCollection $aircraft the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByAircraft($aircraft, $comparison = null)
    {
        if ($aircraft instanceof \Model\Aircraft) {
            return $this
                ->addUsingAlias(AircraftModelTableMap::COL_ID, $aircraft->getAircraftModelId(), $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
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
     * @return ChildAircraftModelQuery The current query, for fluid interface
     */
    public function filterByFlight($flight, $comparison = null)
    {
        if ($flight instanceof \Model\Flight) {
            return $this
                ->addUsingAlias(AircraftModelTableMap::COL_ID, $flight->getAircraftModelId(), $comparison);
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
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
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
     * @param   ChildAircraftModel $aircraftModel Object to remove from the list of results
     *
     * @return $this|ChildAircraftModelQuery The current query, for fluid interface
     */
    public function prune($aircraftModel = null)
    {
        if ($aircraftModel) {
            $this->addUsingAlias(AircraftModelTableMap::COL_ID, $aircraftModel->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the aircraft_models table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftModelTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AircraftModelTableMap::clearInstancePool();
            AircraftModelTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftModelTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AircraftModelTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AircraftModelTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AircraftModelTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AircraftModelQuery

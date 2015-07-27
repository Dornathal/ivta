<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Aircraft as ChildAircraft;
use Model\AircraftQuery as ChildAircraftQuery;
use Model\Airport as ChildAirport;
use Model\AirportQuery as ChildAirportQuery;
use Model\Airway as ChildAirway;
use Model\AirwayQuery as ChildAirwayQuery;
use Model\Flight as ChildFlight;
use Model\FlightQuery as ChildFlightQuery;
use Model\Freight as ChildFreight;
use Model\FreightGeneration as ChildFreightGeneration;
use Model\FreightGenerationQuery as ChildFreightGenerationQuery;
use Model\FreightQuery as ChildFreightQuery;
use Model\Map\AirportTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'airports' table.
 *
 *
 *
* @package    propel.generator.Model.Base
*/
abstract class Airport implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\AirportTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the country field.
     * @var        string
     */
    protected $country;

    /**
     * The value for the iata field.
     * @var        string
     */
    protected $iata;

    /**
     * The value for the icao field.
     * @var        string
     */
    protected $icao;

    /**
     * The value for the altitude field.
     * Note: this column has a database default value of: 0
     * @var        double
     */
    protected $altitude;

    /**
     * The value for the timezone field.
     * Note: this column has a database default value of: 0
     * @var        double
     */
    protected $timezone;

    /**
     * The value for the dst field.
     * Note: this column has a database default value of: 6
     * @var        int
     */
    protected $dst;

    /**
     * The value for the size field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $size;

    /**
     * The value for the latitude field.
     * @var        double
     */
    protected $latitude;

    /**
     * The value for the longitude field.
     * @var        double
     */
    protected $longitude;

    /**
     * @var        ObjectCollection|ChildAircraft[] Collection to store aggregation of ChildAircraft objects.
     */
    protected $collAircrafts;
    protected $collAircraftsPartial;

    /**
     * @var        ObjectCollection|ChildFlight[] Collection to store aggregation of ChildFlight objects.
     */
    protected $collFlightsRelatedByDestinationId;
    protected $collFlightsRelatedByDestinationIdPartial;

    /**
     * @var        ObjectCollection|ChildFlight[] Collection to store aggregation of ChildFlight objects.
     */
    protected $collFlightsRelatedByDepartureId;
    protected $collFlightsRelatedByDepartureIdPartial;

    /**
     * @var        ObjectCollection|ChildAirway[] Collection to store aggregation of ChildAirway objects.
     */
    protected $collAirwaysRelatedByDestinationId;
    protected $collAirwaysRelatedByDestinationIdPartial;

    /**
     * @var        ObjectCollection|ChildAirway[] Collection to store aggregation of ChildAirway objects.
     */
    protected $collAirwaysRelatedByDepartureId;
    protected $collAirwaysRelatedByDepartureIdPartial;

    /**
     * @var        ObjectCollection|ChildFreight[] Collection to store aggregation of ChildFreight objects.
     */
    protected $collFreightsRelatedByDestinationId;
    protected $collFreightsRelatedByDestinationIdPartial;

    /**
     * @var        ObjectCollection|ChildFreight[] Collection to store aggregation of ChildFreight objects.
     */
    protected $collFreightsRelatedByDepartureId;
    protected $collFreightsRelatedByDepartureIdPartial;

    /**
     * @var        ObjectCollection|ChildFreight[] Collection to store aggregation of ChildFreight objects.
     */
    protected $collFreightsRelatedByLocationId;
    protected $collFreightsRelatedByLocationIdPartial;

    /**
     * @var        ChildFreightGeneration one-to-one related ChildFreightGeneration object
     */
    protected $singleFreightGeneration;

    /**
     * @var        ObjectCollection|ChildAirport[] Cross Collection to store aggregation of ChildAirport objects.
     */
    protected $collDepartures;

    /**
     * @var bool
     */
    protected $collDeparturesPartial;

    /**
     * @var        ObjectCollection|ChildAirport[] Cross Collection to store aggregation of ChildAirport objects.
     */
    protected $collDestinations;

    /**
     * @var bool
     */
    protected $collDestinationsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAirport[]
     */
    protected $departuresScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAirport[]
     */
    protected $destinationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAircraft[]
     */
    protected $aircraftsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFlight[]
     */
    protected $flightsRelatedByDestinationIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFlight[]
     */
    protected $flightsRelatedByDepartureIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAirway[]
     */
    protected $airwaysRelatedByDestinationIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAirway[]
     */
    protected $airwaysRelatedByDepartureIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFreight[]
     */
    protected $freightsRelatedByDestinationIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFreight[]
     */
    protected $freightsRelatedByDepartureIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFreight[]
     */
    protected $freightsRelatedByLocationIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->altitude = 0;
        $this->timezone = 0;
        $this->dst = 6;
        $this->size = 0;
    }

    /**
     * Initializes internal state of Model\Base\Airport object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Airport</code> instance.  If
     * <code>obj</code> is an instance of <code>Airport</code>, delegates to
     * <code>equals(Airport)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Airport The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [country] column value.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get the [iata] column value.
     *
     * @return string
     */
    public function getIATA()
    {
        return $this->iata;
    }

    /**
     * Get the [icao] column value.
     *
     * @return string
     */
    public function getICAO()
    {
        return $this->icao;
    }

    /**
     * Get the [altitude] column value.
     *
     * @return double
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Get the [timezone] column value.
     *
     * @return double
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Get the [dst] column value.
     *
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getDst()
    {
        if (null === $this->dst) {
            return null;
        }
        $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_DST);
        if (!isset($valueSet[$this->dst])) {
            throw new PropelException('Unknown stored enum key: ' . $this->dst);
        }

        return $valueSet[$this->dst];
    }

    /**
     * Get the [size] column value.
     *
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSize()
    {
        if (null === $this->size) {
            return null;
        }
        $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_SIZE);
        if (!isset($valueSet[$this->size])) {
            throw new PropelException('Unknown stored enum key: ' . $this->size);
        }

        return $valueSet[$this->size];
    }

    /**
     * Get the [latitude] column value.
     *
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the [longitude] column value.
     *
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AirportTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[AirportTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [city] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[AirportTableMap::COL_CITY] = true;
        }

        return $this;
    } // setCity()

    /**
     * Set the value of [country] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[AirportTableMap::COL_COUNTRY] = true;
        }

        return $this;
    } // setCountry()

    /**
     * Set the value of [iata] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setIATA($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->iata !== $v) {
            $this->iata = $v;
            $this->modifiedColumns[AirportTableMap::COL_IATA] = true;
        }

        return $this;
    } // setIATA()

    /**
     * Set the value of [icao] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setICAO($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->icao !== $v) {
            $this->icao = $v;
            $this->modifiedColumns[AirportTableMap::COL_ICAO] = true;
        }

        return $this;
    } // setICAO()

    /**
     * Set the value of [altitude] column.
     *
     * @param double $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setAltitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->altitude !== $v) {
            $this->altitude = $v;
            $this->modifiedColumns[AirportTableMap::COL_ALTITUDE] = true;
        }

        return $this;
    } // setAltitude()

    /**
     * Set the value of [timezone] column.
     *
     * @param double $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setTimezone($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->timezone !== $v) {
            $this->timezone = $v;
            $this->modifiedColumns[AirportTableMap::COL_TIMEZONE] = true;
        }

        return $this;
    } // setTimezone()

    /**
     * Set the value of [dst] column.
     *
     * @param  string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setDst($v)
    {
        if ($v !== null) {
            $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_DST);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->dst !== $v) {
            $this->dst = $v;
            $this->modifiedColumns[AirportTableMap::COL_DST] = true;
        }

        return $this;
    } // setDst()

    /**
     * Set the value of [size] column.
     *
     * @param  string $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setSize($v)
    {
        if ($v !== null) {
            $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_SIZE);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->size !== $v) {
            $this->size = $v;
            $this->modifiedColumns[AirportTableMap::COL_SIZE] = true;
        }

        return $this;
    } // setSize()

    /**
     * Set the value of [latitude] column.
     *
     * @param double $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setLatitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->latitude !== $v) {
            $this->latitude = $v;
            $this->modifiedColumns[AirportTableMap::COL_LATITUDE] = true;
        }

        return $this;
    } // setLatitude()

    /**
     * Set the value of [longitude] column.
     *
     * @param double $v new value
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function setLongitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->longitude !== $v) {
            $this->longitude = $v;
            $this->modifiedColumns[AirportTableMap::COL_LONGITUDE] = true;
        }

        return $this;
    } // setLongitude()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->altitude !== 0) {
                return false;
            }

            if ($this->timezone !== 0) {
                return false;
            }

            if ($this->dst !== 6) {
                return false;
            }

            if ($this->size !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AirportTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AirportTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AirportTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AirportTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AirportTableMap::translateFieldName('IATA', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iata = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AirportTableMap::translateFieldName('ICAO', TableMap::TYPE_PHPNAME, $indexType)];
            $this->icao = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AirportTableMap::translateFieldName('Altitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->altitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AirportTableMap::translateFieldName('Timezone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->timezone = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AirportTableMap::translateFieldName('Dst', TableMap::TYPE_PHPNAME, $indexType)];
            $this->dst = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : AirportTableMap::translateFieldName('Size', TableMap::TYPE_PHPNAME, $indexType)];
            $this->size = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : AirportTableMap::translateFieldName('Latitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->latitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : AirportTableMap::translateFieldName('Longitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->longitude = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = AirportTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Airport'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AirportTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAirportQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAircrafts = null;

            $this->collFlightsRelatedByDestinationId = null;

            $this->collFlightsRelatedByDepartureId = null;

            $this->collAirwaysRelatedByDestinationId = null;

            $this->collAirwaysRelatedByDepartureId = null;

            $this->collFreightsRelatedByDestinationId = null;

            $this->collFreightsRelatedByDepartureId = null;

            $this->collFreightsRelatedByLocationId = null;

            $this->singleFreightGeneration = null;

            $this->collDepartures = null;
            $this->collDestinations = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Airport::setDeleted()
     * @see Airport::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirportTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAirportQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirportTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                AirportTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->departuresScheduledForDeletion !== null) {
                if (!$this->departuresScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->departuresScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Model\AirwayQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->departuresScheduledForDeletion = null;
                }

            }

            if ($this->collDepartures) {
                foreach ($this->collDepartures as $departure) {
                    if (!$departure->isDeleted() && ($departure->isNew() || $departure->isModified())) {
                        $departure->save($con);
                    }
                }
            }


            if ($this->destinationsScheduledForDeletion !== null) {
                if (!$this->destinationsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->destinationsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Model\AirwayQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->destinationsScheduledForDeletion = null;
                }

            }

            if ($this->collDestinations) {
                foreach ($this->collDestinations as $destination) {
                    if (!$destination->isDeleted() && ($destination->isNew() || $destination->isModified())) {
                        $destination->save($con);
                    }
                }
            }


            if ($this->aircraftsScheduledForDeletion !== null) {
                if (!$this->aircraftsScheduledForDeletion->isEmpty()) {
                    foreach ($this->aircraftsScheduledForDeletion as $aircraft) {
                        // need to save related object because we set the relation to null
                        $aircraft->save($con);
                    }
                    $this->aircraftsScheduledForDeletion = null;
                }
            }

            if ($this->collAircrafts !== null) {
                foreach ($this->collAircrafts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->flightsRelatedByDestinationIdScheduledForDeletion !== null) {
                if (!$this->flightsRelatedByDestinationIdScheduledForDeletion->isEmpty()) {
                    \Model\FlightQuery::create()
                        ->filterByPrimaryKeys($this->flightsRelatedByDestinationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->flightsRelatedByDestinationIdScheduledForDeletion = null;
                }
            }

            if ($this->collFlightsRelatedByDestinationId !== null) {
                foreach ($this->collFlightsRelatedByDestinationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->flightsRelatedByDepartureIdScheduledForDeletion !== null) {
                if (!$this->flightsRelatedByDepartureIdScheduledForDeletion->isEmpty()) {
                    \Model\FlightQuery::create()
                        ->filterByPrimaryKeys($this->flightsRelatedByDepartureIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->flightsRelatedByDepartureIdScheduledForDeletion = null;
                }
            }

            if ($this->collFlightsRelatedByDepartureId !== null) {
                foreach ($this->collFlightsRelatedByDepartureId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->airwaysRelatedByDestinationIdScheduledForDeletion !== null) {
                if (!$this->airwaysRelatedByDestinationIdScheduledForDeletion->isEmpty()) {
                    \Model\AirwayQuery::create()
                        ->filterByPrimaryKeys($this->airwaysRelatedByDestinationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->airwaysRelatedByDestinationIdScheduledForDeletion = null;
                }
            }

            if ($this->collAirwaysRelatedByDestinationId !== null) {
                foreach ($this->collAirwaysRelatedByDestinationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->airwaysRelatedByDepartureIdScheduledForDeletion !== null) {
                if (!$this->airwaysRelatedByDepartureIdScheduledForDeletion->isEmpty()) {
                    \Model\AirwayQuery::create()
                        ->filterByPrimaryKeys($this->airwaysRelatedByDepartureIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->airwaysRelatedByDepartureIdScheduledForDeletion = null;
                }
            }

            if ($this->collAirwaysRelatedByDepartureId !== null) {
                foreach ($this->collAirwaysRelatedByDepartureId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->freightsRelatedByDestinationIdScheduledForDeletion !== null) {
                if (!$this->freightsRelatedByDestinationIdScheduledForDeletion->isEmpty()) {
                    \Model\FreightQuery::create()
                        ->filterByPrimaryKeys($this->freightsRelatedByDestinationIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->freightsRelatedByDestinationIdScheduledForDeletion = null;
                }
            }

            if ($this->collFreightsRelatedByDestinationId !== null) {
                foreach ($this->collFreightsRelatedByDestinationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->freightsRelatedByDepartureIdScheduledForDeletion !== null) {
                if (!$this->freightsRelatedByDepartureIdScheduledForDeletion->isEmpty()) {
                    \Model\FreightQuery::create()
                        ->filterByPrimaryKeys($this->freightsRelatedByDepartureIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->freightsRelatedByDepartureIdScheduledForDeletion = null;
                }
            }

            if ($this->collFreightsRelatedByDepartureId !== null) {
                foreach ($this->collFreightsRelatedByDepartureId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->freightsRelatedByLocationIdScheduledForDeletion !== null) {
                if (!$this->freightsRelatedByLocationIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->freightsRelatedByLocationIdScheduledForDeletion as $freightRelatedByLocationId) {
                        // need to save related object because we set the relation to null
                        $freightRelatedByLocationId->save($con);
                    }
                    $this->freightsRelatedByLocationIdScheduledForDeletion = null;
                }
            }

            if ($this->collFreightsRelatedByLocationId !== null) {
                foreach ($this->collFreightsRelatedByLocationId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->singleFreightGeneration !== null) {
                if (!$this->singleFreightGeneration->isDeleted() && ($this->singleFreightGeneration->isNew() || $this->singleFreightGeneration->isModified())) {
                    $affectedRows += $this->singleFreightGeneration->save($con);
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AirportTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AirportTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(AirportTableMap::COL_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'city';
        }
        if ($this->isColumnModified(AirportTableMap::COL_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'country';
        }
        if ($this->isColumnModified(AirportTableMap::COL_IATA)) {
            $modifiedColumns[':p' . $index++]  = 'iata';
        }
        if ($this->isColumnModified(AirportTableMap::COL_ICAO)) {
            $modifiedColumns[':p' . $index++]  = 'icao';
        }
        if ($this->isColumnModified(AirportTableMap::COL_ALTITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'altitude';
        }
        if ($this->isColumnModified(AirportTableMap::COL_TIMEZONE)) {
            $modifiedColumns[':p' . $index++]  = 'timezone';
        }
        if ($this->isColumnModified(AirportTableMap::COL_DST)) {
            $modifiedColumns[':p' . $index++]  = 'dst';
        }
        if ($this->isColumnModified(AirportTableMap::COL_SIZE)) {
            $modifiedColumns[':p' . $index++]  = 'size';
        }
        if ($this->isColumnModified(AirportTableMap::COL_LATITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'latitude';
        }
        if ($this->isColumnModified(AirportTableMap::COL_LONGITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'longitude';
        }

        $sql = sprintf(
            'INSERT INTO airports (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'city':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case 'country':
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_STR);
                        break;
                    case 'iata':
                        $stmt->bindValue($identifier, $this->iata, PDO::PARAM_STR);
                        break;
                    case 'icao':
                        $stmt->bindValue($identifier, $this->icao, PDO::PARAM_STR);
                        break;
                    case 'altitude':
                        $stmt->bindValue($identifier, $this->altitude, PDO::PARAM_STR);
                        break;
                    case 'timezone':
                        $stmt->bindValue($identifier, $this->timezone, PDO::PARAM_STR);
                        break;
                    case 'dst':
                        $stmt->bindValue($identifier, $this->dst, PDO::PARAM_INT);
                        break;
                    case 'size':
                        $stmt->bindValue($identifier, $this->size, PDO::PARAM_INT);
                        break;
                    case 'latitude':
                        $stmt->bindValue($identifier, $this->latitude, PDO::PARAM_STR);
                        break;
                    case 'longitude':
                        $stmt->bindValue($identifier, $this->longitude, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AirportTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getCity();
                break;
            case 3:
                return $this->getCountry();
                break;
            case 4:
                return $this->getIATA();
                break;
            case 5:
                return $this->getICAO();
                break;
            case 6:
                return $this->getAltitude();
                break;
            case 7:
                return $this->getTimezone();
                break;
            case 8:
                return $this->getDst();
                break;
            case 9:
                return $this->getSize();
                break;
            case 10:
                return $this->getLatitude();
                break;
            case 11:
                return $this->getLongitude();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Airport'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Airport'][$this->hashCode()] = true;
        $keys = AirportTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getCity(),
            $keys[3] => $this->getCountry(),
            $keys[4] => $this->getIATA(),
            $keys[5] => $this->getICAO(),
            $keys[6] => $this->getAltitude(),
            $keys[7] => $this->getTimezone(),
            $keys[8] => $this->getDst(),
            $keys[9] => $this->getSize(),
            $keys[10] => $this->getLatitude(),
            $keys[11] => $this->getLongitude(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collAircrafts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'aircrafts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'aircraftss';
                        break;
                    default:
                        $key = 'Aircrafts';
                }

                $result[$key] = $this->collAircrafts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFlightsRelatedByDestinationId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'flights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'flightss';
                        break;
                    default:
                        $key = 'Flights';
                }

                $result[$key] = $this->collFlightsRelatedByDestinationId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFlightsRelatedByDepartureId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'flights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'flightss';
                        break;
                    default:
                        $key = 'Flights';
                }

                $result[$key] = $this->collFlightsRelatedByDepartureId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAirwaysRelatedByDestinationId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'airways';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'airwayss';
                        break;
                    default:
                        $key = 'Airways';
                }

                $result[$key] = $this->collAirwaysRelatedByDestinationId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAirwaysRelatedByDepartureId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'airways';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'airwayss';
                        break;
                    default:
                        $key = 'Airways';
                }

                $result[$key] = $this->collAirwaysRelatedByDepartureId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFreightsRelatedByDestinationId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'freights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'freightss';
                        break;
                    default:
                        $key = 'Freights';
                }

                $result[$key] = $this->collFreightsRelatedByDestinationId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFreightsRelatedByDepartureId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'freights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'freightss';
                        break;
                    default:
                        $key = 'Freights';
                }

                $result[$key] = $this->collFreightsRelatedByDepartureId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFreightsRelatedByLocationId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'freights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'freightss';
                        break;
                    default:
                        $key = 'Freights';
                }

                $result[$key] = $this->collFreightsRelatedByLocationId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singleFreightGeneration) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'freightGeneration';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'freight_generations';
                        break;
                    default:
                        $key = 'FreightGeneration';
                }

                $result[$key] = $this->singleFreightGeneration->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Model\Airport
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AirportTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Airport
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setCity($value);
                break;
            case 3:
                $this->setCountry($value);
                break;
            case 4:
                $this->setIATA($value);
                break;
            case 5:
                $this->setICAO($value);
                break;
            case 6:
                $this->setAltitude($value);
                break;
            case 7:
                $this->setTimezone($value);
                break;
            case 8:
                $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_DST);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setDst($value);
                break;
            case 9:
                $valueSet = AirportTableMap::getValueSet(AirportTableMap::COL_SIZE);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setSize($value);
                break;
            case 10:
                $this->setLatitude($value);
                break;
            case 11:
                $this->setLongitude($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = AirportTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCity($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCountry($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setIATA($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setICAO($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setAltitude($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setTimezone($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDst($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setSize($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setLatitude($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setLongitude($arr[$keys[11]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Model\Airport The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AirportTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AirportTableMap::COL_ID)) {
            $criteria->add(AirportTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AirportTableMap::COL_NAME)) {
            $criteria->add(AirportTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(AirportTableMap::COL_CITY)) {
            $criteria->add(AirportTableMap::COL_CITY, $this->city);
        }
        if ($this->isColumnModified(AirportTableMap::COL_COUNTRY)) {
            $criteria->add(AirportTableMap::COL_COUNTRY, $this->country);
        }
        if ($this->isColumnModified(AirportTableMap::COL_IATA)) {
            $criteria->add(AirportTableMap::COL_IATA, $this->iata);
        }
        if ($this->isColumnModified(AirportTableMap::COL_ICAO)) {
            $criteria->add(AirportTableMap::COL_ICAO, $this->icao);
        }
        if ($this->isColumnModified(AirportTableMap::COL_ALTITUDE)) {
            $criteria->add(AirportTableMap::COL_ALTITUDE, $this->altitude);
        }
        if ($this->isColumnModified(AirportTableMap::COL_TIMEZONE)) {
            $criteria->add(AirportTableMap::COL_TIMEZONE, $this->timezone);
        }
        if ($this->isColumnModified(AirportTableMap::COL_DST)) {
            $criteria->add(AirportTableMap::COL_DST, $this->dst);
        }
        if ($this->isColumnModified(AirportTableMap::COL_SIZE)) {
            $criteria->add(AirportTableMap::COL_SIZE, $this->size);
        }
        if ($this->isColumnModified(AirportTableMap::COL_LATITUDE)) {
            $criteria->add(AirportTableMap::COL_LATITUDE, $this->latitude);
        }
        if ($this->isColumnModified(AirportTableMap::COL_LONGITUDE)) {
            $criteria->add(AirportTableMap::COL_LONGITUDE, $this->longitude);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildAirportQuery::create();
        $criteria->add(AirportTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Airport (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setId($this->getId());
        $copyObj->setName($this->getName());
        $copyObj->setCity($this->getCity());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setIATA($this->getIATA());
        $copyObj->setICAO($this->getICAO());
        $copyObj->setAltitude($this->getAltitude());
        $copyObj->setTimezone($this->getTimezone());
        $copyObj->setDst($this->getDst());
        $copyObj->setSize($this->getSize());
        $copyObj->setLatitude($this->getLatitude());
        $copyObj->setLongitude($this->getLongitude());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAircrafts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAircraft($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFlightsRelatedByDestinationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFlightRelatedByDestinationId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFlightsRelatedByDepartureId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFlightRelatedByDepartureId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAirwaysRelatedByDestinationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAirwayRelatedByDestinationId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAirwaysRelatedByDepartureId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAirwayRelatedByDepartureId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFreightsRelatedByDestinationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFreightRelatedByDestinationId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFreightsRelatedByDepartureId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFreightRelatedByDepartureId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFreightsRelatedByLocationId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFreightRelatedByLocationId($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getFreightGeneration();
            if ($relObj) {
                $copyObj->setFreightGeneration($relObj->copy($deepCopy));
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\Airport Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Aircraft' == $relationName) {
            return $this->initAircrafts();
        }
        if ('FlightRelatedByDestinationId' == $relationName) {
            return $this->initFlightsRelatedByDestinationId();
        }
        if ('FlightRelatedByDepartureId' == $relationName) {
            return $this->initFlightsRelatedByDepartureId();
        }
        if ('AirwayRelatedByDestinationId' == $relationName) {
            return $this->initAirwaysRelatedByDestinationId();
        }
        if ('AirwayRelatedByDepartureId' == $relationName) {
            return $this->initAirwaysRelatedByDepartureId();
        }
        if ('FreightRelatedByDestinationId' == $relationName) {
            return $this->initFreightsRelatedByDestinationId();
        }
        if ('FreightRelatedByDepartureId' == $relationName) {
            return $this->initFreightsRelatedByDepartureId();
        }
        if ('FreightRelatedByLocationId' == $relationName) {
            return $this->initFreightsRelatedByLocationId();
        }
    }

    /**
     * Clears out the collAircrafts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAircrafts()
     */
    public function clearAircrafts()
    {
        $this->collAircrafts = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAircrafts collection loaded partially.
     */
    public function resetPartialAircrafts($v = true)
    {
        $this->collAircraftsPartial = $v;
    }

    /**
     * Initializes the collAircrafts collection.
     *
     * By default this just sets the collAircrafts collection to an empty array (like clearcollAircrafts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAircrafts($overrideExisting = true)
    {
        if (null !== $this->collAircrafts && !$overrideExisting) {
            return;
        }
        $this->collAircrafts = new ObjectCollection();
        $this->collAircrafts->setModel('\Model\Aircraft');
    }

    /**
     * Gets an array of ChildAircraft objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAircraft[] List of ChildAircraft objects
     * @throws PropelException
     */
    public function getAircrafts(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAircraftsPartial && !$this->isNew();
        if (null === $this->collAircrafts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAircrafts) {
                // return empty collection
                $this->initAircrafts();
            } else {
                $collAircrafts = ChildAircraftQuery::create(null, $criteria)
                    ->filterByAirport($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAircraftsPartial && count($collAircrafts)) {
                        $this->initAircrafts(false);

                        foreach ($collAircrafts as $obj) {
                            if (false == $this->collAircrafts->contains($obj)) {
                                $this->collAircrafts->append($obj);
                            }
                        }

                        $this->collAircraftsPartial = true;
                    }

                    return $collAircrafts;
                }

                if ($partial && $this->collAircrafts) {
                    foreach ($this->collAircrafts as $obj) {
                        if ($obj->isNew()) {
                            $collAircrafts[] = $obj;
                        }
                    }
                }

                $this->collAircrafts = $collAircrafts;
                $this->collAircraftsPartial = false;
            }
        }

        return $this->collAircrafts;
    }

    /**
     * Sets a collection of ChildAircraft objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $aircrafts A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setAircrafts(Collection $aircrafts, ConnectionInterface $con = null)
    {
        /** @var ChildAircraft[] $aircraftsToDelete */
        $aircraftsToDelete = $this->getAircrafts(new Criteria(), $con)->diff($aircrafts);


        $this->aircraftsScheduledForDeletion = $aircraftsToDelete;

        foreach ($aircraftsToDelete as $aircraftRemoved) {
            $aircraftRemoved->setAirport(null);
        }

        $this->collAircrafts = null;
        foreach ($aircrafts as $aircraft) {
            $this->addAircraft($aircraft);
        }

        $this->collAircrafts = $aircrafts;
        $this->collAircraftsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Aircraft objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Aircraft objects.
     * @throws PropelException
     */
    public function countAircrafts(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAircraftsPartial && !$this->isNew();
        if (null === $this->collAircrafts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAircrafts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAircrafts());
            }

            $query = ChildAircraftQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAirport($this)
                ->count($con);
        }

        return count($this->collAircrafts);
    }

    /**
     * Method called to associate a ChildAircraft object to this object
     * through the ChildAircraft foreign key attribute.
     *
     * @param  ChildAircraft $l ChildAircraft
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addAircraft(ChildAircraft $l)
    {
        if ($this->collAircrafts === null) {
            $this->initAircrafts();
            $this->collAircraftsPartial = true;
        }

        if (!$this->collAircrafts->contains($l)) {
            $this->doAddAircraft($l);
        }

        return $this;
    }

    /**
     * @param ChildAircraft $aircraft The ChildAircraft object to add.
     */
    protected function doAddAircraft(ChildAircraft $aircraft)
    {
        $this->collAircrafts[]= $aircraft;
        $aircraft->setAirport($this);
    }

    /**
     * @param  ChildAircraft $aircraft The ChildAircraft object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeAircraft(ChildAircraft $aircraft)
    {
        if ($this->getAircrafts()->contains($aircraft)) {
            $pos = $this->collAircrafts->search($aircraft);
            $this->collAircrafts->remove($pos);
            if (null === $this->aircraftsScheduledForDeletion) {
                $this->aircraftsScheduledForDeletion = clone $this->collAircrafts;
                $this->aircraftsScheduledForDeletion->clear();
            }
            $this->aircraftsScheduledForDeletion[]= $aircraft;
            $aircraft->setAirport(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAircraft[] List of ChildAircraft objects
     */
    public function getAircraftsJoinAircraftModel(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAircraftQuery::create(null, $criteria);
        $query->joinWith('AircraftModel', $joinBehavior);

        return $this->getAircrafts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAircraft[] List of ChildAircraft objects
     */
    public function getAircraftsJoinAirline(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAircraftQuery::create(null, $criteria);
        $query->joinWith('Airline', $joinBehavior);

        return $this->getAircrafts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAircraft[] List of ChildAircraft objects
     */
    public function getAircraftsJoinPilot(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAircraftQuery::create(null, $criteria);
        $query->joinWith('Pilot', $joinBehavior);

        return $this->getAircrafts($query, $con);
    }

    /**
     * Clears out the collFlightsRelatedByDestinationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFlightsRelatedByDestinationId()
     */
    public function clearFlightsRelatedByDestinationId()
    {
        $this->collFlightsRelatedByDestinationId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFlightsRelatedByDestinationId collection loaded partially.
     */
    public function resetPartialFlightsRelatedByDestinationId($v = true)
    {
        $this->collFlightsRelatedByDestinationIdPartial = $v;
    }

    /**
     * Initializes the collFlightsRelatedByDestinationId collection.
     *
     * By default this just sets the collFlightsRelatedByDestinationId collection to an empty array (like clearcollFlightsRelatedByDestinationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFlightsRelatedByDestinationId($overrideExisting = true)
    {
        if (null !== $this->collFlightsRelatedByDestinationId && !$overrideExisting) {
            return;
        }
        $this->collFlightsRelatedByDestinationId = new ObjectCollection();
        $this->collFlightsRelatedByDestinationId->setModel('\Model\Flight');
    }

    /**
     * Gets an array of ChildFlight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     * @throws PropelException
     */
    public function getFlightsRelatedByDestinationId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFlightsRelatedByDestinationIdPartial && !$this->isNew();
        if (null === $this->collFlightsRelatedByDestinationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFlightsRelatedByDestinationId) {
                // return empty collection
                $this->initFlightsRelatedByDestinationId();
            } else {
                $collFlightsRelatedByDestinationId = ChildFlightQuery::create(null, $criteria)
                    ->filterByDestination($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFlightsRelatedByDestinationIdPartial && count($collFlightsRelatedByDestinationId)) {
                        $this->initFlightsRelatedByDestinationId(false);

                        foreach ($collFlightsRelatedByDestinationId as $obj) {
                            if (false == $this->collFlightsRelatedByDestinationId->contains($obj)) {
                                $this->collFlightsRelatedByDestinationId->append($obj);
                            }
                        }

                        $this->collFlightsRelatedByDestinationIdPartial = true;
                    }

                    return $collFlightsRelatedByDestinationId;
                }

                if ($partial && $this->collFlightsRelatedByDestinationId) {
                    foreach ($this->collFlightsRelatedByDestinationId as $obj) {
                        if ($obj->isNew()) {
                            $collFlightsRelatedByDestinationId[] = $obj;
                        }
                    }
                }

                $this->collFlightsRelatedByDestinationId = $collFlightsRelatedByDestinationId;
                $this->collFlightsRelatedByDestinationIdPartial = false;
            }
        }

        return $this->collFlightsRelatedByDestinationId;
    }

    /**
     * Sets a collection of ChildFlight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $flightsRelatedByDestinationId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setFlightsRelatedByDestinationId(Collection $flightsRelatedByDestinationId, ConnectionInterface $con = null)
    {
        /** @var ChildFlight[] $flightsRelatedByDestinationIdToDelete */
        $flightsRelatedByDestinationIdToDelete = $this->getFlightsRelatedByDestinationId(new Criteria(), $con)->diff($flightsRelatedByDestinationId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->flightsRelatedByDestinationIdScheduledForDeletion = clone $flightsRelatedByDestinationIdToDelete;

        foreach ($flightsRelatedByDestinationIdToDelete as $flightRelatedByDestinationIdRemoved) {
            $flightRelatedByDestinationIdRemoved->setDestination(null);
        }

        $this->collFlightsRelatedByDestinationId = null;
        foreach ($flightsRelatedByDestinationId as $flightRelatedByDestinationId) {
            $this->addFlightRelatedByDestinationId($flightRelatedByDestinationId);
        }

        $this->collFlightsRelatedByDestinationId = $flightsRelatedByDestinationId;
        $this->collFlightsRelatedByDestinationIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Flight objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Flight objects.
     * @throws PropelException
     */
    public function countFlightsRelatedByDestinationId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFlightsRelatedByDestinationIdPartial && !$this->isNew();
        if (null === $this->collFlightsRelatedByDestinationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFlightsRelatedByDestinationId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFlightsRelatedByDestinationId());
            }

            $query = ChildFlightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDestination($this)
                ->count($con);
        }

        return count($this->collFlightsRelatedByDestinationId);
    }

    /**
     * Method called to associate a ChildFlight object to this object
     * through the ChildFlight foreign key attribute.
     *
     * @param  ChildFlight $l ChildFlight
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addFlightRelatedByDestinationId(ChildFlight $l)
    {
        if ($this->collFlightsRelatedByDestinationId === null) {
            $this->initFlightsRelatedByDestinationId();
            $this->collFlightsRelatedByDestinationIdPartial = true;
        }

        if (!$this->collFlightsRelatedByDestinationId->contains($l)) {
            $this->doAddFlightRelatedByDestinationId($l);
        }

        return $this;
    }

    /**
     * @param ChildFlight $flightRelatedByDestinationId The ChildFlight object to add.
     */
    protected function doAddFlightRelatedByDestinationId(ChildFlight $flightRelatedByDestinationId)
    {
        $this->collFlightsRelatedByDestinationId[]= $flightRelatedByDestinationId;
        $flightRelatedByDestinationId->setDestination($this);
    }

    /**
     * @param  ChildFlight $flightRelatedByDestinationId The ChildFlight object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeFlightRelatedByDestinationId(ChildFlight $flightRelatedByDestinationId)
    {
        if ($this->getFlightsRelatedByDestinationId()->contains($flightRelatedByDestinationId)) {
            $pos = $this->collFlightsRelatedByDestinationId->search($flightRelatedByDestinationId);
            $this->collFlightsRelatedByDestinationId->remove($pos);
            if (null === $this->flightsRelatedByDestinationIdScheduledForDeletion) {
                $this->flightsRelatedByDestinationIdScheduledForDeletion = clone $this->collFlightsRelatedByDestinationId;
                $this->flightsRelatedByDestinationIdScheduledForDeletion->clear();
            }
            $this->flightsRelatedByDestinationIdScheduledForDeletion[]= clone $flightRelatedByDestinationId;
            $flightRelatedByDestinationId->setDestination(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FlightsRelatedByDestinationId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsRelatedByDestinationIdJoinAircraft(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Aircraft', $joinBehavior);

        return $this->getFlightsRelatedByDestinationId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FlightsRelatedByDestinationId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsRelatedByDestinationIdJoinAirline(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Airline', $joinBehavior);

        return $this->getFlightsRelatedByDestinationId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FlightsRelatedByDestinationId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsRelatedByDestinationIdJoinPilot(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Pilot', $joinBehavior);

        return $this->getFlightsRelatedByDestinationId($query, $con);
    }

    /**
     * Clears out the collFlightsRelatedByDepartureId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFlightsRelatedByDepartureId()
     */
    public function clearFlightsRelatedByDepartureId()
    {
        $this->collFlightsRelatedByDepartureId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFlightsRelatedByDepartureId collection loaded partially.
     */
    public function resetPartialFlightsRelatedByDepartureId($v = true)
    {
        $this->collFlightsRelatedByDepartureIdPartial = $v;
    }

    /**
     * Initializes the collFlightsRelatedByDepartureId collection.
     *
     * By default this just sets the collFlightsRelatedByDepartureId collection to an empty array (like clearcollFlightsRelatedByDepartureId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFlightsRelatedByDepartureId($overrideExisting = true)
    {
        if (null !== $this->collFlightsRelatedByDepartureId && !$overrideExisting) {
            return;
        }
        $this->collFlightsRelatedByDepartureId = new ObjectCollection();
        $this->collFlightsRelatedByDepartureId->setModel('\Model\Flight');
    }

    /**
     * Gets an array of ChildFlight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     * @throws PropelException
     */
    public function getFlightsRelatedByDepartureId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFlightsRelatedByDepartureIdPartial && !$this->isNew();
        if (null === $this->collFlightsRelatedByDepartureId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFlightsRelatedByDepartureId) {
                // return empty collection
                $this->initFlightsRelatedByDepartureId();
            } else {
                $collFlightsRelatedByDepartureId = ChildFlightQuery::create(null, $criteria)
                    ->filterByDeparture($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFlightsRelatedByDepartureIdPartial && count($collFlightsRelatedByDepartureId)) {
                        $this->initFlightsRelatedByDepartureId(false);

                        foreach ($collFlightsRelatedByDepartureId as $obj) {
                            if (false == $this->collFlightsRelatedByDepartureId->contains($obj)) {
                                $this->collFlightsRelatedByDepartureId->append($obj);
                            }
                        }

                        $this->collFlightsRelatedByDepartureIdPartial = true;
                    }

                    return $collFlightsRelatedByDepartureId;
                }

                if ($partial && $this->collFlightsRelatedByDepartureId) {
                    foreach ($this->collFlightsRelatedByDepartureId as $obj) {
                        if ($obj->isNew()) {
                            $collFlightsRelatedByDepartureId[] = $obj;
                        }
                    }
                }

                $this->collFlightsRelatedByDepartureId = $collFlightsRelatedByDepartureId;
                $this->collFlightsRelatedByDepartureIdPartial = false;
            }
        }

        return $this->collFlightsRelatedByDepartureId;
    }

    /**
     * Sets a collection of ChildFlight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $flightsRelatedByDepartureId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setFlightsRelatedByDepartureId(Collection $flightsRelatedByDepartureId, ConnectionInterface $con = null)
    {
        /** @var ChildFlight[] $flightsRelatedByDepartureIdToDelete */
        $flightsRelatedByDepartureIdToDelete = $this->getFlightsRelatedByDepartureId(new Criteria(), $con)->diff($flightsRelatedByDepartureId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->flightsRelatedByDepartureIdScheduledForDeletion = clone $flightsRelatedByDepartureIdToDelete;

        foreach ($flightsRelatedByDepartureIdToDelete as $flightRelatedByDepartureIdRemoved) {
            $flightRelatedByDepartureIdRemoved->setDeparture(null);
        }

        $this->collFlightsRelatedByDepartureId = null;
        foreach ($flightsRelatedByDepartureId as $flightRelatedByDepartureId) {
            $this->addFlightRelatedByDepartureId($flightRelatedByDepartureId);
        }

        $this->collFlightsRelatedByDepartureId = $flightsRelatedByDepartureId;
        $this->collFlightsRelatedByDepartureIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Flight objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Flight objects.
     * @throws PropelException
     */
    public function countFlightsRelatedByDepartureId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFlightsRelatedByDepartureIdPartial && !$this->isNew();
        if (null === $this->collFlightsRelatedByDepartureId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFlightsRelatedByDepartureId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFlightsRelatedByDepartureId());
            }

            $query = ChildFlightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDeparture($this)
                ->count($con);
        }

        return count($this->collFlightsRelatedByDepartureId);
    }

    /**
     * Method called to associate a ChildFlight object to this object
     * through the ChildFlight foreign key attribute.
     *
     * @param  ChildFlight $l ChildFlight
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addFlightRelatedByDepartureId(ChildFlight $l)
    {
        if ($this->collFlightsRelatedByDepartureId === null) {
            $this->initFlightsRelatedByDepartureId();
            $this->collFlightsRelatedByDepartureIdPartial = true;
        }

        if (!$this->collFlightsRelatedByDepartureId->contains($l)) {
            $this->doAddFlightRelatedByDepartureId($l);
        }

        return $this;
    }

    /**
     * @param ChildFlight $flightRelatedByDepartureId The ChildFlight object to add.
     */
    protected function doAddFlightRelatedByDepartureId(ChildFlight $flightRelatedByDepartureId)
    {
        $this->collFlightsRelatedByDepartureId[]= $flightRelatedByDepartureId;
        $flightRelatedByDepartureId->setDeparture($this);
    }

    /**
     * @param  ChildFlight $flightRelatedByDepartureId The ChildFlight object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeFlightRelatedByDepartureId(ChildFlight $flightRelatedByDepartureId)
    {
        if ($this->getFlightsRelatedByDepartureId()->contains($flightRelatedByDepartureId)) {
            $pos = $this->collFlightsRelatedByDepartureId->search($flightRelatedByDepartureId);
            $this->collFlightsRelatedByDepartureId->remove($pos);
            if (null === $this->flightsRelatedByDepartureIdScheduledForDeletion) {
                $this->flightsRelatedByDepartureIdScheduledForDeletion = clone $this->collFlightsRelatedByDepartureId;
                $this->flightsRelatedByDepartureIdScheduledForDeletion->clear();
            }
            $this->flightsRelatedByDepartureIdScheduledForDeletion[]= clone $flightRelatedByDepartureId;
            $flightRelatedByDepartureId->setDeparture(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FlightsRelatedByDepartureId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsRelatedByDepartureIdJoinAircraft(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Aircraft', $joinBehavior);

        return $this->getFlightsRelatedByDepartureId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FlightsRelatedByDepartureId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsRelatedByDepartureIdJoinAirline(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Airline', $joinBehavior);

        return $this->getFlightsRelatedByDepartureId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FlightsRelatedByDepartureId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsRelatedByDepartureIdJoinPilot(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Pilot', $joinBehavior);

        return $this->getFlightsRelatedByDepartureId($query, $con);
    }

    /**
     * Clears out the collAirwaysRelatedByDestinationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAirwaysRelatedByDestinationId()
     */
    public function clearAirwaysRelatedByDestinationId()
    {
        $this->collAirwaysRelatedByDestinationId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAirwaysRelatedByDestinationId collection loaded partially.
     */
    public function resetPartialAirwaysRelatedByDestinationId($v = true)
    {
        $this->collAirwaysRelatedByDestinationIdPartial = $v;
    }

    /**
     * Initializes the collAirwaysRelatedByDestinationId collection.
     *
     * By default this just sets the collAirwaysRelatedByDestinationId collection to an empty array (like clearcollAirwaysRelatedByDestinationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAirwaysRelatedByDestinationId($overrideExisting = true)
    {
        if (null !== $this->collAirwaysRelatedByDestinationId && !$overrideExisting) {
            return;
        }
        $this->collAirwaysRelatedByDestinationId = new ObjectCollection();
        $this->collAirwaysRelatedByDestinationId->setModel('\Model\Airway');
    }

    /**
     * Gets an array of ChildAirway objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAirway[] List of ChildAirway objects
     * @throws PropelException
     */
    public function getAirwaysRelatedByDestinationId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAirwaysRelatedByDestinationIdPartial && !$this->isNew();
        if (null === $this->collAirwaysRelatedByDestinationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAirwaysRelatedByDestinationId) {
                // return empty collection
                $this->initAirwaysRelatedByDestinationId();
            } else {
                $collAirwaysRelatedByDestinationId = ChildAirwayQuery::create(null, $criteria)
                    ->filterByDestination($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAirwaysRelatedByDestinationIdPartial && count($collAirwaysRelatedByDestinationId)) {
                        $this->initAirwaysRelatedByDestinationId(false);

                        foreach ($collAirwaysRelatedByDestinationId as $obj) {
                            if (false == $this->collAirwaysRelatedByDestinationId->contains($obj)) {
                                $this->collAirwaysRelatedByDestinationId->append($obj);
                            }
                        }

                        $this->collAirwaysRelatedByDestinationIdPartial = true;
                    }

                    return $collAirwaysRelatedByDestinationId;
                }

                if ($partial && $this->collAirwaysRelatedByDestinationId) {
                    foreach ($this->collAirwaysRelatedByDestinationId as $obj) {
                        if ($obj->isNew()) {
                            $collAirwaysRelatedByDestinationId[] = $obj;
                        }
                    }
                }

                $this->collAirwaysRelatedByDestinationId = $collAirwaysRelatedByDestinationId;
                $this->collAirwaysRelatedByDestinationIdPartial = false;
            }
        }

        return $this->collAirwaysRelatedByDestinationId;
    }

    /**
     * Sets a collection of ChildAirway objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $airwaysRelatedByDestinationId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setAirwaysRelatedByDestinationId(Collection $airwaysRelatedByDestinationId, ConnectionInterface $con = null)
    {
        /** @var ChildAirway[] $airwaysRelatedByDestinationIdToDelete */
        $airwaysRelatedByDestinationIdToDelete = $this->getAirwaysRelatedByDestinationId(new Criteria(), $con)->diff($airwaysRelatedByDestinationId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->airwaysRelatedByDestinationIdScheduledForDeletion = clone $airwaysRelatedByDestinationIdToDelete;

        foreach ($airwaysRelatedByDestinationIdToDelete as $airwayRelatedByDestinationIdRemoved) {
            $airwayRelatedByDestinationIdRemoved->setDestination(null);
        }

        $this->collAirwaysRelatedByDestinationId = null;
        foreach ($airwaysRelatedByDestinationId as $airwayRelatedByDestinationId) {
            $this->addAirwayRelatedByDestinationId($airwayRelatedByDestinationId);
        }

        $this->collAirwaysRelatedByDestinationId = $airwaysRelatedByDestinationId;
        $this->collAirwaysRelatedByDestinationIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Airway objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Airway objects.
     * @throws PropelException
     */
    public function countAirwaysRelatedByDestinationId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAirwaysRelatedByDestinationIdPartial && !$this->isNew();
        if (null === $this->collAirwaysRelatedByDestinationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAirwaysRelatedByDestinationId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAirwaysRelatedByDestinationId());
            }

            $query = ChildAirwayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDestination($this)
                ->count($con);
        }

        return count($this->collAirwaysRelatedByDestinationId);
    }

    /**
     * Method called to associate a ChildAirway object to this object
     * through the ChildAirway foreign key attribute.
     *
     * @param  ChildAirway $l ChildAirway
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addAirwayRelatedByDestinationId(ChildAirway $l)
    {
        if ($this->collAirwaysRelatedByDestinationId === null) {
            $this->initAirwaysRelatedByDestinationId();
            $this->collAirwaysRelatedByDestinationIdPartial = true;
        }

        if (!$this->collAirwaysRelatedByDestinationId->contains($l)) {
            $this->doAddAirwayRelatedByDestinationId($l);
        }

        return $this;
    }

    /**
     * @param ChildAirway $airwayRelatedByDestinationId The ChildAirway object to add.
     */
    protected function doAddAirwayRelatedByDestinationId(ChildAirway $airwayRelatedByDestinationId)
    {
        $this->collAirwaysRelatedByDestinationId[]= $airwayRelatedByDestinationId;
        $airwayRelatedByDestinationId->setDestination($this);
    }

    /**
     * @param  ChildAirway $airwayRelatedByDestinationId The ChildAirway object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeAirwayRelatedByDestinationId(ChildAirway $airwayRelatedByDestinationId)
    {
        if ($this->getAirwaysRelatedByDestinationId()->contains($airwayRelatedByDestinationId)) {
            $pos = $this->collAirwaysRelatedByDestinationId->search($airwayRelatedByDestinationId);
            $this->collAirwaysRelatedByDestinationId->remove($pos);
            if (null === $this->airwaysRelatedByDestinationIdScheduledForDeletion) {
                $this->airwaysRelatedByDestinationIdScheduledForDeletion = clone $this->collAirwaysRelatedByDestinationId;
                $this->airwaysRelatedByDestinationIdScheduledForDeletion->clear();
            }
            $this->airwaysRelatedByDestinationIdScheduledForDeletion[]= clone $airwayRelatedByDestinationId;
            $airwayRelatedByDestinationId->setDestination(null);
        }

        return $this;
    }

    /**
     * Clears out the collAirwaysRelatedByDepartureId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAirwaysRelatedByDepartureId()
     */
    public function clearAirwaysRelatedByDepartureId()
    {
        $this->collAirwaysRelatedByDepartureId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAirwaysRelatedByDepartureId collection loaded partially.
     */
    public function resetPartialAirwaysRelatedByDepartureId($v = true)
    {
        $this->collAirwaysRelatedByDepartureIdPartial = $v;
    }

    /**
     * Initializes the collAirwaysRelatedByDepartureId collection.
     *
     * By default this just sets the collAirwaysRelatedByDepartureId collection to an empty array (like clearcollAirwaysRelatedByDepartureId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAirwaysRelatedByDepartureId($overrideExisting = true)
    {
        if (null !== $this->collAirwaysRelatedByDepartureId && !$overrideExisting) {
            return;
        }
        $this->collAirwaysRelatedByDepartureId = new ObjectCollection();
        $this->collAirwaysRelatedByDepartureId->setModel('\Model\Airway');
    }

    /**
     * Gets an array of ChildAirway objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAirway[] List of ChildAirway objects
     * @throws PropelException
     */
    public function getAirwaysRelatedByDepartureId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAirwaysRelatedByDepartureIdPartial && !$this->isNew();
        if (null === $this->collAirwaysRelatedByDepartureId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAirwaysRelatedByDepartureId) {
                // return empty collection
                $this->initAirwaysRelatedByDepartureId();
            } else {
                $collAirwaysRelatedByDepartureId = ChildAirwayQuery::create(null, $criteria)
                    ->filterByDeparture($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAirwaysRelatedByDepartureIdPartial && count($collAirwaysRelatedByDepartureId)) {
                        $this->initAirwaysRelatedByDepartureId(false);

                        foreach ($collAirwaysRelatedByDepartureId as $obj) {
                            if (false == $this->collAirwaysRelatedByDepartureId->contains($obj)) {
                                $this->collAirwaysRelatedByDepartureId->append($obj);
                            }
                        }

                        $this->collAirwaysRelatedByDepartureIdPartial = true;
                    }

                    return $collAirwaysRelatedByDepartureId;
                }

                if ($partial && $this->collAirwaysRelatedByDepartureId) {
                    foreach ($this->collAirwaysRelatedByDepartureId as $obj) {
                        if ($obj->isNew()) {
                            $collAirwaysRelatedByDepartureId[] = $obj;
                        }
                    }
                }

                $this->collAirwaysRelatedByDepartureId = $collAirwaysRelatedByDepartureId;
                $this->collAirwaysRelatedByDepartureIdPartial = false;
            }
        }

        return $this->collAirwaysRelatedByDepartureId;
    }

    /**
     * Sets a collection of ChildAirway objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $airwaysRelatedByDepartureId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setAirwaysRelatedByDepartureId(Collection $airwaysRelatedByDepartureId, ConnectionInterface $con = null)
    {
        /** @var ChildAirway[] $airwaysRelatedByDepartureIdToDelete */
        $airwaysRelatedByDepartureIdToDelete = $this->getAirwaysRelatedByDepartureId(new Criteria(), $con)->diff($airwaysRelatedByDepartureId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->airwaysRelatedByDepartureIdScheduledForDeletion = clone $airwaysRelatedByDepartureIdToDelete;

        foreach ($airwaysRelatedByDepartureIdToDelete as $airwayRelatedByDepartureIdRemoved) {
            $airwayRelatedByDepartureIdRemoved->setDeparture(null);
        }

        $this->collAirwaysRelatedByDepartureId = null;
        foreach ($airwaysRelatedByDepartureId as $airwayRelatedByDepartureId) {
            $this->addAirwayRelatedByDepartureId($airwayRelatedByDepartureId);
        }

        $this->collAirwaysRelatedByDepartureId = $airwaysRelatedByDepartureId;
        $this->collAirwaysRelatedByDepartureIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Airway objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Airway objects.
     * @throws PropelException
     */
    public function countAirwaysRelatedByDepartureId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAirwaysRelatedByDepartureIdPartial && !$this->isNew();
        if (null === $this->collAirwaysRelatedByDepartureId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAirwaysRelatedByDepartureId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAirwaysRelatedByDepartureId());
            }

            $query = ChildAirwayQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDeparture($this)
                ->count($con);
        }

        return count($this->collAirwaysRelatedByDepartureId);
    }

    /**
     * Method called to associate a ChildAirway object to this object
     * through the ChildAirway foreign key attribute.
     *
     * @param  ChildAirway $l ChildAirway
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addAirwayRelatedByDepartureId(ChildAirway $l)
    {
        if ($this->collAirwaysRelatedByDepartureId === null) {
            $this->initAirwaysRelatedByDepartureId();
            $this->collAirwaysRelatedByDepartureIdPartial = true;
        }

        if (!$this->collAirwaysRelatedByDepartureId->contains($l)) {
            $this->doAddAirwayRelatedByDepartureId($l);
        }

        return $this;
    }

    /**
     * @param ChildAirway $airwayRelatedByDepartureId The ChildAirway object to add.
     */
    protected function doAddAirwayRelatedByDepartureId(ChildAirway $airwayRelatedByDepartureId)
    {
        $this->collAirwaysRelatedByDepartureId[]= $airwayRelatedByDepartureId;
        $airwayRelatedByDepartureId->setDeparture($this);
    }

    /**
     * @param  ChildAirway $airwayRelatedByDepartureId The ChildAirway object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeAirwayRelatedByDepartureId(ChildAirway $airwayRelatedByDepartureId)
    {
        if ($this->getAirwaysRelatedByDepartureId()->contains($airwayRelatedByDepartureId)) {
            $pos = $this->collAirwaysRelatedByDepartureId->search($airwayRelatedByDepartureId);
            $this->collAirwaysRelatedByDepartureId->remove($pos);
            if (null === $this->airwaysRelatedByDepartureIdScheduledForDeletion) {
                $this->airwaysRelatedByDepartureIdScheduledForDeletion = clone $this->collAirwaysRelatedByDepartureId;
                $this->airwaysRelatedByDepartureIdScheduledForDeletion->clear();
            }
            $this->airwaysRelatedByDepartureIdScheduledForDeletion[]= clone $airwayRelatedByDepartureId;
            $airwayRelatedByDepartureId->setDeparture(null);
        }

        return $this;
    }

    /**
     * Clears out the collFreightsRelatedByDestinationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFreightsRelatedByDestinationId()
     */
    public function clearFreightsRelatedByDestinationId()
    {
        $this->collFreightsRelatedByDestinationId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFreightsRelatedByDestinationId collection loaded partially.
     */
    public function resetPartialFreightsRelatedByDestinationId($v = true)
    {
        $this->collFreightsRelatedByDestinationIdPartial = $v;
    }

    /**
     * Initializes the collFreightsRelatedByDestinationId collection.
     *
     * By default this just sets the collFreightsRelatedByDestinationId collection to an empty array (like clearcollFreightsRelatedByDestinationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFreightsRelatedByDestinationId($overrideExisting = true)
    {
        if (null !== $this->collFreightsRelatedByDestinationId && !$overrideExisting) {
            return;
        }
        $this->collFreightsRelatedByDestinationId = new ObjectCollection();
        $this->collFreightsRelatedByDestinationId->setModel('\Model\Freight');
    }

    /**
     * Gets an array of ChildFreight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     * @throws PropelException
     */
    public function getFreightsRelatedByDestinationId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsRelatedByDestinationIdPartial && !$this->isNew();
        if (null === $this->collFreightsRelatedByDestinationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFreightsRelatedByDestinationId) {
                // return empty collection
                $this->initFreightsRelatedByDestinationId();
            } else {
                $collFreightsRelatedByDestinationId = ChildFreightQuery::create(null, $criteria)
                    ->filterByDestination($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFreightsRelatedByDestinationIdPartial && count($collFreightsRelatedByDestinationId)) {
                        $this->initFreightsRelatedByDestinationId(false);

                        foreach ($collFreightsRelatedByDestinationId as $obj) {
                            if (false == $this->collFreightsRelatedByDestinationId->contains($obj)) {
                                $this->collFreightsRelatedByDestinationId->append($obj);
                            }
                        }

                        $this->collFreightsRelatedByDestinationIdPartial = true;
                    }

                    return $collFreightsRelatedByDestinationId;
                }

                if ($partial && $this->collFreightsRelatedByDestinationId) {
                    foreach ($this->collFreightsRelatedByDestinationId as $obj) {
                        if ($obj->isNew()) {
                            $collFreightsRelatedByDestinationId[] = $obj;
                        }
                    }
                }

                $this->collFreightsRelatedByDestinationId = $collFreightsRelatedByDestinationId;
                $this->collFreightsRelatedByDestinationIdPartial = false;
            }
        }

        return $this->collFreightsRelatedByDestinationId;
    }

    /**
     * Sets a collection of ChildFreight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $freightsRelatedByDestinationId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setFreightsRelatedByDestinationId(Collection $freightsRelatedByDestinationId, ConnectionInterface $con = null)
    {
        /** @var ChildFreight[] $freightsRelatedByDestinationIdToDelete */
        $freightsRelatedByDestinationIdToDelete = $this->getFreightsRelatedByDestinationId(new Criteria(), $con)->diff($freightsRelatedByDestinationId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->freightsRelatedByDestinationIdScheduledForDeletion = clone $freightsRelatedByDestinationIdToDelete;

        foreach ($freightsRelatedByDestinationIdToDelete as $freightRelatedByDestinationIdRemoved) {
            $freightRelatedByDestinationIdRemoved->setDestination(null);
        }

        $this->collFreightsRelatedByDestinationId = null;
        foreach ($freightsRelatedByDestinationId as $freightRelatedByDestinationId) {
            $this->addFreightRelatedByDestinationId($freightRelatedByDestinationId);
        }

        $this->collFreightsRelatedByDestinationId = $freightsRelatedByDestinationId;
        $this->collFreightsRelatedByDestinationIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Freight objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Freight objects.
     * @throws PropelException
     */
    public function countFreightsRelatedByDestinationId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsRelatedByDestinationIdPartial && !$this->isNew();
        if (null === $this->collFreightsRelatedByDestinationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFreightsRelatedByDestinationId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFreightsRelatedByDestinationId());
            }

            $query = ChildFreightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDestination($this)
                ->count($con);
        }

        return count($this->collFreightsRelatedByDestinationId);
    }

    /**
     * Method called to associate a ChildFreight object to this object
     * through the ChildFreight foreign key attribute.
     *
     * @param  ChildFreight $l ChildFreight
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addFreightRelatedByDestinationId(ChildFreight $l)
    {
        if ($this->collFreightsRelatedByDestinationId === null) {
            $this->initFreightsRelatedByDestinationId();
            $this->collFreightsRelatedByDestinationIdPartial = true;
        }

        if (!$this->collFreightsRelatedByDestinationId->contains($l)) {
            $this->doAddFreightRelatedByDestinationId($l);
        }

        return $this;
    }

    /**
     * @param ChildFreight $freightRelatedByDestinationId The ChildFreight object to add.
     */
    protected function doAddFreightRelatedByDestinationId(ChildFreight $freightRelatedByDestinationId)
    {
        $this->collFreightsRelatedByDestinationId[]= $freightRelatedByDestinationId;
        $freightRelatedByDestinationId->setDestination($this);
    }

    /**
     * @param  ChildFreight $freightRelatedByDestinationId The ChildFreight object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeFreightRelatedByDestinationId(ChildFreight $freightRelatedByDestinationId)
    {
        if ($this->getFreightsRelatedByDestinationId()->contains($freightRelatedByDestinationId)) {
            $pos = $this->collFreightsRelatedByDestinationId->search($freightRelatedByDestinationId);
            $this->collFreightsRelatedByDestinationId->remove($pos);
            if (null === $this->freightsRelatedByDestinationIdScheduledForDeletion) {
                $this->freightsRelatedByDestinationIdScheduledForDeletion = clone $this->collFreightsRelatedByDestinationId;
                $this->freightsRelatedByDestinationIdScheduledForDeletion->clear();
            }
            $this->freightsRelatedByDestinationIdScheduledForDeletion[]= clone $freightRelatedByDestinationId;
            $freightRelatedByDestinationId->setDestination(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FreightsRelatedByDestinationId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     */
    public function getFreightsRelatedByDestinationIdJoinOnFlight(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFreightQuery::create(null, $criteria);
        $query->joinWith('OnFlight', $joinBehavior);

        return $this->getFreightsRelatedByDestinationId($query, $con);
    }

    /**
     * Clears out the collFreightsRelatedByDepartureId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFreightsRelatedByDepartureId()
     */
    public function clearFreightsRelatedByDepartureId()
    {
        $this->collFreightsRelatedByDepartureId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFreightsRelatedByDepartureId collection loaded partially.
     */
    public function resetPartialFreightsRelatedByDepartureId($v = true)
    {
        $this->collFreightsRelatedByDepartureIdPartial = $v;
    }

    /**
     * Initializes the collFreightsRelatedByDepartureId collection.
     *
     * By default this just sets the collFreightsRelatedByDepartureId collection to an empty array (like clearcollFreightsRelatedByDepartureId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFreightsRelatedByDepartureId($overrideExisting = true)
    {
        if (null !== $this->collFreightsRelatedByDepartureId && !$overrideExisting) {
            return;
        }
        $this->collFreightsRelatedByDepartureId = new ObjectCollection();
        $this->collFreightsRelatedByDepartureId->setModel('\Model\Freight');
    }

    /**
     * Gets an array of ChildFreight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     * @throws PropelException
     */
    public function getFreightsRelatedByDepartureId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsRelatedByDepartureIdPartial && !$this->isNew();
        if (null === $this->collFreightsRelatedByDepartureId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFreightsRelatedByDepartureId) {
                // return empty collection
                $this->initFreightsRelatedByDepartureId();
            } else {
                $collFreightsRelatedByDepartureId = ChildFreightQuery::create(null, $criteria)
                    ->filterByDeparture($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFreightsRelatedByDepartureIdPartial && count($collFreightsRelatedByDepartureId)) {
                        $this->initFreightsRelatedByDepartureId(false);

                        foreach ($collFreightsRelatedByDepartureId as $obj) {
                            if (false == $this->collFreightsRelatedByDepartureId->contains($obj)) {
                                $this->collFreightsRelatedByDepartureId->append($obj);
                            }
                        }

                        $this->collFreightsRelatedByDepartureIdPartial = true;
                    }

                    return $collFreightsRelatedByDepartureId;
                }

                if ($partial && $this->collFreightsRelatedByDepartureId) {
                    foreach ($this->collFreightsRelatedByDepartureId as $obj) {
                        if ($obj->isNew()) {
                            $collFreightsRelatedByDepartureId[] = $obj;
                        }
                    }
                }

                $this->collFreightsRelatedByDepartureId = $collFreightsRelatedByDepartureId;
                $this->collFreightsRelatedByDepartureIdPartial = false;
            }
        }

        return $this->collFreightsRelatedByDepartureId;
    }

    /**
     * Sets a collection of ChildFreight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $freightsRelatedByDepartureId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setFreightsRelatedByDepartureId(Collection $freightsRelatedByDepartureId, ConnectionInterface $con = null)
    {
        /** @var ChildFreight[] $freightsRelatedByDepartureIdToDelete */
        $freightsRelatedByDepartureIdToDelete = $this->getFreightsRelatedByDepartureId(new Criteria(), $con)->diff($freightsRelatedByDepartureId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->freightsRelatedByDepartureIdScheduledForDeletion = clone $freightsRelatedByDepartureIdToDelete;

        foreach ($freightsRelatedByDepartureIdToDelete as $freightRelatedByDepartureIdRemoved) {
            $freightRelatedByDepartureIdRemoved->setDeparture(null);
        }

        $this->collFreightsRelatedByDepartureId = null;
        foreach ($freightsRelatedByDepartureId as $freightRelatedByDepartureId) {
            $this->addFreightRelatedByDepartureId($freightRelatedByDepartureId);
        }

        $this->collFreightsRelatedByDepartureId = $freightsRelatedByDepartureId;
        $this->collFreightsRelatedByDepartureIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Freight objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Freight objects.
     * @throws PropelException
     */
    public function countFreightsRelatedByDepartureId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsRelatedByDepartureIdPartial && !$this->isNew();
        if (null === $this->collFreightsRelatedByDepartureId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFreightsRelatedByDepartureId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFreightsRelatedByDepartureId());
            }

            $query = ChildFreightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDeparture($this)
                ->count($con);
        }

        return count($this->collFreightsRelatedByDepartureId);
    }

    /**
     * Method called to associate a ChildFreight object to this object
     * through the ChildFreight foreign key attribute.
     *
     * @param  ChildFreight $l ChildFreight
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addFreightRelatedByDepartureId(ChildFreight $l)
    {
        if ($this->collFreightsRelatedByDepartureId === null) {
            $this->initFreightsRelatedByDepartureId();
            $this->collFreightsRelatedByDepartureIdPartial = true;
        }

        if (!$this->collFreightsRelatedByDepartureId->contains($l)) {
            $this->doAddFreightRelatedByDepartureId($l);
        }

        return $this;
    }

    /**
     * @param ChildFreight $freightRelatedByDepartureId The ChildFreight object to add.
     */
    protected function doAddFreightRelatedByDepartureId(ChildFreight $freightRelatedByDepartureId)
    {
        $this->collFreightsRelatedByDepartureId[]= $freightRelatedByDepartureId;
        $freightRelatedByDepartureId->setDeparture($this);
    }

    /**
     * @param  ChildFreight $freightRelatedByDepartureId The ChildFreight object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeFreightRelatedByDepartureId(ChildFreight $freightRelatedByDepartureId)
    {
        if ($this->getFreightsRelatedByDepartureId()->contains($freightRelatedByDepartureId)) {
            $pos = $this->collFreightsRelatedByDepartureId->search($freightRelatedByDepartureId);
            $this->collFreightsRelatedByDepartureId->remove($pos);
            if (null === $this->freightsRelatedByDepartureIdScheduledForDeletion) {
                $this->freightsRelatedByDepartureIdScheduledForDeletion = clone $this->collFreightsRelatedByDepartureId;
                $this->freightsRelatedByDepartureIdScheduledForDeletion->clear();
            }
            $this->freightsRelatedByDepartureIdScheduledForDeletion[]= clone $freightRelatedByDepartureId;
            $freightRelatedByDepartureId->setDeparture(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FreightsRelatedByDepartureId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     */
    public function getFreightsRelatedByDepartureIdJoinOnFlight(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFreightQuery::create(null, $criteria);
        $query->joinWith('OnFlight', $joinBehavior);

        return $this->getFreightsRelatedByDepartureId($query, $con);
    }

    /**
     * Clears out the collFreightsRelatedByLocationId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFreightsRelatedByLocationId()
     */
    public function clearFreightsRelatedByLocationId()
    {
        $this->collFreightsRelatedByLocationId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFreightsRelatedByLocationId collection loaded partially.
     */
    public function resetPartialFreightsRelatedByLocationId($v = true)
    {
        $this->collFreightsRelatedByLocationIdPartial = $v;
    }

    /**
     * Initializes the collFreightsRelatedByLocationId collection.
     *
     * By default this just sets the collFreightsRelatedByLocationId collection to an empty array (like clearcollFreightsRelatedByLocationId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFreightsRelatedByLocationId($overrideExisting = true)
    {
        if (null !== $this->collFreightsRelatedByLocationId && !$overrideExisting) {
            return;
        }
        $this->collFreightsRelatedByLocationId = new ObjectCollection();
        $this->collFreightsRelatedByLocationId->setModel('\Model\Freight');
    }

    /**
     * Gets an array of ChildFreight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     * @throws PropelException
     */
    public function getFreightsRelatedByLocationId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsRelatedByLocationIdPartial && !$this->isNew();
        if (null === $this->collFreightsRelatedByLocationId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFreightsRelatedByLocationId) {
                // return empty collection
                $this->initFreightsRelatedByLocationId();
            } else {
                $collFreightsRelatedByLocationId = ChildFreightQuery::create(null, $criteria)
                    ->filterByLocation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFreightsRelatedByLocationIdPartial && count($collFreightsRelatedByLocationId)) {
                        $this->initFreightsRelatedByLocationId(false);

                        foreach ($collFreightsRelatedByLocationId as $obj) {
                            if (false == $this->collFreightsRelatedByLocationId->contains($obj)) {
                                $this->collFreightsRelatedByLocationId->append($obj);
                            }
                        }

                        $this->collFreightsRelatedByLocationIdPartial = true;
                    }

                    return $collFreightsRelatedByLocationId;
                }

                if ($partial && $this->collFreightsRelatedByLocationId) {
                    foreach ($this->collFreightsRelatedByLocationId as $obj) {
                        if ($obj->isNew()) {
                            $collFreightsRelatedByLocationId[] = $obj;
                        }
                    }
                }

                $this->collFreightsRelatedByLocationId = $collFreightsRelatedByLocationId;
                $this->collFreightsRelatedByLocationIdPartial = false;
            }
        }

        return $this->collFreightsRelatedByLocationId;
    }

    /**
     * Sets a collection of ChildFreight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $freightsRelatedByLocationId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setFreightsRelatedByLocationId(Collection $freightsRelatedByLocationId, ConnectionInterface $con = null)
    {
        /** @var ChildFreight[] $freightsRelatedByLocationIdToDelete */
        $freightsRelatedByLocationIdToDelete = $this->getFreightsRelatedByLocationId(new Criteria(), $con)->diff($freightsRelatedByLocationId);


        $this->freightsRelatedByLocationIdScheduledForDeletion = $freightsRelatedByLocationIdToDelete;

        foreach ($freightsRelatedByLocationIdToDelete as $freightRelatedByLocationIdRemoved) {
            $freightRelatedByLocationIdRemoved->setLocation(null);
        }

        $this->collFreightsRelatedByLocationId = null;
        foreach ($freightsRelatedByLocationId as $freightRelatedByLocationId) {
            $this->addFreightRelatedByLocationId($freightRelatedByLocationId);
        }

        $this->collFreightsRelatedByLocationId = $freightsRelatedByLocationId;
        $this->collFreightsRelatedByLocationIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Freight objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Freight objects.
     * @throws PropelException
     */
    public function countFreightsRelatedByLocationId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsRelatedByLocationIdPartial && !$this->isNew();
        if (null === $this->collFreightsRelatedByLocationId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFreightsRelatedByLocationId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFreightsRelatedByLocationId());
            }

            $query = ChildFreightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocation($this)
                ->count($con);
        }

        return count($this->collFreightsRelatedByLocationId);
    }

    /**
     * Method called to associate a ChildFreight object to this object
     * through the ChildFreight foreign key attribute.
     *
     * @param  ChildFreight $l ChildFreight
     * @return $this|\Model\Airport The current object (for fluent API support)
     */
    public function addFreightRelatedByLocationId(ChildFreight $l)
    {
        if ($this->collFreightsRelatedByLocationId === null) {
            $this->initFreightsRelatedByLocationId();
            $this->collFreightsRelatedByLocationIdPartial = true;
        }

        if (!$this->collFreightsRelatedByLocationId->contains($l)) {
            $this->doAddFreightRelatedByLocationId($l);
        }

        return $this;
    }

    /**
     * @param ChildFreight $freightRelatedByLocationId The ChildFreight object to add.
     */
    protected function doAddFreightRelatedByLocationId(ChildFreight $freightRelatedByLocationId)
    {
        $this->collFreightsRelatedByLocationId[]= $freightRelatedByLocationId;
        $freightRelatedByLocationId->setLocation($this);
    }

    /**
     * @param  ChildFreight $freightRelatedByLocationId The ChildFreight object to remove.
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function removeFreightRelatedByLocationId(ChildFreight $freightRelatedByLocationId)
    {
        if ($this->getFreightsRelatedByLocationId()->contains($freightRelatedByLocationId)) {
            $pos = $this->collFreightsRelatedByLocationId->search($freightRelatedByLocationId);
            $this->collFreightsRelatedByLocationId->remove($pos);
            if (null === $this->freightsRelatedByLocationIdScheduledForDeletion) {
                $this->freightsRelatedByLocationIdScheduledForDeletion = clone $this->collFreightsRelatedByLocationId;
                $this->freightsRelatedByLocationIdScheduledForDeletion->clear();
            }
            $this->freightsRelatedByLocationIdScheduledForDeletion[]= $freightRelatedByLocationId;
            $freightRelatedByLocationId->setLocation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airport is new, it will return
     * an empty collection; or if this Airport has previously
     * been saved, it will retrieve related FreightsRelatedByLocationId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airport.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     */
    public function getFreightsRelatedByLocationIdJoinOnFlight(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFreightQuery::create(null, $criteria);
        $query->joinWith('OnFlight', $joinBehavior);

        return $this->getFreightsRelatedByLocationId($query, $con);
    }

    /**
     * Gets a single ChildFreightGeneration object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildFreightGeneration
     * @throws PropelException
     */
    public function getFreightGeneration(ConnectionInterface $con = null)
    {

        if ($this->singleFreightGeneration === null && !$this->isNew()) {
            $this->singleFreightGeneration = ChildFreightGenerationQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleFreightGeneration;
    }

    /**
     * Sets a single ChildFreightGeneration object as related to this object by a one-to-one relationship.
     *
     * @param  ChildFreightGeneration $v ChildFreightGeneration
     * @return $this|\Model\Airport The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFreightGeneration(ChildFreightGeneration $v = null)
    {
        $this->singleFreightGeneration = $v;

        // Make sure that that the passed-in ChildFreightGeneration isn't already associated with this object
        if ($v !== null && $v->getAirport(null, false) === null) {
            $v->setAirport($this);
        }

        return $this;
    }

    /**
     * Clears out the collDepartures collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDepartures()
     */
    public function clearDepartures()
    {
        $this->collDepartures = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collDepartures crossRef collection.
     *
     * By default this just sets the collDepartures collection to an empty collection (like clearDepartures());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initDepartures()
    {
        $this->collDepartures = new ObjectCollection();
        $this->collDeparturesPartial = true;

        $this->collDepartures->setModel('\Model\Airport');
    }

    /**
     * Checks if the collDepartures collection is loaded.
     *
     * @return bool
     */
    public function isDeparturesLoaded()
    {
        return null !== $this->collDepartures;
    }

    /**
     * Gets a collection of ChildAirport objects related by a many-to-many relationship
     * to the current object by way of the airways cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildAirport[] List of ChildAirport objects
     */
    public function getDepartures(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDeparturesPartial && !$this->isNew();
        if (null === $this->collDepartures || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collDepartures) {
                    $this->initDepartures();
                }
            } else {

                $query = ChildAirportQuery::create(null, $criteria)
                    ->filterByDestination($this);
                $collDepartures = $query->find($con);
                if (null !== $criteria) {
                    return $collDepartures;
                }

                if ($partial && $this->collDepartures) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collDepartures as $obj) {
                        if (!$collDepartures->contains($obj)) {
                            $collDepartures[] = $obj;
                        }
                    }
                }

                $this->collDepartures = $collDepartures;
                $this->collDeparturesPartial = false;
            }
        }

        return $this->collDepartures;
    }

    /**
     * Sets a collection of Airport objects related by a many-to-many relationship
     * to the current object by way of the airways cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $departures A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setDepartures(Collection $departures, ConnectionInterface $con = null)
    {
        $this->clearDepartures();
        $currentDepartures = $this->getDepartures();

        $departuresScheduledForDeletion = $currentDepartures->diff($departures);

        foreach ($departuresScheduledForDeletion as $toDelete) {
            $this->removeDeparture($toDelete);
        }

        foreach ($departures as $departure) {
            if (!$currentDepartures->contains($departure)) {
                $this->doAddDeparture($departure);
            }
        }

        $this->collDeparturesPartial = false;
        $this->collDepartures = $departures;

        return $this;
    }

    /**
     * Gets the number of Airport objects related by a many-to-many relationship
     * to the current object by way of the airways cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Airport objects
     */
    public function countDepartures(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDeparturesPartial && !$this->isNew();
        if (null === $this->collDepartures || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDepartures) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getDepartures());
                }

                $query = ChildAirportQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByDestination($this)
                    ->count($con);
            }
        } else {
            return count($this->collDepartures);
        }
    }

    /**
     * Associate a ChildAirport to this object
     * through the airways cross reference table.
     *
     * @param ChildAirport $departure
     * @return ChildAirport The current object (for fluent API support)
     */
    public function addDeparture(ChildAirport $departure)
    {
        if ($this->collDepartures === null) {
            $this->initDepartures();
        }

        if (!$this->getDepartures()->contains($departure)) {
            // only add it if the **same** object is not already associated
            $this->collDepartures->push($departure);
            $this->doAddDeparture($departure);
        }

        return $this;
    }

    /**
     *
     * @param ChildAirport $departure
     */
    protected function doAddDeparture(ChildAirport $departure)
    {
        $airway = new ChildAirway();

        $airway->setDeparture($departure);

        $airway->setDestination($this);

        $this->addAirwayRelatedByDestinationId($airway);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$departure->isDestinationsLoaded()) {
            $departure->initDestinations();
            $departure->getDestinations()->push($this);
        } elseif (!$departure->getDestinations()->contains($this)) {
            $departure->getDestinations()->push($this);
        }

    }

    /**
     * Remove departure of this object
     * through the airways cross reference table.
     *
     * @param ChildAirport $departure
     * @return ChildAirport The current object (for fluent API support)
     */
    public function removeDeparture(ChildAirport $departure)
    {
        if ($this->getDepartures()->contains($departure)) { $airway = new ChildAirway();

            $airway->setDeparture($departure);
            if ($departure->isDestinationsLoaded()) {
                //remove the back reference if available
                $departure->getDestinations()->removeObject($this);
            }

            $airway->setDestination($this);
            $this->removeAirwayRelatedByDestinationId(clone $airway);
            $airway->clear();

            $this->collDepartures->remove($this->collDepartures->search($departure));

            if (null === $this->departuresScheduledForDeletion) {
                $this->departuresScheduledForDeletion = clone $this->collDepartures;
                $this->departuresScheduledForDeletion->clear();
            }

            $this->departuresScheduledForDeletion->push($departure);
        }


        return $this;
    }

    /**
     * Clears out the collDestinations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDestinations()
     */
    public function clearDestinations()
    {
        $this->collDestinations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collDestinations crossRef collection.
     *
     * By default this just sets the collDestinations collection to an empty collection (like clearDestinations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initDestinations()
    {
        $this->collDestinations = new ObjectCollection();
        $this->collDestinationsPartial = true;

        $this->collDestinations->setModel('\Model\Airport');
    }

    /**
     * Checks if the collDestinations collection is loaded.
     *
     * @return bool
     */
    public function isDestinationsLoaded()
    {
        return null !== $this->collDestinations;
    }

    /**
     * Gets a collection of ChildAirport objects related by a many-to-many relationship
     * to the current object by way of the airways cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirport is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildAirport[] List of ChildAirport objects
     */
    public function getDestinations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDestinationsPartial && !$this->isNew();
        if (null === $this->collDestinations || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collDestinations) {
                    $this->initDestinations();
                }
            } else {

                $query = ChildAirportQuery::create(null, $criteria)
                    ->filterByDeparture($this);
                $collDestinations = $query->find($con);
                if (null !== $criteria) {
                    return $collDestinations;
                }

                if ($partial && $this->collDestinations) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collDestinations as $obj) {
                        if (!$collDestinations->contains($obj)) {
                            $collDestinations[] = $obj;
                        }
                    }
                }

                $this->collDestinations = $collDestinations;
                $this->collDestinationsPartial = false;
            }
        }

        return $this->collDestinations;
    }

    /**
     * Sets a collection of Airport objects related by a many-to-many relationship
     * to the current object by way of the airways cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $destinations A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildAirport The current object (for fluent API support)
     */
    public function setDestinations(Collection $destinations, ConnectionInterface $con = null)
    {
        $this->clearDestinations();
        $currentDestinations = $this->getDestinations();

        $destinationsScheduledForDeletion = $currentDestinations->diff($destinations);

        foreach ($destinationsScheduledForDeletion as $toDelete) {
            $this->removeDestination($toDelete);
        }

        foreach ($destinations as $destination) {
            if (!$currentDestinations->contains($destination)) {
                $this->doAddDestination($destination);
            }
        }

        $this->collDestinationsPartial = false;
        $this->collDestinations = $destinations;

        return $this;
    }

    /**
     * Gets the number of Airport objects related by a many-to-many relationship
     * to the current object by way of the airways cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Airport objects
     */
    public function countDestinations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDestinationsPartial && !$this->isNew();
        if (null === $this->collDestinations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDestinations) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getDestinations());
                }

                $query = ChildAirportQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByDeparture($this)
                    ->count($con);
            }
        } else {
            return count($this->collDestinations);
        }
    }

    /**
     * Associate a ChildAirport to this object
     * through the airways cross reference table.
     *
     * @param ChildAirport $destination
     * @return ChildAirport The current object (for fluent API support)
     */
    public function addDestination(ChildAirport $destination)
    {
        if ($this->collDestinations === null) {
            $this->initDestinations();
        }

        if (!$this->getDestinations()->contains($destination)) {
            // only add it if the **same** object is not already associated
            $this->collDestinations->push($destination);
            $this->doAddDestination($destination);
        }

        return $this;
    }

    /**
     *
     * @param ChildAirport $destination
     */
    protected function doAddDestination(ChildAirport $destination)
    {
        $airway = new ChildAirway();

        $airway->setDestination($destination);

        $airway->setDeparture($this);

        $this->addAirwayRelatedByDepartureId($airway);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$destination->isDeparturesLoaded()) {
            $destination->initDepartures();
            $destination->getDepartures()->push($this);
        } elseif (!$destination->getDepartures()->contains($this)) {
            $destination->getDepartures()->push($this);
        }

    }

    /**
     * Remove destination of this object
     * through the airways cross reference table.
     *
     * @param ChildAirport $destination
     * @return ChildAirport The current object (for fluent API support)
     */
    public function removeDestination(ChildAirport $destination)
    {
        if ($this->getDestinations()->contains($destination)) { $airway = new ChildAirway();

            $airway->setDestination($destination);
            if ($destination->isDeparturesLoaded()) {
                //remove the back reference if available
                $destination->getDepartures()->removeObject($this);
            }

            $airway->setDeparture($this);
            $this->removeAirwayRelatedByDepartureId(clone $airway);
            $airway->clear();

            $this->collDestinations->remove($this->collDestinations->search($destination));

            if (null === $this->destinationsScheduledForDeletion) {
                $this->destinationsScheduledForDeletion = clone $this->collDestinations;
                $this->destinationsScheduledForDeletion->clear();
            }

            $this->destinationsScheduledForDeletion->push($destination);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->city = null;
        $this->country = null;
        $this->iata = null;
        $this->icao = null;
        $this->altitude = null;
        $this->timezone = null;
        $this->dst = null;
        $this->size = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collAircrafts) {
                foreach ($this->collAircrafts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFlightsRelatedByDestinationId) {
                foreach ($this->collFlightsRelatedByDestinationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFlightsRelatedByDepartureId) {
                foreach ($this->collFlightsRelatedByDepartureId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAirwaysRelatedByDestinationId) {
                foreach ($this->collAirwaysRelatedByDestinationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAirwaysRelatedByDepartureId) {
                foreach ($this->collAirwaysRelatedByDepartureId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFreightsRelatedByDestinationId) {
                foreach ($this->collFreightsRelatedByDestinationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFreightsRelatedByDepartureId) {
                foreach ($this->collFreightsRelatedByDepartureId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFreightsRelatedByLocationId) {
                foreach ($this->collFreightsRelatedByLocationId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleFreightGeneration) {
                $this->singleFreightGeneration->clearAllReferences($deep);
            }
            if ($this->collDepartures) {
                foreach ($this->collDepartures as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDestinations) {
                foreach ($this->collDestinations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAircrafts = null;
        $this->collFlightsRelatedByDestinationId = null;
        $this->collFlightsRelatedByDepartureId = null;
        $this->collAirwaysRelatedByDestinationId = null;
        $this->collAirwaysRelatedByDepartureId = null;
        $this->collFreightsRelatedByDestinationId = null;
        $this->collFreightsRelatedByDepartureId = null;
        $this->collFreightsRelatedByLocationId = null;
        $this->singleFreightGeneration = null;
        $this->collDepartures = null;
        $this->collDestinations = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AirportTableMap::DEFAULT_STRING_FORMAT);
    }

    // geocodable behavior

    /**
     * Convenient method to set latitude and longitude values.
     *
     * @param double $latitude     A latitude value.
     * @param double $longitude    A longitude value.
     */
    public function setCoordinates($latitude, $longitude)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    /**
     * Returns an array with latitude and longitude values.
     *
     * @return array
     */
    public function getCoordinates()
    {
        return array(
            'latitude'  => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        );
    }

    /**
     * Returns whether this object has been geocoded or not.
     *
     * @return boolean
     */
    public function isGeocoded()
    {
        $lat = $this->getLatitude();
        $lng = $this->getLongitude();

        return (!empty($lat) && !empty($lng));
    }

    /**
     * Calculates the distance between a given airport and this one.
     *
     * @param \Model\Airport $airport    A \Model\Airport object.
     * @param double $unit     The unit measure.
     *
     * @return double   The distance between the two objects.
     */
    public function getDistanceTo(\Model\Airport $airport, $unit = AirportTableMap::KILOMETERS_UNIT)
    {
        $dist = rad2deg(acos(sin(deg2rad($this->getLatitude())) * sin(deg2rad($airport->getLatitude())) +  cos(deg2rad($this->getLatitude())) * cos(deg2rad($airport->getLatitude())) * cos(deg2rad($this->getLongitude() - $airport->getLongitude())))) * 60 * AirportTableMap::MILES_UNIT;

        if (AirportTableMap::MILES_UNIT === $unit) {
            return $dist;
        } elseif (AirportTableMap::NAUTICAL_MILES_UNIT === $unit) {
            return $dist * AirportTableMap::NAUTICAL_MILES_UNIT;
        }

        return $dist * AirportTableMap::KILOMETERS_UNIT;
    }

    /**
     * Update geocode information.
     * You can extend this method to fill in other fields.
     *
     * @return \Geocoder\Result\ResultInterface|null
     */
    public function geocode()
    {
        // Do nothing as both 'geocode_ip', and 'geocode_address' are turned off.
        return null;
    }

    /**
     * Check whether the current object is required to be geocoded (again).
     *
     * @return boolean
     */
    public function isGeocodingNecessary()
    {

        return false;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}

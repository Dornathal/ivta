<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Aircraft as ChildAircraft;
use Model\AircraftModel as ChildAircraftModel;
use Model\AircraftModelQuery as ChildAircraftModelQuery;
use Model\AircraftQuery as ChildAircraftQuery;
use Model\Airline as ChildAirline;
use Model\AirlineQuery as ChildAirlineQuery;
use Model\Airport as ChildAirport;
use Model\AirportQuery as ChildAirportQuery;
use Model\Flight as ChildFlight;
use Model\FlightQuery as ChildFlightQuery;
use Model\Pilot as ChildPilot;
use Model\PilotQuery as ChildPilotQuery;
use Model\Map\AircraftTableMap;
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
 * Base class that represents a row from the 'aircrafts' table.
 *
 *
 *
* @package    propel.generator.Model.Base
*/
abstract class Aircraft implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\AircraftTableMap';


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
     * The value for the aircraft_model_id field.
     * @var        int
     */
    protected $aircraft_model_id;

    /**
     * The value for the airline_id field.
     * @var        int
     */
    protected $airline_id;

    /**
     * The value for the airport_id field.
     * @var        int
     */
    protected $airport_id;

    /**
     * The value for the pilot_id field.
     * @var        int
     */
    protected $pilot_id;

    /**
     * The value for the callsign field.
     * @var        string
     */
    protected $callsign;

    /**
     * The value for the flown_distance field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $flown_distance;

    /**
     * The value for the number_flights field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $number_flights;

    /**
     * The value for the flown_time field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $flown_time;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

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
     * @var        ChildAircraftModel
     */
    protected $aAircraftModel;

    /**
     * @var        ChildAirport
     */
    protected $aAirport;

    /**
     * @var        ChildAirline
     */
    protected $aAirline;

    /**
     * @var        ChildPilot
     */
    protected $aPilot;

    /**
     * @var        ObjectCollection|ChildFlight[] Collection to store aggregation of ChildFlight objects.
     */
    protected $collFlights;
    protected $collFlightsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFlight[]
     */
    protected $flightsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->flown_distance = 0;
        $this->number_flights = 0;
        $this->flown_time = 0;
        $this->status = 0;
    }

    /**
     * Initializes internal state of Model\Base\Aircraft object.
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
     * Compares this with another <code>Aircraft</code> instance.  If
     * <code>obj</code> is an instance of <code>Aircraft</code>, delegates to
     * <code>equals(Aircraft)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Aircraft The current object, for fluid interface
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
     * Get the [aircraft_model_id] column value.
     *
     * @return int
     */
    public function getAircraftModelId()
    {
        return $this->aircraft_model_id;
    }

    /**
     * Get the [airline_id] column value.
     *
     * @return int
     */
    public function getAirlineId()
    {
        return $this->airline_id;
    }

    /**
     * Get the [airport_id] column value.
     *
     * @return int
     */
    public function getAirportId()
    {
        return $this->airport_id;
    }

    /**
     * Get the [pilot_id] column value.
     *
     * @return int
     */
    public function getPilotId()
    {
        return $this->pilot_id;
    }

    /**
     * Get the [callsign] column value.
     *
     * @return string
     */
    public function getCallsign()
    {
        return $this->callsign;
    }

    /**
     * Get the [flown_distance] column value.
     *
     * @return int
     */
    public function getFlownDistance()
    {
        return $this->flown_distance;
    }

    /**
     * Get the [number_flights] column value.
     *
     * @return int
     */
    public function getNumberFlights()
    {
        return $this->number_flights;
    }

    /**
     * Get the [flown_time] column value.
     *
     * @return int
     */
    public function getFlownTime()
    {
        return $this->flown_time;
    }

    /**
     * Get the [status] column value.
     *
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStatus()
    {
        if (null === $this->status) {
            return null;
        }
        $valueSet = AircraftTableMap::getValueSet(AircraftTableMap::COL_STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
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
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AircraftTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [aircraft_model_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setAircraftModelId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->aircraft_model_id !== $v) {
            $this->aircraft_model_id = $v;
            $this->modifiedColumns[AircraftTableMap::COL_AIRCRAFT_MODEL_ID] = true;
        }

        if ($this->aAircraftModel !== null && $this->aAircraftModel->getId() !== $v) {
            $this->aAircraftModel = null;
        }

        return $this;
    } // setAircraftModelId()

    /**
     * Set the value of [airline_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setAirlineId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->airline_id !== $v) {
            $this->airline_id = $v;
            $this->modifiedColumns[AircraftTableMap::COL_AIRLINE_ID] = true;
        }

        if ($this->aAirline !== null && $this->aAirline->getId() !== $v) {
            $this->aAirline = null;
        }

        return $this;
    } // setAirlineId()

    /**
     * Set the value of [airport_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setAirportId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->airport_id !== $v) {
            $this->airport_id = $v;
            $this->modifiedColumns[AircraftTableMap::COL_AIRPORT_ID] = true;
        }

        if ($this->aAirport !== null && $this->aAirport->getId() !== $v) {
            $this->aAirport = null;
        }

        return $this;
    } // setAirportId()

    /**
     * Set the value of [pilot_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setPilotId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pilot_id !== $v) {
            $this->pilot_id = $v;
            $this->modifiedColumns[AircraftTableMap::COL_PILOT_ID] = true;
        }

        if ($this->aPilot !== null && $this->aPilot->getId() !== $v) {
            $this->aPilot = null;
        }

        return $this;
    } // setPilotId()

    /**
     * Set the value of [callsign] column.
     *
     * @param string $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setCallsign($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->callsign !== $v) {
            $this->callsign = $v;
            $this->modifiedColumns[AircraftTableMap::COL_CALLSIGN] = true;
        }

        return $this;
    } // setCallsign()

    /**
     * Set the value of [flown_distance] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setFlownDistance($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->flown_distance !== $v) {
            $this->flown_distance = $v;
            $this->modifiedColumns[AircraftTableMap::COL_FLOWN_DISTANCE] = true;
        }

        return $this;
    } // setFlownDistance()

    /**
     * Set the value of [number_flights] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setNumberFlights($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->number_flights !== $v) {
            $this->number_flights = $v;
            $this->modifiedColumns[AircraftTableMap::COL_NUMBER_FLIGHTS] = true;
        }

        return $this;
    } // setNumberFlights()

    /**
     * Set the value of [flown_time] column.
     *
     * @param int $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setFlownTime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->flown_time !== $v) {
            $this->flown_time = $v;
            $this->modifiedColumns[AircraftTableMap::COL_FLOWN_TIME] = true;
        }

        return $this;
    } // setFlownTime()

    /**
     * Set the value of [status] column.
     *
     * @param  string $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = AircraftTableMap::getValueSet(AircraftTableMap::COL_STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[AircraftTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Set the value of [latitude] column.
     *
     * @param double $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setLatitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->latitude !== $v) {
            $this->latitude = $v;
            $this->modifiedColumns[AircraftTableMap::COL_LATITUDE] = true;
        }

        return $this;
    } // setLatitude()

    /**
     * Set the value of [longitude] column.
     *
     * @param double $v new value
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function setLongitude($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->longitude !== $v) {
            $this->longitude = $v;
            $this->modifiedColumns[AircraftTableMap::COL_LONGITUDE] = true;
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
            if ($this->flown_distance !== 0) {
                return false;
            }

            if ($this->number_flights !== 0) {
                return false;
            }

            if ($this->flown_time !== 0) {
                return false;
            }

            if ($this->status !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AircraftTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AircraftTableMap::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->aircraft_model_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AircraftTableMap::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->airline_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AircraftTableMap::translateFieldName('AirportId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->airport_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AircraftTableMap::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pilot_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AircraftTableMap::translateFieldName('Callsign', TableMap::TYPE_PHPNAME, $indexType)];
            $this->callsign = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AircraftTableMap::translateFieldName('FlownDistance', TableMap::TYPE_PHPNAME, $indexType)];
            $this->flown_distance = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AircraftTableMap::translateFieldName('NumberFlights', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number_flights = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AircraftTableMap::translateFieldName('FlownTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->flown_time = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : AircraftTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : AircraftTableMap::translateFieldName('Latitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->latitude = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : AircraftTableMap::translateFieldName('Longitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->longitude = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = AircraftTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Aircraft'), 0, $e);
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
        if ($this->aAircraftModel !== null && $this->aircraft_model_id !== $this->aAircraftModel->getId()) {
            $this->aAircraftModel = null;
        }
        if ($this->aAirline !== null && $this->airline_id !== $this->aAirline->getId()) {
            $this->aAirline = null;
        }
        if ($this->aAirport !== null && $this->airport_id !== $this->aAirport->getId()) {
            $this->aAirport = null;
        }
        if ($this->aPilot !== null && $this->pilot_id !== $this->aPilot->getId()) {
            $this->aPilot = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(AircraftTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAircraftQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAircraftModel = null;
            $this->aAirport = null;
            $this->aAirline = null;
            $this->aPilot = null;
            $this->collFlights = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Aircraft::setDeleted()
     * @see Aircraft::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAircraftQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTableMap::DATABASE_NAME);
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
                AircraftTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aAircraftModel !== null) {
                if ($this->aAircraftModel->isModified() || $this->aAircraftModel->isNew()) {
                    $affectedRows += $this->aAircraftModel->save($con);
                }
                $this->setAircraftModel($this->aAircraftModel);
            }

            if ($this->aAirport !== null) {
                if ($this->aAirport->isModified() || $this->aAirport->isNew()) {
                    $affectedRows += $this->aAirport->save($con);
                }
                $this->setAirport($this->aAirport);
            }

            if ($this->aAirline !== null) {
                if ($this->aAirline->isModified() || $this->aAirline->isNew()) {
                    $affectedRows += $this->aAirline->save($con);
                }
                $this->setAirline($this->aAirline);
            }

            if ($this->aPilot !== null) {
                if ($this->aPilot->isModified() || $this->aPilot->isNew()) {
                    $affectedRows += $this->aPilot->save($con);
                }
                $this->setPilot($this->aPilot);
            }

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

            if ($this->flightsScheduledForDeletion !== null) {
                if (!$this->flightsScheduledForDeletion->isEmpty()) {
                    \Model\FlightQuery::create()
                        ->filterByPrimaryKeys($this->flightsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->flightsScheduledForDeletion = null;
                }
            }

            if ($this->collFlights !== null) {
                foreach ($this->collFlights as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
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

        $this->modifiedColumns[AircraftTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AircraftTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AircraftTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_AIRCRAFT_MODEL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'aircraft_model_id';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_AIRLINE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'airline_id';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_AIRPORT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'airport_id';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_PILOT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'pilot_id';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_CALLSIGN)) {
            $modifiedColumns[':p' . $index++]  = 'callsign';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_FLOWN_DISTANCE)) {
            $modifiedColumns[':p' . $index++]  = 'flown_distance';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_NUMBER_FLIGHTS)) {
            $modifiedColumns[':p' . $index++]  = 'number_flights';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_FLOWN_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'flown_time';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'status';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_LATITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'latitude';
        }
        if ($this->isColumnModified(AircraftTableMap::COL_LONGITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'longitude';
        }

        $sql = sprintf(
            'INSERT INTO aircrafts (%s) VALUES (%s)',
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
                    case 'aircraft_model_id':
                        $stmt->bindValue($identifier, $this->aircraft_model_id, PDO::PARAM_INT);
                        break;
                    case 'airline_id':
                        $stmt->bindValue($identifier, $this->airline_id, PDO::PARAM_INT);
                        break;
                    case 'airport_id':
                        $stmt->bindValue($identifier, $this->airport_id, PDO::PARAM_INT);
                        break;
                    case 'pilot_id':
                        $stmt->bindValue($identifier, $this->pilot_id, PDO::PARAM_INT);
                        break;
                    case 'callsign':
                        $stmt->bindValue($identifier, $this->callsign, PDO::PARAM_STR);
                        break;
                    case 'flown_distance':
                        $stmt->bindValue($identifier, $this->flown_distance, PDO::PARAM_INT);
                        break;
                    case 'number_flights':
                        $stmt->bindValue($identifier, $this->number_flights, PDO::PARAM_INT);
                        break;
                    case 'flown_time':
                        $stmt->bindValue($identifier, $this->flown_time, PDO::PARAM_INT);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
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

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

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
        $pos = AircraftTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAircraftModelId();
                break;
            case 2:
                return $this->getAirlineId();
                break;
            case 3:
                return $this->getAirportId();
                break;
            case 4:
                return $this->getPilotId();
                break;
            case 5:
                return $this->getCallsign();
                break;
            case 6:
                return $this->getFlownDistance();
                break;
            case 7:
                return $this->getNumberFlights();
                break;
            case 8:
                return $this->getFlownTime();
                break;
            case 9:
                return $this->getStatus();
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

        if (isset($alreadyDumpedObjects['Aircraft'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Aircraft'][$this->hashCode()] = true;
        $keys = AircraftTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAircraftModelId(),
            $keys[2] => $this->getAirlineId(),
            $keys[3] => $this->getAirportId(),
            $keys[4] => $this->getPilotId(),
            $keys[5] => $this->getCallsign(),
            $keys[6] => $this->getFlownDistance(),
            $keys[7] => $this->getNumberFlights(),
            $keys[8] => $this->getFlownTime(),
            $keys[9] => $this->getStatus(),
            $keys[10] => $this->getLatitude(),
            $keys[11] => $this->getLongitude(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAircraftModel) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'aircraftModel';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'aircraft_models';
                        break;
                    default:
                        $key = 'AircraftModel';
                }

                $result[$key] = $this->aAircraftModel->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAirport) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'airport';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'airports';
                        break;
                    default:
                        $key = 'Airport';
                }

                $result[$key] = $this->aAirport->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAirline) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'airline';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'airlines';
                        break;
                    default:
                        $key = 'Airline';
                }

                $result[$key] = $this->aAirline->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPilot) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'pilot';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pilots';
                        break;
                    default:
                        $key = 'Pilot';
                }

                $result[$key] = $this->aPilot->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collFlights) {

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

                $result[$key] = $this->collFlights->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Model\Aircraft
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AircraftTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Aircraft
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAircraftModelId($value);
                break;
            case 2:
                $this->setAirlineId($value);
                break;
            case 3:
                $this->setAirportId($value);
                break;
            case 4:
                $this->setPilotId($value);
                break;
            case 5:
                $this->setCallsign($value);
                break;
            case 6:
                $this->setFlownDistance($value);
                break;
            case 7:
                $this->setNumberFlights($value);
                break;
            case 8:
                $this->setFlownTime($value);
                break;
            case 9:
                $valueSet = AircraftTableMap::getValueSet(AircraftTableMap::COL_STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
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
        $keys = AircraftTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAircraftModelId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAirlineId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAirportId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPilotId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCallsign($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setFlownDistance($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setNumberFlights($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setFlownTime($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setStatus($arr[$keys[9]]);
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
     * @return $this|\Model\Aircraft The current object, for fluid interface
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
        $criteria = new Criteria(AircraftTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AircraftTableMap::COL_ID)) {
            $criteria->add(AircraftTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_AIRCRAFT_MODEL_ID)) {
            $criteria->add(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $this->aircraft_model_id);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_AIRLINE_ID)) {
            $criteria->add(AircraftTableMap::COL_AIRLINE_ID, $this->airline_id);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_AIRPORT_ID)) {
            $criteria->add(AircraftTableMap::COL_AIRPORT_ID, $this->airport_id);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_PILOT_ID)) {
            $criteria->add(AircraftTableMap::COL_PILOT_ID, $this->pilot_id);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_CALLSIGN)) {
            $criteria->add(AircraftTableMap::COL_CALLSIGN, $this->callsign);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_FLOWN_DISTANCE)) {
            $criteria->add(AircraftTableMap::COL_FLOWN_DISTANCE, $this->flown_distance);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_NUMBER_FLIGHTS)) {
            $criteria->add(AircraftTableMap::COL_NUMBER_FLIGHTS, $this->number_flights);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_FLOWN_TIME)) {
            $criteria->add(AircraftTableMap::COL_FLOWN_TIME, $this->flown_time);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_STATUS)) {
            $criteria->add(AircraftTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_LATITUDE)) {
            $criteria->add(AircraftTableMap::COL_LATITUDE, $this->latitude);
        }
        if ($this->isColumnModified(AircraftTableMap::COL_LONGITUDE)) {
            $criteria->add(AircraftTableMap::COL_LONGITUDE, $this->longitude);
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
        $criteria = ChildAircraftQuery::create();
        $criteria->add(AircraftTableMap::COL_ID, $this->id);
        $criteria->add(AircraftTableMap::COL_AIRCRAFT_MODEL_ID, $this->aircraft_model_id);
        $criteria->add(AircraftTableMap::COL_AIRLINE_ID, $this->airline_id);
        $criteria->add(AircraftTableMap::COL_PILOT_ID, $this->pilot_id);

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
        $validPk = null !== $this->getId() &&
            null !== $this->getAircraftModelId() &&
            null !== $this->getAirlineId() &&
            null !== $this->getPilotId();

        $validPrimaryKeyFKs = 3;
        $primaryKeyFKs = [];

        //relation aircrafts_fk_533ab3 to table aircraft_models
        if ($this->aAircraftModel && $hash = spl_object_hash($this->aAircraftModel)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation aircrafts_fk_3c541c to table airlines
        if ($this->aAirline && $hash = spl_object_hash($this->aAirline)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation aircrafts_fk_17d49f to table pilots
        if ($this->aPilot && $hash = spl_object_hash($this->aPilot)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getId();
        $pks[1] = $this->getAircraftModelId();
        $pks[2] = $this->getAirlineId();
        $pks[3] = $this->getPilotId();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param      array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setId($keys[0]);
        $this->setAircraftModelId($keys[1]);
        $this->setAirlineId($keys[2]);
        $this->setPilotId($keys[3]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getId()) && (null === $this->getAircraftModelId()) && (null === $this->getAirlineId()) && (null === $this->getPilotId());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Aircraft (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAircraftModelId($this->getAircraftModelId());
        $copyObj->setAirlineId($this->getAirlineId());
        $copyObj->setAirportId($this->getAirportId());
        $copyObj->setPilotId($this->getPilotId());
        $copyObj->setCallsign($this->getCallsign());
        $copyObj->setFlownDistance($this->getFlownDistance());
        $copyObj->setNumberFlights($this->getNumberFlights());
        $copyObj->setFlownTime($this->getFlownTime());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setLatitude($this->getLatitude());
        $copyObj->setLongitude($this->getLongitude());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getFlights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFlight($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Model\Aircraft Clone of current object.
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
     * Declares an association between this object and a ChildAircraftModel object.
     *
     * @param  ChildAircraftModel $v
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAircraftModel(ChildAircraftModel $v = null)
    {
        if ($v === null) {
            $this->setAircraftModelId(NULL);
        } else {
            $this->setAircraftModelId($v->getId());
        }

        $this->aAircraftModel = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAircraftModel object, it will not be re-added.
        if ($v !== null) {
            $v->addAircraft($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAircraftModel object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAircraftModel The associated ChildAircraftModel object.
     * @throws PropelException
     */
    public function getAircraftModel(ConnectionInterface $con = null)
    {
        if ($this->aAircraftModel === null && ($this->aircraft_model_id !== null)) {
            $this->aAircraftModel = ChildAircraftModelQuery::create()->findPk($this->aircraft_model_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAircraftModel->addAircrafts($this);
             */
        }

        return $this->aAircraftModel;
    }

    /**
     * Declares an association between this object and a ChildAirport object.
     *
     * @param  ChildAirport $v
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAirport(ChildAirport $v = null)
    {
        if ($v === null) {
            $this->setAirportId(NULL);
        } else {
            $this->setAirportId($v->getId());
        }

        $this->aAirport = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAirport object, it will not be re-added.
        if ($v !== null) {
            $v->addAircraft($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAirport object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAirport The associated ChildAirport object.
     * @throws PropelException
     */
    public function getAirport(ConnectionInterface $con = null)
    {
        if ($this->aAirport === null && ($this->airport_id !== null)) {
            $this->aAirport = ChildAirportQuery::create()->findPk($this->airport_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAirport->addAircrafts($this);
             */
        }

        return $this->aAirport;
    }

    /**
     * Declares an association between this object and a ChildAirline object.
     *
     * @param  ChildAirline $v
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAirline(ChildAirline $v = null)
    {
        if ($v === null) {
            $this->setAirlineId(NULL);
        } else {
            $this->setAirlineId($v->getId());
        }

        $this->aAirline = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAirline object, it will not be re-added.
        if ($v !== null) {
            $v->addAircraft($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAirline object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAirline The associated ChildAirline object.
     * @throws PropelException
     */
    public function getAirline(ConnectionInterface $con = null)
    {
        if ($this->aAirline === null && ($this->airline_id !== null)) {
            $this->aAirline = ChildAirlineQuery::create()->findPk($this->airline_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAirline->addAircrafts($this);
             */
        }

        return $this->aAirline;
    }

    /**
     * Declares an association between this object and a ChildPilot object.
     *
     * @param  ChildPilot $v
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPilot(ChildPilot $v = null)
    {
        if ($v === null) {
            $this->setPilotId(NULL);
        } else {
            $this->setPilotId($v->getId());
        }

        $this->aPilot = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPilot object, it will not be re-added.
        if ($v !== null) {
            $v->addAircraft($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPilot object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPilot The associated ChildPilot object.
     * @throws PropelException
     */
    public function getPilot(ConnectionInterface $con = null)
    {
        if ($this->aPilot === null && ($this->pilot_id !== null)) {
            $this->aPilot = ChildPilotQuery::create()->findPk($this->pilot_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPilot->addAircrafts($this);
             */
        }

        return $this->aPilot;
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
        if ('Flight' == $relationName) {
            return $this->initFlights();
        }
    }

    /**
     * Clears out the collFlights collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFlights()
     */
    public function clearFlights()
    {
        $this->collFlights = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFlights collection loaded partially.
     */
    public function resetPartialFlights($v = true)
    {
        $this->collFlightsPartial = $v;
    }

    /**
     * Initializes the collFlights collection.
     *
     * By default this just sets the collFlights collection to an empty array (like clearcollFlights());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFlights($overrideExisting = true)
    {
        if (null !== $this->collFlights && !$overrideExisting) {
            return;
        }
        $this->collFlights = new ObjectCollection();
        $this->collFlights->setModel('\Model\Flight');
    }

    /**
     * Gets an array of ChildFlight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAircraft is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     * @throws PropelException
     */
    public function getFlights(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFlightsPartial && !$this->isNew();
        if (null === $this->collFlights || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFlights) {
                // return empty collection
                $this->initFlights();
            } else {
                $collFlights = ChildFlightQuery::create(null, $criteria)
                    ->filterByAircraft($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFlightsPartial && count($collFlights)) {
                        $this->initFlights(false);

                        foreach ($collFlights as $obj) {
                            if (false == $this->collFlights->contains($obj)) {
                                $this->collFlights->append($obj);
                            }
                        }

                        $this->collFlightsPartial = true;
                    }

                    return $collFlights;
                }

                if ($partial && $this->collFlights) {
                    foreach ($this->collFlights as $obj) {
                        if ($obj->isNew()) {
                            $collFlights[] = $obj;
                        }
                    }
                }

                $this->collFlights = $collFlights;
                $this->collFlightsPartial = false;
            }
        }

        return $this->collFlights;
    }

    /**
     * Sets a collection of ChildFlight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $flights A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAircraft The current object (for fluent API support)
     */
    public function setFlights(Collection $flights, ConnectionInterface $con = null)
    {
        /** @var ChildFlight[] $flightsToDelete */
        $flightsToDelete = $this->getFlights(new Criteria(), $con)->diff($flights);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->flightsScheduledForDeletion = clone $flightsToDelete;

        foreach ($flightsToDelete as $flightRemoved) {
            $flightRemoved->setAircraft(null);
        }

        $this->collFlights = null;
        foreach ($flights as $flight) {
            $this->addFlight($flight);
        }

        $this->collFlights = $flights;
        $this->collFlightsPartial = false;

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
    public function countFlights(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFlightsPartial && !$this->isNew();
        if (null === $this->collFlights || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFlights) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFlights());
            }

            $query = ChildFlightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAircraft($this)
                ->count($con);
        }

        return count($this->collFlights);
    }

    /**
     * Method called to associate a ChildFlight object to this object
     * through the ChildFlight foreign key attribute.
     *
     * @param  ChildFlight $l ChildFlight
     * @return $this|\Model\Aircraft The current object (for fluent API support)
     */
    public function addFlight(ChildFlight $l)
    {
        if ($this->collFlights === null) {
            $this->initFlights();
            $this->collFlightsPartial = true;
        }

        if (!$this->collFlights->contains($l)) {
            $this->doAddFlight($l);
        }

        return $this;
    }

    /**
     * @param ChildFlight $flight The ChildFlight object to add.
     */
    protected function doAddFlight(ChildFlight $flight)
    {
        $this->collFlights[]= $flight;
        $flight->setAircraft($this);
    }

    /**
     * @param  ChildFlight $flight The ChildFlight object to remove.
     * @return $this|ChildAircraft The current object (for fluent API support)
     */
    public function removeFlight(ChildFlight $flight)
    {
        if ($this->getFlights()->contains($flight)) {
            $pos = $this->collFlights->search($flight);
            $this->collFlights->remove($pos);
            if (null === $this->flightsScheduledForDeletion) {
                $this->flightsScheduledForDeletion = clone $this->collFlights;
                $this->flightsScheduledForDeletion->clear();
            }
            $this->flightsScheduledForDeletion[]= clone $flight;
            $flight->setAircraft(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Aircraft is new, it will return
     * an empty collection; or if this Aircraft has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Aircraft.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsJoinAircraftModel(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('AircraftModel', $joinBehavior);

        return $this->getFlights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Aircraft is new, it will return
     * an empty collection; or if this Aircraft has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Aircraft.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsJoinAirline(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Airline', $joinBehavior);

        return $this->getFlights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Aircraft is new, it will return
     * an empty collection; or if this Aircraft has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Aircraft.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsJoinDestination(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Destination', $joinBehavior);

        return $this->getFlights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Aircraft is new, it will return
     * an empty collection; or if this Aircraft has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Aircraft.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsJoinDeparture(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Departure', $joinBehavior);

        return $this->getFlights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Aircraft is new, it will return
     * an empty collection; or if this Aircraft has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Aircraft.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsJoinPilot(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Pilot', $joinBehavior);

        return $this->getFlights($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAircraftModel) {
            $this->aAircraftModel->removeAircraft($this);
        }
        if (null !== $this->aAirport) {
            $this->aAirport->removeAircraft($this);
        }
        if (null !== $this->aAirline) {
            $this->aAirline->removeAircraft($this);
        }
        if (null !== $this->aPilot) {
            $this->aPilot->removeAircraft($this);
        }
        $this->id = null;
        $this->aircraft_model_id = null;
        $this->airline_id = null;
        $this->airport_id = null;
        $this->pilot_id = null;
        $this->callsign = null;
        $this->flown_distance = null;
        $this->number_flights = null;
        $this->flown_time = null;
        $this->status = null;
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
            if ($this->collFlights) {
                foreach ($this->collFlights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collFlights = null;
        $this->aAircraftModel = null;
        $this->aAirport = null;
        $this->aAirline = null;
        $this->aPilot = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AircraftTableMap::DEFAULT_STRING_FORMAT);
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
     * Calculates the distance between a given aircraft and this one.
     *
     * @param \Model\Aircraft $aircraft    A \Model\Aircraft object.
     * @param double $unit     The unit measure.
     *
     * @return double   The distance between the two objects.
     */
    public function getDistanceTo(\Model\Aircraft $aircraft, $unit = AircraftTableMap::KILOMETERS_UNIT)
    {
        $dist = rad2deg(acos(sin(deg2rad($this->getLatitude())) * sin(deg2rad($aircraft->getLatitude())) +  cos(deg2rad($this->getLatitude())) * cos(deg2rad($aircraft->getLatitude())) * cos(deg2rad($this->getLongitude() - $aircraft->getLongitude())))) * 60 * AircraftTableMap::MILES_UNIT;

        if (AircraftTableMap::MILES_UNIT === $unit) {
            return $dist;
        } elseif (AircraftTableMap::NAUTICAL_MILES_UNIT === $unit) {
            return $dist * AircraftTableMap::NAUTICAL_MILES_UNIT;
        }

        return $dist * AircraftTableMap::KILOMETERS_UNIT;
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

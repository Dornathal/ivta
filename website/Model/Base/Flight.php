<?php

namespace Model\Base;

use \DateTime;
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
use Model\Freight as ChildFreight;
use Model\FreightQuery as ChildFreightQuery;
use Model\Pilot as ChildPilot;
use Model\PilotQuery as ChildPilotQuery;
use Model\Map\FlightTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'flights' table.
 *
 *
 *
* @package    propel.generator.Model.Base
*/
abstract class Flight implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\FlightTableMap';


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
     * The value for the aircraft_id field.
     * @var        int
     */
    protected $aircraft_id;

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
     * The value for the destination_id field.
     * @var        int
     */
    protected $destination_id;

    /**
     * The value for the departure_id field.
     * @var        int
     */
    protected $departure_id;

    /**
     * The value for the pilot_id field.
     * @var        int
     */
    protected $pilot_id;

    /**
     * The value for the flight_number field.
     * @var        string
     */
    protected $flight_number;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the packages field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $packages;

    /**
     * The value for the post field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $post;

    /**
     * The value for the passenger_low field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $passenger_low;

    /**
     * The value for the passenger_mid field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $passenger_mid;

    /**
     * The value for the passenger_high field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $passenger_high;

    /**
     * The value for the flight_started_at field.
     * @var        \DateTime
     */
    protected $flight_started_at;

    /**
     * The value for the flight_finished_at field.
     * @var        \DateTime
     */
    protected $flight_finished_at;

    /**
     * The value for the next_step_possible_at field.
     * @var        \DateTime
     */
    protected $next_step_possible_at;

    /**
     * The value for the created_at field.
     * @var        \DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        \DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildAircraft
     */
    protected $aAircraft;

    /**
     * @var        ChildAircraftModel
     */
    protected $aAircraftModel;

    /**
     * @var        ChildAirline
     */
    protected $aAirline;

    /**
     * @var        ChildAirport
     */
    protected $aDestination;

    /**
     * @var        ChildAirport
     */
    protected $aDeparture;

    /**
     * @var        ChildPilot
     */
    protected $aPilot;

    /**
     * @var        ObjectCollection|ChildFreight[] Collection to store aggregation of ChildFreight objects.
     */
    protected $collFreights;
    protected $collFreightsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFreight[]
     */
    protected $freightsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
        $this->packages = 0;
        $this->post = 0;
        $this->passenger_low = 0;
        $this->passenger_mid = 0;
        $this->passenger_high = 0;
    }

    /**
     * Initializes internal state of Model\Base\Flight object.
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
     * Compares this with another <code>Flight</code> instance.  If
     * <code>obj</code> is an instance of <code>Flight</code>, delegates to
     * <code>equals(Flight)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Flight The current object, for fluid interface
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
     * Get the [aircraft_id] column value.
     *
     * @return int
     */
    public function getAircraftId()
    {
        return $this->aircraft_id;
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
     * Get the [destination_id] column value.
     *
     * @return int
     */
    public function getDestinationId()
    {
        return $this->destination_id;
    }

    /**
     * Get the [departure_id] column value.
     *
     * @return int
     */
    public function getDepartureId()
    {
        return $this->departure_id;
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
     * Get the [flight_number] column value.
     *
     * @return string
     */
    public function getFlightNumber()
    {
        return $this->flight_number;
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
        $valueSet = FlightTableMap::getValueSet(FlightTableMap::COL_STATUS);
        if (!isset($valueSet[$this->status])) {
            throw new PropelException('Unknown stored enum key: ' . $this->status);
        }

        return $valueSet[$this->status];
    }

    /**
     * Get the [packages] column value.
     *
     * @return int
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * Get the [post] column value.
     *
     * @return int
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Get the [passenger_low] column value.
     *
     * @return int
     */
    public function getPassengerLow()
    {
        return $this->passenger_low;
    }

    /**
     * Get the [passenger_mid] column value.
     *
     * @return int
     */
    public function getPassengerMid()
    {
        return $this->passenger_mid;
    }

    /**
     * Get the [passenger_high] column value.
     *
     * @return int
     */
    public function getPassengerHigh()
    {
        return $this->passenger_high;
    }

    /**
     * Get the [optionally formatted] temporal [flight_started_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getFlightStartedAt($format = NULL)
    {
        if ($format === null) {
            return $this->flight_started_at;
        } else {
            return $this->flight_started_at instanceof \DateTime ? $this->flight_started_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [flight_finished_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getFlightFinishedAt($format = NULL)
    {
        if ($format === null) {
            return $this->flight_finished_at;
        } else {
            return $this->flight_finished_at instanceof \DateTime ? $this->flight_finished_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [next_step_possible_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getNextStepPossibleAt($format = NULL)
    {
        if ($format === null) {
            return $this->next_step_possible_at;
        } else {
            return $this->next_step_possible_at instanceof \DateTime ? $this->next_step_possible_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[FlightTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [aircraft_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setAircraftId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->aircraft_id !== $v) {
            $this->aircraft_id = $v;
            $this->modifiedColumns[FlightTableMap::COL_AIRCRAFT_ID] = true;
        }

        if ($this->aAircraft !== null && $this->aAircraft->getId() !== $v) {
            $this->aAircraft = null;
        }

        return $this;
    } // setAircraftId()

    /**
     * Set the value of [aircraft_model_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setAircraftModelId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->aircraft_model_id !== $v) {
            $this->aircraft_model_id = $v;
            $this->modifiedColumns[FlightTableMap::COL_AIRCRAFT_MODEL_ID] = true;
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
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setAirlineId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->airline_id !== $v) {
            $this->airline_id = $v;
            $this->modifiedColumns[FlightTableMap::COL_AIRLINE_ID] = true;
        }

        if ($this->aAirline !== null && $this->aAirline->getId() !== $v) {
            $this->aAirline = null;
        }

        return $this;
    } // setAirlineId()

    /**
     * Set the value of [destination_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setDestinationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->destination_id !== $v) {
            $this->destination_id = $v;
            $this->modifiedColumns[FlightTableMap::COL_DESTINATION_ID] = true;
        }

        if ($this->aDestination !== null && $this->aDestination->getId() !== $v) {
            $this->aDestination = null;
        }

        return $this;
    } // setDestinationId()

    /**
     * Set the value of [departure_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setDepartureId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->departure_id !== $v) {
            $this->departure_id = $v;
            $this->modifiedColumns[FlightTableMap::COL_DEPARTURE_ID] = true;
        }

        if ($this->aDeparture !== null && $this->aDeparture->getId() !== $v) {
            $this->aDeparture = null;
        }

        return $this;
    } // setDepartureId()

    /**
     * Set the value of [pilot_id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setPilotId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->pilot_id !== $v) {
            $this->pilot_id = $v;
            $this->modifiedColumns[FlightTableMap::COL_PILOT_ID] = true;
        }

        if ($this->aPilot !== null && $this->aPilot->getId() !== $v) {
            $this->aPilot = null;
        }

        return $this;
    } // setPilotId()

    /**
     * Set the value of [flight_number] column.
     *
     * @param string $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setFlightNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->flight_number !== $v) {
            $this->flight_number = $v;
            $this->modifiedColumns[FlightTableMap::COL_FLIGHT_NUMBER] = true;
        }

        return $this;
    } // setFlightNumber()

    /**
     * Set the value of [status] column.
     *
     * @param  string $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $valueSet = FlightTableMap::getValueSet(FlightTableMap::COL_STATUS);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[FlightTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Set the value of [packages] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setPackages($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->packages !== $v) {
            $this->packages = $v;
            $this->modifiedColumns[FlightTableMap::COL_PACKAGES] = true;
        }

        return $this;
    } // setPackages()

    /**
     * Set the value of [post] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setPost($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->post !== $v) {
            $this->post = $v;
            $this->modifiedColumns[FlightTableMap::COL_POST] = true;
        }

        return $this;
    } // setPost()

    /**
     * Set the value of [passenger_low] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setPassengerLow($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->passenger_low !== $v) {
            $this->passenger_low = $v;
            $this->modifiedColumns[FlightTableMap::COL_PASSENGER_LOW] = true;
        }

        return $this;
    } // setPassengerLow()

    /**
     * Set the value of [passenger_mid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setPassengerMid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->passenger_mid !== $v) {
            $this->passenger_mid = $v;
            $this->modifiedColumns[FlightTableMap::COL_PASSENGER_MID] = true;
        }

        return $this;
    } // setPassengerMid()

    /**
     * Set the value of [passenger_high] column.
     *
     * @param int $v new value
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setPassengerHigh($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->passenger_high !== $v) {
            $this->passenger_high = $v;
            $this->modifiedColumns[FlightTableMap::COL_PASSENGER_HIGH] = true;
        }

        return $this;
    } // setPassengerHigh()

    /**
     * Sets the value of [flight_started_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setFlightStartedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->flight_started_at !== null || $dt !== null) {
            if ($this->flight_started_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->flight_started_at->format("Y-m-d H:i:s")) {
                $this->flight_started_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FlightTableMap::COL_FLIGHT_STARTED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setFlightStartedAt()

    /**
     * Sets the value of [flight_finished_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setFlightFinishedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->flight_finished_at !== null || $dt !== null) {
            if ($this->flight_finished_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->flight_finished_at->format("Y-m-d H:i:s")) {
                $this->flight_finished_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FlightTableMap::COL_FLIGHT_FINISHED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setFlightFinishedAt()

    /**
     * Sets the value of [next_step_possible_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setNextStepPossibleAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->next_step_possible_at !== null || $dt !== null) {
            if ($this->next_step_possible_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->next_step_possible_at->format("Y-m-d H:i:s")) {
                $this->next_step_possible_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setNextStepPossibleAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_at->format("Y-m-d H:i:s")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FlightTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_at->format("Y-m-d H:i:s")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FlightTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

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
            if ($this->status !== 0) {
                return false;
            }

            if ($this->packages !== 0) {
                return false;
            }

            if ($this->post !== 0) {
                return false;
            }

            if ($this->passenger_low !== 0) {
                return false;
            }

            if ($this->passenger_mid !== 0) {
                return false;
            }

            if ($this->passenger_high !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : FlightTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : FlightTableMap::translateFieldName('AircraftId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->aircraft_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : FlightTableMap::translateFieldName('AircraftModelId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->aircraft_model_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : FlightTableMap::translateFieldName('AirlineId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->airline_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : FlightTableMap::translateFieldName('DestinationId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->destination_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : FlightTableMap::translateFieldName('DepartureId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->departure_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : FlightTableMap::translateFieldName('PilotId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pilot_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : FlightTableMap::translateFieldName('FlightNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->flight_number = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : FlightTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : FlightTableMap::translateFieldName('Packages', TableMap::TYPE_PHPNAME, $indexType)];
            $this->packages = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : FlightTableMap::translateFieldName('Post', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : FlightTableMap::translateFieldName('PassengerLow', TableMap::TYPE_PHPNAME, $indexType)];
            $this->passenger_low = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : FlightTableMap::translateFieldName('PassengerMid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->passenger_mid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : FlightTableMap::translateFieldName('PassengerHigh', TableMap::TYPE_PHPNAME, $indexType)];
            $this->passenger_high = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : FlightTableMap::translateFieldName('FlightStartedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->flight_started_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : FlightTableMap::translateFieldName('FlightFinishedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->flight_finished_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : FlightTableMap::translateFieldName('NextStepPossibleAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->next_step_possible_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : FlightTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : FlightTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 19; // 19 = FlightTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Flight'), 0, $e);
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
        if ($this->aAircraft !== null && $this->aircraft_id !== $this->aAircraft->getId()) {
            $this->aAircraft = null;
        }
        if ($this->aAircraftModel !== null && $this->aircraft_model_id !== $this->aAircraftModel->getId()) {
            $this->aAircraftModel = null;
        }
        if ($this->aAirline !== null && $this->airline_id !== $this->aAirline->getId()) {
            $this->aAirline = null;
        }
        if ($this->aDestination !== null && $this->destination_id !== $this->aDestination->getId()) {
            $this->aDestination = null;
        }
        if ($this->aDeparture !== null && $this->departure_id !== $this->aDeparture->getId()) {
            $this->aDeparture = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(FlightTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildFlightQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAircraft = null;
            $this->aAircraftModel = null;
            $this->aAirline = null;
            $this->aDestination = null;
            $this->aDeparture = null;
            $this->aPilot = null;
            $this->collFreights = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Flight::setDeleted()
     * @see Flight::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FlightTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildFlightQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(FlightTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(FlightTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(FlightTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(FlightTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                FlightTableMap::addInstanceToPool($this);
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

            if ($this->aAircraft !== null) {
                if ($this->aAircraft->isModified() || $this->aAircraft->isNew()) {
                    $affectedRows += $this->aAircraft->save($con);
                }
                $this->setAircraft($this->aAircraft);
            }

            if ($this->aAircraftModel !== null) {
                if ($this->aAircraftModel->isModified() || $this->aAircraftModel->isNew()) {
                    $affectedRows += $this->aAircraftModel->save($con);
                }
                $this->setAircraftModel($this->aAircraftModel);
            }

            if ($this->aAirline !== null) {
                if ($this->aAirline->isModified() || $this->aAirline->isNew()) {
                    $affectedRows += $this->aAirline->save($con);
                }
                $this->setAirline($this->aAirline);
            }

            if ($this->aDestination !== null) {
                if ($this->aDestination->isModified() || $this->aDestination->isNew()) {
                    $affectedRows += $this->aDestination->save($con);
                }
                $this->setDestination($this->aDestination);
            }

            if ($this->aDeparture !== null) {
                if ($this->aDeparture->isModified() || $this->aDeparture->isNew()) {
                    $affectedRows += $this->aDeparture->save($con);
                }
                $this->setDeparture($this->aDeparture);
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

            if ($this->freightsScheduledForDeletion !== null) {
                if (!$this->freightsScheduledForDeletion->isEmpty()) {
                    foreach ($this->freightsScheduledForDeletion as $freight) {
                        // need to save related object because we set the relation to null
                        $freight->save($con);
                    }
                    $this->freightsScheduledForDeletion = null;
                }
            }

            if ($this->collFreights !== null) {
                foreach ($this->collFreights as $referrerFK) {
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

        $this->modifiedColumns[FlightTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FlightTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FlightTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_AIRCRAFT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'aircraft_id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_AIRCRAFT_MODEL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'aircraft_model_id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_AIRLINE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'airline_id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_DESTINATION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'destination_id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_DEPARTURE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'departure_id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_PILOT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'pilot_id';
        }
        if ($this->isColumnModified(FlightTableMap::COL_FLIGHT_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'flight_number';
        }
        if ($this->isColumnModified(FlightTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'status';
        }
        if ($this->isColumnModified(FlightTableMap::COL_PACKAGES)) {
            $modifiedColumns[':p' . $index++]  = 'packages';
        }
        if ($this->isColumnModified(FlightTableMap::COL_POST)) {
            $modifiedColumns[':p' . $index++]  = 'post';
        }
        if ($this->isColumnModified(FlightTableMap::COL_PASSENGER_LOW)) {
            $modifiedColumns[':p' . $index++]  = 'passenger_low';
        }
        if ($this->isColumnModified(FlightTableMap::COL_PASSENGER_MID)) {
            $modifiedColumns[':p' . $index++]  = 'passenger_mid';
        }
        if ($this->isColumnModified(FlightTableMap::COL_PASSENGER_HIGH)) {
            $modifiedColumns[':p' . $index++]  = 'passenger_high';
        }
        if ($this->isColumnModified(FlightTableMap::COL_FLIGHT_STARTED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'flight_started_at';
        }
        if ($this->isColumnModified(FlightTableMap::COL_FLIGHT_FINISHED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'flight_finished_at';
        }
        if ($this->isColumnModified(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT)) {
            $modifiedColumns[':p' . $index++]  = 'next_step_possible_at';
        }
        if ($this->isColumnModified(FlightTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(FlightTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO flights (%s) VALUES (%s)',
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
                    case 'aircraft_id':
                        $stmt->bindValue($identifier, $this->aircraft_id, PDO::PARAM_INT);
                        break;
                    case 'aircraft_model_id':
                        $stmt->bindValue($identifier, $this->aircraft_model_id, PDO::PARAM_INT);
                        break;
                    case 'airline_id':
                        $stmt->bindValue($identifier, $this->airline_id, PDO::PARAM_INT);
                        break;
                    case 'destination_id':
                        $stmt->bindValue($identifier, $this->destination_id, PDO::PARAM_INT);
                        break;
                    case 'departure_id':
                        $stmt->bindValue($identifier, $this->departure_id, PDO::PARAM_INT);
                        break;
                    case 'pilot_id':
                        $stmt->bindValue($identifier, $this->pilot_id, PDO::PARAM_INT);
                        break;
                    case 'flight_number':
                        $stmt->bindValue($identifier, $this->flight_number, PDO::PARAM_STR);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'packages':
                        $stmt->bindValue($identifier, $this->packages, PDO::PARAM_INT);
                        break;
                    case 'post':
                        $stmt->bindValue($identifier, $this->post, PDO::PARAM_INT);
                        break;
                    case 'passenger_low':
                        $stmt->bindValue($identifier, $this->passenger_low, PDO::PARAM_INT);
                        break;
                    case 'passenger_mid':
                        $stmt->bindValue($identifier, $this->passenger_mid, PDO::PARAM_INT);
                        break;
                    case 'passenger_high':
                        $stmt->bindValue($identifier, $this->passenger_high, PDO::PARAM_INT);
                        break;
                    case 'flight_started_at':
                        $stmt->bindValue($identifier, $this->flight_started_at ? $this->flight_started_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'flight_finished_at':
                        $stmt->bindValue($identifier, $this->flight_finished_at ? $this->flight_finished_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'next_step_possible_at':
                        $stmt->bindValue($identifier, $this->next_step_possible_at ? $this->next_step_possible_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $pos = FlightTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAircraftId();
                break;
            case 2:
                return $this->getAircraftModelId();
                break;
            case 3:
                return $this->getAirlineId();
                break;
            case 4:
                return $this->getDestinationId();
                break;
            case 5:
                return $this->getDepartureId();
                break;
            case 6:
                return $this->getPilotId();
                break;
            case 7:
                return $this->getFlightNumber();
                break;
            case 8:
                return $this->getStatus();
                break;
            case 9:
                return $this->getPackages();
                break;
            case 10:
                return $this->getPost();
                break;
            case 11:
                return $this->getPassengerLow();
                break;
            case 12:
                return $this->getPassengerMid();
                break;
            case 13:
                return $this->getPassengerHigh();
                break;
            case 14:
                return $this->getFlightStartedAt();
                break;
            case 15:
                return $this->getFlightFinishedAt();
                break;
            case 16:
                return $this->getNextStepPossibleAt();
                break;
            case 17:
                return $this->getCreatedAt();
                break;
            case 18:
                return $this->getUpdatedAt();
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

        if (isset($alreadyDumpedObjects['Flight'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Flight'][$this->hashCode()] = true;
        $keys = FlightTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAircraftId(),
            $keys[2] => $this->getAircraftModelId(),
            $keys[3] => $this->getAirlineId(),
            $keys[4] => $this->getDestinationId(),
            $keys[5] => $this->getDepartureId(),
            $keys[6] => $this->getPilotId(),
            $keys[7] => $this->getFlightNumber(),
            $keys[8] => $this->getStatus(),
            $keys[9] => $this->getPackages(),
            $keys[10] => $this->getPost(),
            $keys[11] => $this->getPassengerLow(),
            $keys[12] => $this->getPassengerMid(),
            $keys[13] => $this->getPassengerHigh(),
            $keys[14] => $this->getFlightStartedAt(),
            $keys[15] => $this->getFlightFinishedAt(),
            $keys[16] => $this->getNextStepPossibleAt(),
            $keys[17] => $this->getCreatedAt(),
            $keys[18] => $this->getUpdatedAt(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[14]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[14]];
            $result[$keys[14]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[15]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[15]];
            $result[$keys[15]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[16]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[16]];
            $result[$keys[16]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[17]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[17]];
            $result[$keys[17]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[18]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[18]];
            $result[$keys[18]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAircraft) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'aircraft';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'aircrafts';
                        break;
                    default:
                        $key = 'Aircraft';
                }

                $result[$key] = $this->aAircraft->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->aDestination) {

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

                $result[$key] = $this->aDestination->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDeparture) {

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

                $result[$key] = $this->aDeparture->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collFreights) {

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

                $result[$key] = $this->collFreights->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Model\Flight
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = FlightTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Flight
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAircraftId($value);
                break;
            case 2:
                $this->setAircraftModelId($value);
                break;
            case 3:
                $this->setAirlineId($value);
                break;
            case 4:
                $this->setDestinationId($value);
                break;
            case 5:
                $this->setDepartureId($value);
                break;
            case 6:
                $this->setPilotId($value);
                break;
            case 7:
                $this->setFlightNumber($value);
                break;
            case 8:
                $valueSet = FlightTableMap::getValueSet(FlightTableMap::COL_STATUS);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setStatus($value);
                break;
            case 9:
                $this->setPackages($value);
                break;
            case 10:
                $this->setPost($value);
                break;
            case 11:
                $this->setPassengerLow($value);
                break;
            case 12:
                $this->setPassengerMid($value);
                break;
            case 13:
                $this->setPassengerHigh($value);
                break;
            case 14:
                $this->setFlightStartedAt($value);
                break;
            case 15:
                $this->setFlightFinishedAt($value);
                break;
            case 16:
                $this->setNextStepPossibleAt($value);
                break;
            case 17:
                $this->setCreatedAt($value);
                break;
            case 18:
                $this->setUpdatedAt($value);
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
        $keys = FlightTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAircraftId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAircraftModelId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAirlineId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDestinationId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDepartureId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPilotId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setFlightNumber($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setStatus($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPackages($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setPost($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setPassengerLow($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setPassengerMid($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setPassengerHigh($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setFlightStartedAt($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setFlightFinishedAt($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setNextStepPossibleAt($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setCreatedAt($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setUpdatedAt($arr[$keys[18]]);
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
     * @return $this|\Model\Flight The current object, for fluid interface
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
        $criteria = new Criteria(FlightTableMap::DATABASE_NAME);

        if ($this->isColumnModified(FlightTableMap::COL_ID)) {
            $criteria->add(FlightTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_AIRCRAFT_ID)) {
            $criteria->add(FlightTableMap::COL_AIRCRAFT_ID, $this->aircraft_id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_AIRCRAFT_MODEL_ID)) {
            $criteria->add(FlightTableMap::COL_AIRCRAFT_MODEL_ID, $this->aircraft_model_id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_AIRLINE_ID)) {
            $criteria->add(FlightTableMap::COL_AIRLINE_ID, $this->airline_id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_DESTINATION_ID)) {
            $criteria->add(FlightTableMap::COL_DESTINATION_ID, $this->destination_id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_DEPARTURE_ID)) {
            $criteria->add(FlightTableMap::COL_DEPARTURE_ID, $this->departure_id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_PILOT_ID)) {
            $criteria->add(FlightTableMap::COL_PILOT_ID, $this->pilot_id);
        }
        if ($this->isColumnModified(FlightTableMap::COL_FLIGHT_NUMBER)) {
            $criteria->add(FlightTableMap::COL_FLIGHT_NUMBER, $this->flight_number);
        }
        if ($this->isColumnModified(FlightTableMap::COL_STATUS)) {
            $criteria->add(FlightTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(FlightTableMap::COL_PACKAGES)) {
            $criteria->add(FlightTableMap::COL_PACKAGES, $this->packages);
        }
        if ($this->isColumnModified(FlightTableMap::COL_POST)) {
            $criteria->add(FlightTableMap::COL_POST, $this->post);
        }
        if ($this->isColumnModified(FlightTableMap::COL_PASSENGER_LOW)) {
            $criteria->add(FlightTableMap::COL_PASSENGER_LOW, $this->passenger_low);
        }
        if ($this->isColumnModified(FlightTableMap::COL_PASSENGER_MID)) {
            $criteria->add(FlightTableMap::COL_PASSENGER_MID, $this->passenger_mid);
        }
        if ($this->isColumnModified(FlightTableMap::COL_PASSENGER_HIGH)) {
            $criteria->add(FlightTableMap::COL_PASSENGER_HIGH, $this->passenger_high);
        }
        if ($this->isColumnModified(FlightTableMap::COL_FLIGHT_STARTED_AT)) {
            $criteria->add(FlightTableMap::COL_FLIGHT_STARTED_AT, $this->flight_started_at);
        }
        if ($this->isColumnModified(FlightTableMap::COL_FLIGHT_FINISHED_AT)) {
            $criteria->add(FlightTableMap::COL_FLIGHT_FINISHED_AT, $this->flight_finished_at);
        }
        if ($this->isColumnModified(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT)) {
            $criteria->add(FlightTableMap::COL_NEXT_STEP_POSSIBLE_AT, $this->next_step_possible_at);
        }
        if ($this->isColumnModified(FlightTableMap::COL_CREATED_AT)) {
            $criteria->add(FlightTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(FlightTableMap::COL_UPDATED_AT)) {
            $criteria->add(FlightTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildFlightQuery::create();
        $criteria->add(FlightTableMap::COL_ID, $this->id);
        $criteria->add(FlightTableMap::COL_AIRCRAFT_ID, $this->aircraft_id);
        $criteria->add(FlightTableMap::COL_AIRCRAFT_MODEL_ID, $this->aircraft_model_id);
        $criteria->add(FlightTableMap::COL_AIRLINE_ID, $this->airline_id);
        $criteria->add(FlightTableMap::COL_DESTINATION_ID, $this->destination_id);
        $criteria->add(FlightTableMap::COL_DEPARTURE_ID, $this->departure_id);
        $criteria->add(FlightTableMap::COL_PILOT_ID, $this->pilot_id);

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
            null !== $this->getAircraftId() &&
            null !== $this->getAircraftModelId() &&
            null !== $this->getAirlineId() &&
            null !== $this->getDestinationId() &&
            null !== $this->getDepartureId() &&
            null !== $this->getPilotId();

        $validPrimaryKeyFKs = 6;
        $primaryKeyFKs = [];

        //relation flights_fk_c3deee to table aircrafts
        if ($this->aAircraft && $hash = spl_object_hash($this->aAircraft)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation flights_fk_533ab3 to table aircraft_models
        if ($this->aAircraftModel && $hash = spl_object_hash($this->aAircraftModel)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation flights_fk_3c541c to table airlines
        if ($this->aAirline && $hash = spl_object_hash($this->aAirline)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation flights_fk_a39f89 to table airports
        if ($this->aDestination && $hash = spl_object_hash($this->aDestination)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation flights_fk_cbe538 to table airports
        if ($this->aDeparture && $hash = spl_object_hash($this->aDeparture)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation flights_fk_17d49f to table pilots
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
        $pks[1] = $this->getAircraftId();
        $pks[2] = $this->getAircraftModelId();
        $pks[3] = $this->getAirlineId();
        $pks[4] = $this->getDestinationId();
        $pks[5] = $this->getDepartureId();
        $pks[6] = $this->getPilotId();

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
        $this->setAircraftId($keys[1]);
        $this->setAircraftModelId($keys[2]);
        $this->setAirlineId($keys[3]);
        $this->setDestinationId($keys[4]);
        $this->setDepartureId($keys[5]);
        $this->setPilotId($keys[6]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getId()) && (null === $this->getAircraftId()) && (null === $this->getAircraftModelId()) && (null === $this->getAirlineId()) && (null === $this->getDestinationId()) && (null === $this->getDepartureId()) && (null === $this->getPilotId());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Flight (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAircraftId($this->getAircraftId());
        $copyObj->setAircraftModelId($this->getAircraftModelId());
        $copyObj->setAirlineId($this->getAirlineId());
        $copyObj->setDestinationId($this->getDestinationId());
        $copyObj->setDepartureId($this->getDepartureId());
        $copyObj->setPilotId($this->getPilotId());
        $copyObj->setFlightNumber($this->getFlightNumber());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setPackages($this->getPackages());
        $copyObj->setPost($this->getPost());
        $copyObj->setPassengerLow($this->getPassengerLow());
        $copyObj->setPassengerMid($this->getPassengerMid());
        $copyObj->setPassengerHigh($this->getPassengerHigh());
        $copyObj->setFlightStartedAt($this->getFlightStartedAt());
        $copyObj->setFlightFinishedAt($this->getFlightFinishedAt());
        $copyObj->setNextStepPossibleAt($this->getNextStepPossibleAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getFreights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFreight($relObj->copy($deepCopy));
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
     * @return \Model\Flight Clone of current object.
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
     * Declares an association between this object and a ChildAircraft object.
     *
     * @param  ChildAircraft $v
     * @return $this|\Model\Flight The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAircraft(ChildAircraft $v = null)
    {
        if ($v === null) {
            $this->setAircraftId(NULL);
        } else {
            $this->setAircraftId($v->getId());
        }

        $this->aAircraft = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAircraft object, it will not be re-added.
        if ($v !== null) {
            $v->addFlight($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAircraft object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAircraft The associated ChildAircraft object.
     * @throws PropelException
     */
    public function getAircraft(ConnectionInterface $con = null)
    {
        if ($this->aAircraft === null && ($this->aircraft_id !== null)) {
            $this->aAircraft = ChildAircraftQuery::create()
                ->filterByFlight($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAircraft->addFlights($this);
             */
        }

        return $this->aAircraft;
    }

    /**
     * Declares an association between this object and a ChildAircraftModel object.
     *
     * @param  ChildAircraftModel $v
     * @return $this|\Model\Flight The current object (for fluent API support)
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
            $v->addFlight($this);
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
                $this->aAircraftModel->addFlights($this);
             */
        }

        return $this->aAircraftModel;
    }

    /**
     * Declares an association between this object and a ChildAirline object.
     *
     * @param  ChildAirline $v
     * @return $this|\Model\Flight The current object (for fluent API support)
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
            $v->addFlight($this);
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
                $this->aAirline->addFlights($this);
             */
        }

        return $this->aAirline;
    }

    /**
     * Declares an association between this object and a ChildAirport object.
     *
     * @param  ChildAirport $v
     * @return $this|\Model\Flight The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDestination(ChildAirport $v = null)
    {
        if ($v === null) {
            $this->setDestinationId(NULL);
        } else {
            $this->setDestinationId($v->getId());
        }

        $this->aDestination = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAirport object, it will not be re-added.
        if ($v !== null) {
            $v->addFlightRelatedByDestinationId($this);
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
    public function getDestination(ConnectionInterface $con = null)
    {
        if ($this->aDestination === null && ($this->destination_id !== null)) {
            $this->aDestination = ChildAirportQuery::create()->findPk($this->destination_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDestination->addFlightsRelatedByDestinationId($this);
             */
        }

        return $this->aDestination;
    }

    /**
     * Declares an association between this object and a ChildAirport object.
     *
     * @param  ChildAirport $v
     * @return $this|\Model\Flight The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDeparture(ChildAirport $v = null)
    {
        if ($v === null) {
            $this->setDepartureId(NULL);
        } else {
            $this->setDepartureId($v->getId());
        }

        $this->aDeparture = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAirport object, it will not be re-added.
        if ($v !== null) {
            $v->addFlightRelatedByDepartureId($this);
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
    public function getDeparture(ConnectionInterface $con = null)
    {
        if ($this->aDeparture === null && ($this->departure_id !== null)) {
            $this->aDeparture = ChildAirportQuery::create()->findPk($this->departure_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDeparture->addFlightsRelatedByDepartureId($this);
             */
        }

        return $this->aDeparture;
    }

    /**
     * Declares an association between this object and a ChildPilot object.
     *
     * @param  ChildPilot $v
     * @return $this|\Model\Flight The current object (for fluent API support)
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
            $v->addFlight($this);
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
                $this->aPilot->addFlights($this);
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
        if ('Freight' == $relationName) {
            return $this->initFreights();
        }
    }

    /**
     * Clears out the collFreights collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFreights()
     */
    public function clearFreights()
    {
        $this->collFreights = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFreights collection loaded partially.
     */
    public function resetPartialFreights($v = true)
    {
        $this->collFreightsPartial = $v;
    }

    /**
     * Initializes the collFreights collection.
     *
     * By default this just sets the collFreights collection to an empty array (like clearcollFreights());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFreights($overrideExisting = true)
    {
        if (null !== $this->collFreights && !$overrideExisting) {
            return;
        }
        $this->collFreights = new ObjectCollection();
        $this->collFreights->setModel('\Model\Freight');
    }

    /**
     * Gets an array of ChildFreight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFlight is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     * @throws PropelException
     */
    public function getFreights(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsPartial && !$this->isNew();
        if (null === $this->collFreights || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFreights) {
                // return empty collection
                $this->initFreights();
            } else {
                $collFreights = ChildFreightQuery::create(null, $criteria)
                    ->filterByOnFlight($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFreightsPartial && count($collFreights)) {
                        $this->initFreights(false);

                        foreach ($collFreights as $obj) {
                            if (false == $this->collFreights->contains($obj)) {
                                $this->collFreights->append($obj);
                            }
                        }

                        $this->collFreightsPartial = true;
                    }

                    return $collFreights;
                }

                if ($partial && $this->collFreights) {
                    foreach ($this->collFreights as $obj) {
                        if ($obj->isNew()) {
                            $collFreights[] = $obj;
                        }
                    }
                }

                $this->collFreights = $collFreights;
                $this->collFreightsPartial = false;
            }
        }

        return $this->collFreights;
    }

    /**
     * Sets a collection of ChildFreight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $freights A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFlight The current object (for fluent API support)
     */
    public function setFreights(Collection $freights, ConnectionInterface $con = null)
    {
        /** @var ChildFreight[] $freightsToDelete */
        $freightsToDelete = $this->getFreights(new Criteria(), $con)->diff($freights);


        $this->freightsScheduledForDeletion = $freightsToDelete;

        foreach ($freightsToDelete as $freightRemoved) {
            $freightRemoved->setOnFlight(null);
        }

        $this->collFreights = null;
        foreach ($freights as $freight) {
            $this->addFreight($freight);
        }

        $this->collFreights = $freights;
        $this->collFreightsPartial = false;

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
    public function countFreights(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFreightsPartial && !$this->isNew();
        if (null === $this->collFreights || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFreights) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFreights());
            }

            $query = ChildFreightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOnFlight($this)
                ->count($con);
        }

        return count($this->collFreights);
    }

    /**
     * Method called to associate a ChildFreight object to this object
     * through the ChildFreight foreign key attribute.
     *
     * @param  ChildFreight $l ChildFreight
     * @return $this|\Model\Flight The current object (for fluent API support)
     */
    public function addFreight(ChildFreight $l)
    {
        if ($this->collFreights === null) {
            $this->initFreights();
            $this->collFreightsPartial = true;
        }

        if (!$this->collFreights->contains($l)) {
            $this->doAddFreight($l);
        }

        return $this;
    }

    /**
     * @param ChildFreight $freight The ChildFreight object to add.
     */
    protected function doAddFreight(ChildFreight $freight)
    {
        $this->collFreights[]= $freight;
        $freight->setOnFlight($this);
    }

    /**
     * @param  ChildFreight $freight The ChildFreight object to remove.
     * @return $this|ChildFlight The current object (for fluent API support)
     */
    public function removeFreight(ChildFreight $freight)
    {
        if ($this->getFreights()->contains($freight)) {
            $pos = $this->collFreights->search($freight);
            $this->collFreights->remove($pos);
            if (null === $this->freightsScheduledForDeletion) {
                $this->freightsScheduledForDeletion = clone $this->collFreights;
                $this->freightsScheduledForDeletion->clear();
            }
            $this->freightsScheduledForDeletion[]= $freight;
            $freight->setOnFlight(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Flight is new, it will return
     * an empty collection; or if this Flight has previously
     * been saved, it will retrieve related Freights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Flight.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     */
    public function getFreightsJoinDestination(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFreightQuery::create(null, $criteria);
        $query->joinWith('Destination', $joinBehavior);

        return $this->getFreights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Flight is new, it will return
     * an empty collection; or if this Flight has previously
     * been saved, it will retrieve related Freights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Flight.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     */
    public function getFreightsJoinDeparture(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFreightQuery::create(null, $criteria);
        $query->joinWith('Departure', $joinBehavior);

        return $this->getFreights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Flight is new, it will return
     * an empty collection; or if this Flight has previously
     * been saved, it will retrieve related Freights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Flight.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFreight[] List of ChildFreight objects
     */
    public function getFreightsJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFreightQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getFreights($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAircraft) {
            $this->aAircraft->removeFlight($this);
        }
        if (null !== $this->aAircraftModel) {
            $this->aAircraftModel->removeFlight($this);
        }
        if (null !== $this->aAirline) {
            $this->aAirline->removeFlight($this);
        }
        if (null !== $this->aDestination) {
            $this->aDestination->removeFlightRelatedByDestinationId($this);
        }
        if (null !== $this->aDeparture) {
            $this->aDeparture->removeFlightRelatedByDepartureId($this);
        }
        if (null !== $this->aPilot) {
            $this->aPilot->removeFlight($this);
        }
        $this->id = null;
        $this->aircraft_id = null;
        $this->aircraft_model_id = null;
        $this->airline_id = null;
        $this->destination_id = null;
        $this->departure_id = null;
        $this->pilot_id = null;
        $this->flight_number = null;
        $this->status = null;
        $this->packages = null;
        $this->post = null;
        $this->passenger_low = null;
        $this->passenger_mid = null;
        $this->passenger_high = null;
        $this->flight_started_at = null;
        $this->flight_finished_at = null;
        $this->next_step_possible_at = null;
        $this->created_at = null;
        $this->updated_at = null;
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
            if ($this->collFreights) {
                foreach ($this->collFreights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collFreights = null;
        $this->aAircraft = null;
        $this->aAircraftModel = null;
        $this->aAirline = null;
        $this->aDestination = null;
        $this->aDeparture = null;
        $this->aPilot = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FlightTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildFlight The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[FlightTableMap::COL_UPDATED_AT] = true;

        return $this;
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

<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Aircraft as ChildAircraft;
use Model\AircraftQuery as ChildAircraftQuery;
use Model\Airline as ChildAirline;
use Model\AirlineQuery as ChildAirlineQuery;
use Model\Flight as ChildFlight;
use Model\FlightQuery as ChildFlightQuery;
use Model\Pilot as ChildPilot;
use Model\PilotQuery as ChildPilotQuery;
use Model\Map\AirlineTableMap;
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
 * Base class that represents a row from the 'airlines' table.
 *
 *
 *
* @package    propel.generator.Model.Base
*/
abstract class Airline implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\AirlineTableMap';


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
     * The value for the alias field.
     * @var        string
     */
    protected $alias;

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
     * The value for the callsign field.
     * @var        string
     */
    protected $callsign;

    /**
     * The value for the country field.
     * @var        string
     */
    protected $country;

    /**
     * The value for the active field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $active;

    /**
     * The value for the saldo field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $saldo;

    /**
     * @var        ObjectCollection|ChildAircraft[] Collection to store aggregation of ChildAircraft objects.
     */
    protected $collAircrafts;
    protected $collAircraftsPartial;

    /**
     * @var        ObjectCollection|ChildFlight[] Collection to store aggregation of ChildFlight objects.
     */
    protected $collFlights;
    protected $collFlightsPartial;

    /**
     * @var        ObjectCollection|ChildPilot[] Collection to store aggregation of ChildPilot objects.
     */
    protected $collPilots;
    protected $collPilotsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAircraft[]
     */
    protected $aircraftsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFlight[]
     */
    protected $flightsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPilot[]
     */
    protected $pilotsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->active = false;
        $this->saldo = 0;
    }

    /**
     * Initializes internal state of Model\Base\Airline object.
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
     * Compares this with another <code>Airline</code> instance.  If
     * <code>obj</code> is an instance of <code>Airline</code>, delegates to
     * <code>equals(Airline)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Airline The current object, for fluid interface
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
     * Get the [alias] column value.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
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
     * Get the [callsign] column value.
     *
     * @return string
     */
    public function getCallsign()
    {
        return $this->callsign;
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
     * Get the [active] column value.
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get the [active] column value.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->getActive();
    }

    /**
     * Get the [saldo] column value.
     *
     * @return int
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AirlineTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[AirlineTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [alias] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setAlias($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->alias !== $v) {
            $this->alias = $v;
            $this->modifiedColumns[AirlineTableMap::COL_ALIAS] = true;
        }

        return $this;
    } // setAlias()

    /**
     * Set the value of [iata] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setIATA($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->iata !== $v) {
            $this->iata = $v;
            $this->modifiedColumns[AirlineTableMap::COL_IATA] = true;
        }

        return $this;
    } // setIATA()

    /**
     * Set the value of [icao] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setICAO($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->icao !== $v) {
            $this->icao = $v;
            $this->modifiedColumns[AirlineTableMap::COL_ICAO] = true;
        }

        return $this;
    } // setICAO()

    /**
     * Set the value of [callsign] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setCallsign($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->callsign !== $v) {
            $this->callsign = $v;
            $this->modifiedColumns[AirlineTableMap::COL_CALLSIGN] = true;
        }

        return $this;
    } // setCallsign()

    /**
     * Set the value of [country] column.
     *
     * @param string $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[AirlineTableMap::COL_COUNTRY] = true;
        }

        return $this;
    } // setCountry()

    /**
     * Sets the value of the [active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[AirlineTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [saldo] column.
     *
     * @param int $v new value
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function setSaldo($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->saldo !== $v) {
            $this->saldo = $v;
            $this->modifiedColumns[AirlineTableMap::COL_SALDO] = true;
        }

        return $this;
    } // setSaldo()

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
            if ($this->active !== false) {
                return false;
            }

            if ($this->saldo !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AirlineTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AirlineTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AirlineTableMap::translateFieldName('Alias', TableMap::TYPE_PHPNAME, $indexType)];
            $this->alias = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AirlineTableMap::translateFieldName('IATA', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iata = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AirlineTableMap::translateFieldName('ICAO', TableMap::TYPE_PHPNAME, $indexType)];
            $this->icao = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AirlineTableMap::translateFieldName('Callsign', TableMap::TYPE_PHPNAME, $indexType)];
            $this->callsign = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AirlineTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AirlineTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AirlineTableMap::translateFieldName('Saldo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->saldo = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = AirlineTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Airline'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(AirlineTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAirlineQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAircrafts = null;

            $this->collFlights = null;

            $this->collPilots = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Airline::setDeleted()
     * @see Airline::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AirlineTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAirlineQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AirlineTableMap::DATABASE_NAME);
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
                AirlineTableMap::addInstanceToPool($this);
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

            if ($this->aircraftsScheduledForDeletion !== null) {
                if (!$this->aircraftsScheduledForDeletion->isEmpty()) {
                    \Model\AircraftQuery::create()
                        ->filterByPrimaryKeys($this->aircraftsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
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

            if ($this->pilotsScheduledForDeletion !== null) {
                if (!$this->pilotsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pilotsScheduledForDeletion as $pilot) {
                        // need to save related object because we set the relation to null
                        $pilot->save($con);
                    }
                    $this->pilotsScheduledForDeletion = null;
                }
            }

            if ($this->collPilots !== null) {
                foreach ($this->collPilots as $referrerFK) {
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AirlineTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_ALIAS)) {
            $modifiedColumns[':p' . $index++]  = 'alias';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_IATA)) {
            $modifiedColumns[':p' . $index++]  = 'iata';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_ICAO)) {
            $modifiedColumns[':p' . $index++]  = 'icao';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_CALLSIGN)) {
            $modifiedColumns[':p' . $index++]  = 'callsign';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'country';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }
        if ($this->isColumnModified(AirlineTableMap::COL_SALDO)) {
            $modifiedColumns[':p' . $index++]  = 'saldo';
        }

        $sql = sprintf(
            'INSERT INTO airlines (%s) VALUES (%s)',
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
                    case 'alias':
                        $stmt->bindValue($identifier, $this->alias, PDO::PARAM_STR);
                        break;
                    case 'iata':
                        $stmt->bindValue($identifier, $this->iata, PDO::PARAM_STR);
                        break;
                    case 'icao':
                        $stmt->bindValue($identifier, $this->icao, PDO::PARAM_STR);
                        break;
                    case 'callsign':
                        $stmt->bindValue($identifier, $this->callsign, PDO::PARAM_STR);
                        break;
                    case 'country':
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_STR);
                        break;
                    case 'active':
                        $stmt->bindValue($identifier, (int) $this->active, PDO::PARAM_INT);
                        break;
                    case 'saldo':
                        $stmt->bindValue($identifier, $this->saldo, PDO::PARAM_INT);
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
        $pos = AirlineTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAlias();
                break;
            case 3:
                return $this->getIATA();
                break;
            case 4:
                return $this->getICAO();
                break;
            case 5:
                return $this->getCallsign();
                break;
            case 6:
                return $this->getCountry();
                break;
            case 7:
                return $this->getActive();
                break;
            case 8:
                return $this->getSaldo();
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

        if (isset($alreadyDumpedObjects['Airline'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Airline'][$this->hashCode()] = true;
        $keys = AirlineTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getAlias(),
            $keys[3] => $this->getIATA(),
            $keys[4] => $this->getICAO(),
            $keys[5] => $this->getCallsign(),
            $keys[6] => $this->getCountry(),
            $keys[7] => $this->getActive(),
            $keys[8] => $this->getSaldo(),
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
            if (null !== $this->collPilots) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'pilots';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pilotss';
                        break;
                    default:
                        $key = 'Pilots';
                }

                $result[$key] = $this->collPilots->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Model\Airline
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AirlineTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Airline
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
                $this->setAlias($value);
                break;
            case 3:
                $this->setIATA($value);
                break;
            case 4:
                $this->setICAO($value);
                break;
            case 5:
                $this->setCallsign($value);
                break;
            case 6:
                $this->setCountry($value);
                break;
            case 7:
                $this->setActive($value);
                break;
            case 8:
                $this->setSaldo($value);
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
        $keys = AirlineTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAlias($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setIATA($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setICAO($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCallsign($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCountry($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setActive($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSaldo($arr[$keys[8]]);
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
     * @return $this|\Model\Airline The current object, for fluid interface
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
        $criteria = new Criteria(AirlineTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AirlineTableMap::COL_ID)) {
            $criteria->add(AirlineTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_NAME)) {
            $criteria->add(AirlineTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_ALIAS)) {
            $criteria->add(AirlineTableMap::COL_ALIAS, $this->alias);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_IATA)) {
            $criteria->add(AirlineTableMap::COL_IATA, $this->iata);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_ICAO)) {
            $criteria->add(AirlineTableMap::COL_ICAO, $this->icao);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_CALLSIGN)) {
            $criteria->add(AirlineTableMap::COL_CALLSIGN, $this->callsign);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_COUNTRY)) {
            $criteria->add(AirlineTableMap::COL_COUNTRY, $this->country);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_ACTIVE)) {
            $criteria->add(AirlineTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(AirlineTableMap::COL_SALDO)) {
            $criteria->add(AirlineTableMap::COL_SALDO, $this->saldo);
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
        $criteria = ChildAirlineQuery::create();
        $criteria->add(AirlineTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Model\Airline (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setId($this->getId());
        $copyObj->setName($this->getName());
        $copyObj->setAlias($this->getAlias());
        $copyObj->setIATA($this->getIATA());
        $copyObj->setICAO($this->getICAO());
        $copyObj->setCallsign($this->getCallsign());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setActive($this->getActive());
        $copyObj->setSaldo($this->getSaldo());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAircrafts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAircraft($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFlights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFlight($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPilots() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPilot($relObj->copy($deepCopy));
                }
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
     * @return \Model\Airline Clone of current object.
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
        if ('Flight' == $relationName) {
            return $this->initFlights();
        }
        if ('Pilot' == $relationName) {
            return $this->initPilots();
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
     * If this ChildAirline is new, it will return
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
                    ->filterByAirline($this)
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
     * @return $this|ChildAirline The current object (for fluent API support)
     */
    public function setAircrafts(Collection $aircrafts, ConnectionInterface $con = null)
    {
        /** @var ChildAircraft[] $aircraftsToDelete */
        $aircraftsToDelete = $this->getAircrafts(new Criteria(), $con)->diff($aircrafts);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->aircraftsScheduledForDeletion = clone $aircraftsToDelete;

        foreach ($aircraftsToDelete as $aircraftRemoved) {
            $aircraftRemoved->setAirline(null);
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
                ->filterByAirline($this)
                ->count($con);
        }

        return count($this->collAircrafts);
    }

    /**
     * Method called to associate a ChildAircraft object to this object
     * through the ChildAircraft foreign key attribute.
     *
     * @param  ChildAircraft $l ChildAircraft
     * @return $this|\Model\Airline The current object (for fluent API support)
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
        $aircraft->setAirline($this);
    }

    /**
     * @param  ChildAircraft $aircraft The ChildAircraft object to remove.
     * @return $this|ChildAirline The current object (for fluent API support)
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
            $this->aircraftsScheduledForDeletion[]= clone $aircraft;
            $aircraft->setAirline(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
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
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAircraft[] List of ChildAircraft objects
     */
    public function getAircraftsJoinAirport(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAircraftQuery::create(null, $criteria);
        $query->joinWith('Airport', $joinBehavior);

        return $this->getAircrafts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
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
     * If this ChildAirline is new, it will return
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
                    ->filterByAirline($this)
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
     * @return $this|ChildAirline The current object (for fluent API support)
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
            $flightRemoved->setAirline(null);
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
                ->filterByAirline($this)
                ->count($con);
        }

        return count($this->collFlights);
    }

    /**
     * Method called to associate a ChildFlight object to this object
     * through the ChildFlight foreign key attribute.
     *
     * @param  ChildFlight $l ChildFlight
     * @return $this|\Model\Airline The current object (for fluent API support)
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
        $flight->setAirline($this);
    }

    /**
     * @param  ChildFlight $flight The ChildFlight object to remove.
     * @return $this|ChildAirline The current object (for fluent API support)
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
            $flight->setAirline(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFlight[] List of ChildFlight objects
     */
    public function getFlightsJoinAircraft(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFlightQuery::create(null, $criteria);
        $query->joinWith('Aircraft', $joinBehavior);

        return $this->getFlights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
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
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
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
     * Otherwise if this Airline is new, it will return
     * an empty collection; or if this Airline has previously
     * been saved, it will retrieve related Flights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Airline.
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
     * Clears out the collPilots collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPilots()
     */
    public function clearPilots()
    {
        $this->collPilots = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPilots collection loaded partially.
     */
    public function resetPartialPilots($v = true)
    {
        $this->collPilotsPartial = $v;
    }

    /**
     * Initializes the collPilots collection.
     *
     * By default this just sets the collPilots collection to an empty array (like clearcollPilots());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPilots($overrideExisting = true)
    {
        if (null !== $this->collPilots && !$overrideExisting) {
            return;
        }
        $this->collPilots = new ObjectCollection();
        $this->collPilots->setModel('\Model\Pilot');
    }

    /**
     * Gets an array of ChildPilot objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAirline is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPilot[] List of ChildPilot objects
     * @throws PropelException
     */
    public function getPilots(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPilotsPartial && !$this->isNew();
        if (null === $this->collPilots || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPilots) {
                // return empty collection
                $this->initPilots();
            } else {
                $collPilots = ChildPilotQuery::create(null, $criteria)
                    ->filterByAirline($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPilotsPartial && count($collPilots)) {
                        $this->initPilots(false);

                        foreach ($collPilots as $obj) {
                            if (false == $this->collPilots->contains($obj)) {
                                $this->collPilots->append($obj);
                            }
                        }

                        $this->collPilotsPartial = true;
                    }

                    return $collPilots;
                }

                if ($partial && $this->collPilots) {
                    foreach ($this->collPilots as $obj) {
                        if ($obj->isNew()) {
                            $collPilots[] = $obj;
                        }
                    }
                }

                $this->collPilots = $collPilots;
                $this->collPilotsPartial = false;
            }
        }

        return $this->collPilots;
    }

    /**
     * Sets a collection of ChildPilot objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $pilots A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAirline The current object (for fluent API support)
     */
    public function setPilots(Collection $pilots, ConnectionInterface $con = null)
    {
        /** @var ChildPilot[] $pilotsToDelete */
        $pilotsToDelete = $this->getPilots(new Criteria(), $con)->diff($pilots);


        $this->pilotsScheduledForDeletion = $pilotsToDelete;

        foreach ($pilotsToDelete as $pilotRemoved) {
            $pilotRemoved->setAirline(null);
        }

        $this->collPilots = null;
        foreach ($pilots as $pilot) {
            $this->addPilot($pilot);
        }

        $this->collPilots = $pilots;
        $this->collPilotsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Pilot objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Pilot objects.
     * @throws PropelException
     */
    public function countPilots(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPilotsPartial && !$this->isNew();
        if (null === $this->collPilots || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPilots) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPilots());
            }

            $query = ChildPilotQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAirline($this)
                ->count($con);
        }

        return count($this->collPilots);
    }

    /**
     * Method called to associate a ChildPilot object to this object
     * through the ChildPilot foreign key attribute.
     *
     * @param  ChildPilot $l ChildPilot
     * @return $this|\Model\Airline The current object (for fluent API support)
     */
    public function addPilot(ChildPilot $l)
    {
        if ($this->collPilots === null) {
            $this->initPilots();
            $this->collPilotsPartial = true;
        }

        if (!$this->collPilots->contains($l)) {
            $this->doAddPilot($l);
        }

        return $this;
    }

    /**
     * @param ChildPilot $pilot The ChildPilot object to add.
     */
    protected function doAddPilot(ChildPilot $pilot)
    {
        $this->collPilots[]= $pilot;
        $pilot->setAirline($this);
    }

    /**
     * @param  ChildPilot $pilot The ChildPilot object to remove.
     * @return $this|ChildAirline The current object (for fluent API support)
     */
    public function removePilot(ChildPilot $pilot)
    {
        if ($this->getPilots()->contains($pilot)) {
            $pos = $this->collPilots->search($pilot);
            $this->collPilots->remove($pos);
            if (null === $this->pilotsScheduledForDeletion) {
                $this->pilotsScheduledForDeletion = clone $this->collPilots;
                $this->pilotsScheduledForDeletion->clear();
            }
            $this->pilotsScheduledForDeletion[]= $pilot;
            $pilot->setAirline(null);
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
        $this->alias = null;
        $this->iata = null;
        $this->icao = null;
        $this->callsign = null;
        $this->country = null;
        $this->active = null;
        $this->saldo = null;
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
            if ($this->collFlights) {
                foreach ($this->collFlights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPilots) {
                foreach ($this->collPilots as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAircrafts = null;
        $this->collFlights = null;
        $this->collPilots = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AirlineTableMap::DEFAULT_STRING_FORMAT);
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

<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Aircraft as ChildAircraft;
use Model\AircraftQuery as ChildAircraftQuery;
use Model\AircraftType as ChildAircraftType;
use Model\AircraftTypeQuery as ChildAircraftTypeQuery;
use Model\Map\AircraftTypeTableMap;
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
 * Base class that represents a row from the 'aircraft_types' table.
 *
 *
 *
* @package    propel.generator.Model.Base
*/
abstract class AircraftType implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\AircraftTypeTableMap';


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
     * The value for the model field.
     * @var        string
     */
    protected $model;

    /**
     * The value for the brand field.
     * @var        string
     */
    protected $brand;

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
     * The value for the weight field.
     * @var        int
     */
    protected $weight;

    /**
     * The value for the value field.
     * @var        int
     */
    protected $value;

    /**
     * @var        ObjectCollection|ChildAircraft[] Collection to store aggregation of ChildAircraft objects.
     */
    protected $collAircrafts;
    protected $collAircraftsPartial;

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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->packages = 0;
        $this->post = 0;
        $this->passenger_low = 0;
        $this->passenger_mid = 0;
        $this->passenger_high = 0;
    }

    /**
     * Initializes internal state of Model\Base\AircraftType object.
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
     * Compares this with another <code>AircraftType</code> instance.  If
     * <code>obj</code> is an instance of <code>AircraftType</code>, delegates to
     * <code>equals(AircraftType)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|AircraftType The current object, for fluid interface
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
     * Get the [model] column value.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the [brand] column value.
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
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
     * Get the [weight] column value.
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Get the [value] column value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [model] column.
     *
     * @param string $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setModel($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->model !== $v) {
            $this->model = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_MODEL] = true;
        }

        return $this;
    } // setModel()

    /**
     * Set the value of [brand] column.
     *
     * @param string $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setBrand($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->brand !== $v) {
            $this->brand = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_BRAND] = true;
        }

        return $this;
    } // setBrand()

    /**
     * Set the value of [packages] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setPackages($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->packages !== $v) {
            $this->packages = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_PACKAGES] = true;
        }

        return $this;
    } // setPackages()

    /**
     * Set the value of [post] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setPost($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->post !== $v) {
            $this->post = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_POST] = true;
        }

        return $this;
    } // setPost()

    /**
     * Set the value of [passenger_low] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setPassengerLow($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->passenger_low !== $v) {
            $this->passenger_low = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_PASSENGER_LOW] = true;
        }

        return $this;
    } // setPassengerLow()

    /**
     * Set the value of [passenger_mid] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setPassengerMid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->passenger_mid !== $v) {
            $this->passenger_mid = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_PASSENGER_MID] = true;
        }

        return $this;
    } // setPassengerMid()

    /**
     * Set the value of [passenger_high] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setPassengerHigh($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->passenger_high !== $v) {
            $this->passenger_high = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_PASSENGER_HIGH] = true;
        }

        return $this;
    } // setPassengerHigh()

    /**
     * Set the value of [weight] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->weight !== $v) {
            $this->weight = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_WEIGHT] = true;
        }

        return $this;
    } // setWeight()

    /**
     * Set the value of [value] column.
     *
     * @param int $v new value
     * @return $this|\Model\AircraftType The current object (for fluent API support)
     */
    public function setValue($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->value !== $v) {
            $this->value = $v;
            $this->modifiedColumns[AircraftTypeTableMap::COL_VALUE] = true;
        }

        return $this;
    } // setValue()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AircraftTypeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AircraftTypeTableMap::translateFieldName('Model', TableMap::TYPE_PHPNAME, $indexType)];
            $this->model = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AircraftTypeTableMap::translateFieldName('Brand', TableMap::TYPE_PHPNAME, $indexType)];
            $this->brand = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AircraftTypeTableMap::translateFieldName('Packages', TableMap::TYPE_PHPNAME, $indexType)];
            $this->packages = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AircraftTypeTableMap::translateFieldName('Post', TableMap::TYPE_PHPNAME, $indexType)];
            $this->post = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AircraftTypeTableMap::translateFieldName('PassengerLow', TableMap::TYPE_PHPNAME, $indexType)];
            $this->passenger_low = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AircraftTypeTableMap::translateFieldName('PassengerMid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->passenger_mid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AircraftTypeTableMap::translateFieldName('PassengerHigh', TableMap::TYPE_PHPNAME, $indexType)];
            $this->passenger_high = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AircraftTypeTableMap::translateFieldName('Weight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : AircraftTypeTableMap::translateFieldName('Value', TableMap::TYPE_PHPNAME, $indexType)];
            $this->value = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = AircraftTypeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\AircraftType'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(AircraftTypeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAircraftTypeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAircrafts = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see AircraftType::setDeleted()
     * @see AircraftType::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTypeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAircraftTypeQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AircraftTypeTableMap::DATABASE_NAME);
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
                AircraftTypeTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[AircraftTypeTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AircraftTypeTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AircraftTypeTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_MODEL)) {
            $modifiedColumns[':p' . $index++]  = 'model';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_BRAND)) {
            $modifiedColumns[':p' . $index++]  = 'brand';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PACKAGES)) {
            $modifiedColumns[':p' . $index++]  = 'packages';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_POST)) {
            $modifiedColumns[':p' . $index++]  = 'post';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PASSENGER_LOW)) {
            $modifiedColumns[':p' . $index++]  = 'passenger_low';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PASSENGER_MID)) {
            $modifiedColumns[':p' . $index++]  = 'passenger_mid';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PASSENGER_HIGH)) {
            $modifiedColumns[':p' . $index++]  = 'passenger_high';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'weight';
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'value';
        }

        $sql = sprintf(
            'INSERT INTO aircraft_types (%s) VALUES (%s)',
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
                    case 'model':
                        $stmt->bindValue($identifier, $this->model, PDO::PARAM_STR);
                        break;
                    case 'brand':
                        $stmt->bindValue($identifier, $this->brand, PDO::PARAM_STR);
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
                    case 'weight':
                        $stmt->bindValue($identifier, $this->weight, PDO::PARAM_INT);
                        break;
                    case 'value':
                        $stmt->bindValue($identifier, $this->value, PDO::PARAM_INT);
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
        $pos = AircraftTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getModel();
                break;
            case 2:
                return $this->getBrand();
                break;
            case 3:
                return $this->getPackages();
                break;
            case 4:
                return $this->getPost();
                break;
            case 5:
                return $this->getPassengerLow();
                break;
            case 6:
                return $this->getPassengerMid();
                break;
            case 7:
                return $this->getPassengerHigh();
                break;
            case 8:
                return $this->getWeight();
                break;
            case 9:
                return $this->getValue();
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

        if (isset($alreadyDumpedObjects['AircraftType'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['AircraftType'][$this->hashCode()] = true;
        $keys = AircraftTypeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getModel(),
            $keys[2] => $this->getBrand(),
            $keys[3] => $this->getPackages(),
            $keys[4] => $this->getPost(),
            $keys[5] => $this->getPassengerLow(),
            $keys[6] => $this->getPassengerMid(),
            $keys[7] => $this->getPassengerHigh(),
            $keys[8] => $this->getWeight(),
            $keys[9] => $this->getValue(),
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
     * @return $this|\Model\AircraftType
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AircraftTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\AircraftType
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setModel($value);
                break;
            case 2:
                $this->setBrand($value);
                break;
            case 3:
                $this->setPackages($value);
                break;
            case 4:
                $this->setPost($value);
                break;
            case 5:
                $this->setPassengerLow($value);
                break;
            case 6:
                $this->setPassengerMid($value);
                break;
            case 7:
                $this->setPassengerHigh($value);
                break;
            case 8:
                $this->setWeight($value);
                break;
            case 9:
                $this->setValue($value);
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
        $keys = AircraftTypeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setModel($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setBrand($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPackages($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPost($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPassengerLow($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPassengerMid($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPassengerHigh($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setWeight($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setValue($arr[$keys[9]]);
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
     * @return $this|\Model\AircraftType The current object, for fluid interface
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
        $criteria = new Criteria(AircraftTypeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AircraftTypeTableMap::COL_ID)) {
            $criteria->add(AircraftTypeTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_MODEL)) {
            $criteria->add(AircraftTypeTableMap::COL_MODEL, $this->model);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_BRAND)) {
            $criteria->add(AircraftTypeTableMap::COL_BRAND, $this->brand);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PACKAGES)) {
            $criteria->add(AircraftTypeTableMap::COL_PACKAGES, $this->packages);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_POST)) {
            $criteria->add(AircraftTypeTableMap::COL_POST, $this->post);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PASSENGER_LOW)) {
            $criteria->add(AircraftTypeTableMap::COL_PASSENGER_LOW, $this->passenger_low);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PASSENGER_MID)) {
            $criteria->add(AircraftTypeTableMap::COL_PASSENGER_MID, $this->passenger_mid);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_PASSENGER_HIGH)) {
            $criteria->add(AircraftTypeTableMap::COL_PASSENGER_HIGH, $this->passenger_high);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_WEIGHT)) {
            $criteria->add(AircraftTypeTableMap::COL_WEIGHT, $this->weight);
        }
        if ($this->isColumnModified(AircraftTypeTableMap::COL_VALUE)) {
            $criteria->add(AircraftTypeTableMap::COL_VALUE, $this->value);
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
        $criteria = ChildAircraftTypeQuery::create();
        $criteria->add(AircraftTypeTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Model\AircraftType (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setModel($this->getModel());
        $copyObj->setBrand($this->getBrand());
        $copyObj->setPackages($this->getPackages());
        $copyObj->setPost($this->getPost());
        $copyObj->setPassengerLow($this->getPassengerLow());
        $copyObj->setPassengerMid($this->getPassengerMid());
        $copyObj->setPassengerHigh($this->getPassengerHigh());
        $copyObj->setWeight($this->getWeight());
        $copyObj->setValue($this->getValue());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAircrafts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAircraft($relObj->copy($deepCopy));
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
     * @return \Model\AircraftType Clone of current object.
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
     * If this ChildAircraftType is new, it will return
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
                    ->filterByAircraftType($this)
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
     * @return $this|ChildAircraftType The current object (for fluent API support)
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
            $aircraftRemoved->setAircraftType(null);
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
                ->filterByAircraftType($this)
                ->count($con);
        }

        return count($this->collAircrafts);
    }

    /**
     * Method called to associate a ChildAircraft object to this object
     * through the ChildAircraft foreign key attribute.
     *
     * @param  ChildAircraft $l ChildAircraft
     * @return $this|\Model\AircraftType The current object (for fluent API support)
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
        $aircraft->setAircraftType($this);
    }

    /**
     * @param  ChildAircraft $aircraft The ChildAircraft object to remove.
     * @return $this|ChildAircraftType The current object (for fluent API support)
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
            $aircraft->setAircraftType(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this AircraftType is new, it will return
     * an empty collection; or if this AircraftType has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in AircraftType.
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
     * Otherwise if this AircraftType is new, it will return
     * an empty collection; or if this AircraftType has previously
     * been saved, it will retrieve related Aircrafts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in AircraftType.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->model = null;
        $this->brand = null;
        $this->packages = null;
        $this->post = null;
        $this->passenger_low = null;
        $this->passenger_mid = null;
        $this->passenger_high = null;
        $this->weight = null;
        $this->value = null;
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
        } // if ($deep)

        $this->collAircrafts = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AircraftTypeTableMap::DEFAULT_STRING_FORMAT);
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

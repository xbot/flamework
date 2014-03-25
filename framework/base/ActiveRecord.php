<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\Flame;
use org\x3f\flamework\exceptions\FlameException;

/**
 * Ancestor class for active records
 *
 * @abstract
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
abstract class ActiveRecord
{
    /**
     * @var array Models 
     * @since 1.0
     */
    private static $_models = array();
    /**
     * @var array Attributes and values
     * @since 1.0
     */
    private $_data = array();
    /**
     * @var array Attributes and values which are changed
     * @since 1.0
     */
    private $_dirtyData = array();
    /**
     * @var bool Whether this record is a new one 
     * @since 1.0
     */
    private $_isNew = true;

    /**
     * Get model instance
     * @param string $className
     * @return ActiveRecord
     * @since 1.0
     */
    public static function getModel($className = __CLASS__)
    {
        if (isset(self::$_models[$className])) {
            return self::$_models[$className];
        } else {
            $model = self::$_models[$className] = new $className;
            return $model;
        }
    }
    
    /**
     * Get table name of this ActiveRecord
     * @return string Table name
     * @since 1.0
     */
    abstract public function getTableName();

    /**
     * Get the name of the primary key column
     * @return string Column name
     * @since 1.0
     */
    abstract public function getPrimaryKey();

    /**
     * Set the db connection
     * @param object Db connection instance
     * @return void
     * @since 1.0
     */
    public function setDbConnection($conn)
    {
        $this->_dbConnection = $conn;
    }
    
    /**
     * Get the db connection
     * @return object Db connection instance
     * @since 1.0
     */
    public function getDbConnection()
    {
        if ($this->_dbConnection == null)
            throw new FlameException('Failed to get db connection.');
        return $this->_dbConnection;
    }
    
    /**
     * Magic method for accessing model attributes
     * @param string $attr Attribute name
     * @return mixed Attribute value
     * @since 1.0
     */
    public function __get($attr)
    {
        if (isset($this->_data[$attr])) {
            return $this->_data[$attr];
        }
        return null;
    }
    
    /**
     * Magic method for setting attribute value
     * @param string $attr Attribute name
     * @param mixed $val Attribute value
     * @return void
     * @since 1.0
     */
    public function __set($attr, $val)
    {
        $this->_data[$attr] = $val;
        if (!$this->getIsNew()) {
            $this->_dirtyData[$attr] = $val;
        }
    }
    
    /**
     * Magic method for checking if an attribute is set
     * @param string $attr Attribute name
     * @return bool
     * @since 1.0
     */
    public function __isset($attr)
    {
        return isset($this->_data[$attr]);
    }
    
    /**
     * Magic method for unsetting an attribute
     * @param string $attr Attribute name
     * @return void
     * @since 1.0
     */
    public function __unset($attr)
    {
        unset($this->_data[$attr]);
    }
    
    /**
     * Set this record to be $isNew
     * @param bool $isNew
     * @return void
     * @since 1.0
     */
    public function setIsNew($isNew)
    {
        $this->_isNew = $isNew;
    }
    
    /**
     * Whether this record is new
     * @return bool
     * @since 1.0
     */
    public function getIsNew()
    {
        return $this->_isNew;
    }
    
    /**
     * Find a record by primary key
     * @param mixed $val Primary key value
     * @return ActiveRecord
     * @since 1.0
     */
    public function findByPk($val)
    {
        $sql = "select * from ".$this->getTableName()." where ".$this->getPrimaryKey()."=?";
        $rows = $this->getDbConnection()->rows($sql, array($val));
        if (count($rows) > 0) {
            return $this->createInstance($rows[0]);
        }
        return null;
    }
    
    /**
     * Create an instance with given data
     * @param array $row Associative array
     * @return ActiveRecord
     * @since 1.0
     */
    public function createInstance($row)
    {
        $className = get_class($this);
        $instance = new $className;
        foreach ($row as $col=>$val){
            $instance->$col = $val;
        }
        $instance->setIsNew(false);
        return $instance;
    }
    
    /**
     * Save this record
     * @return void
     * @since 1.0
     */
    public function save()
    {
        if ($this->getIsNew()) {
            $this->_insert();
        } else {
            $this->_update();
        }
    }
    
    /**
     * Save this record into the database as a new row
     * @return void
     * @since 1.0
     */
    private function _insert()
    {
        if (count($this->_data) > 0) {
            $cols = implode(', ', array_keys($this->_data));
            $placeHolders = implode(', ', array_fill(0, count($this->_data), '?'));
            $sql = "insert into ".$this->getTableName(). " ($cols) values ($placeHolders)";
            $this->getDbConnection()->execute($sql, array_values($this->_data));
        }
    }
    
    /**
     * Save this record
     * @return void
     * @since 1.0
     */
    private function _update()
    {
        if (count($this->_dirtyData) > 0) {
            $pairs = implode('=?, ', array_keys($this->_dirtyData)).'=?';
            $sql = 'update '.$this->getTableName()." set $pairs where ".$this->getPrimaryKey().'=?';
            $pk = $this->getPrimaryKey();
            $this->getDbConnection()->execute($sql, array_merge(array_values($this->_dirtyData), array($this->$pk)));
        }
    }
    
    /**
     * Delete this record
     * @return void
     * @since 1.0
     */
    public function delete()
    {
        if (!$this->getIsNew()) {
            $pk = $this->getPrimaryKey();
            $sql = 'delete from '.$this->getTableName()." where $pk=?";
            $this->getDbConnection()->execute($sql, array($this->$pk));
        }
    }
    
}
?>

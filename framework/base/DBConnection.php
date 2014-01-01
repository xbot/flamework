<?php
namespace org\x3f\flamework\base;

/**
 * Database connection above PDO
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class DBConnection
{
    /**
     * @var PDO Database connection 
     * @since 1.0
     */
    private $_c;
    /**
     * @var array PDO options 
     * @since 1.0
     */
    private $_options = array(
        'connection_string' => 'sqlite::memory:',
        'username' => null,
        'password' => null,
        'pdo_options' => null,
    );
    /**
     * @var PDOStatement Last PDO statement 
     * @since 1.0
     */
    private $_lastStmt;

    public function __construct($options)
    {
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * Init DB connection
     * @param string $dsn DB connection string
     * @param string $user DB user name
     * @param string $password DB password
     * @param array $options PDO options
     * @return void
     * @since 1.0
     */
    private function _connectDB($dsn, $user='', $password='', $options=array())
    {
        if ($this->_c == null) {
            $this->_c = new \PDO($dsn, $user, $password, $options);
        }
    }
   
    /**
     * Execute sql statement
     * @param mixed $sql SQL statement or template
     * @param array $params Parameters for SQL template
     * @return bool
     * @since 1.0
     */
    public function execute($sql, $params=array())
    {
        $this->_connectDB(
            $this->_options['connection_string'],
            $this->_options['username'],
            $this->_options['password'],
            $this->_options['driver_options']
        );
        $stmt = $this->_c->prepare($sql);
        $this->_lastStmt = $stmt;
        return $stmt->execute($params);
    }
    
    /**
     * Fetch rows
     * @return array Associative array holding data rows
     * @since 1.0
     */
    public function rows($sql, $params=array())
    {
        $this->execute($sql, $params);
        $stmt = $this->getLastStmt();
        $rows = array();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    /**
     * Return the last PDO statement
     * @return PDOStatement
     * @since 1.0
     */
    public function getLastStmt()
    {
        return $this->_lastStmt;
    }
    
    /**
     * Begin transaction
     * @return void
     * @since 1.0
     */
    public function beginTransaction()
    {
        $this->_c->beginTransaction();
    }
    
    /**
     * Commit the current transaction
     * @return void
     * @since 1.0
     */
    public function commit()
    {
        $this->_c->commit();
    }
    
    /**
     * Rollback the current transaction
     * @return void
     * @since 1.0
     */
    public function rollback()
    {
        $this->_c->rollBack();
    }
    
}

?>

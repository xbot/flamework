<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\exceptions\FlameException;

/**
 * Dependency Injection Class
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class DI
{
    /**
     * @var object Singleton instance
     * @since 1.0
     */
    public static $_instance;
    /**
     * @var array Services
     * @since 1.0
     */
    private $_services = array();

    /**
     * Return the singleton instance
     * @return object Singleton instance
     * @since 1.0
     **/
    public static function getInstance()
    {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Add a service to the register
     * @param string $key Service name
     * @param mixed $service Callable to create a service instance or exactly an instance
     * @param bool $isSingleton Set true to treat this service as singleton
     * @return void
     * @since 1.0
     **/
    public function set($key, $service, $isSingleton=false)
    {
        $this->_services[strtolower($key)] = array(
            'service' => $service,
            'isSingleton' => $isSingleton,
            'instance' => null
        );
    }
    
    /**
     * Get a service instance
     * @return mixed Service instance
     * @since 1.0
     */
    public function get($key)
    {
        $key = strtolower($key);
        if (isset($this->_services[$key])) {
            $info = &$this->_services[$key];
            if ($info['instance'] !== null)
                return $info['instance'];
            if (is_callable($info['service'])) {
                $instance = call_user_func($info['service']);
                if ($info['isSingleton'] === true)
                    $info['instance'] = $instance;
                return $instance;
            } else {
                return $info['service'];
            }
        }
        return null;
    }
    
    /**
     * Get service with magic method
     * @param string $method get{ServiceName}
     * @param array $parameters Parameters, currently useless
     * @return mixed Service instance
     * @since 1.0
     */
    public function __call($method, $parameters)
    {
        if (strpos(strtolower($method), 'get') === 0) {
            $serviceName = substr($method, 3);
            return $this->get($serviceName);
        }
        throw new FlameException('Call to undefined method: '.$method);
    }
    
} // END class DI
?>

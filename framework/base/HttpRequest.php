<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\Flame as Flame;

/**
 * HTTP request wrapper
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class HttpRequest
{
    /**
     * @var HttpRequest Singleton instance 
     * @since 1.0
     */
    private static $_instance;
    /**
     * @var string Controller name, null if no one is given 
     * @since 1.0
     */
    private $_controller;
    /**
     * @var string Action name, null if no one is given 
     * @since 1.0
     */
    private $_action;

    /**
     * Singleton constructor
     * @return void
     * @since 1.0
     */
    private function __construct()
    {
        $this->parseRoute();
    }

    /**
     * Disable the cloning
     * @return void
     * @since 1.0
     */
    public function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    /**
     * Get the singleton instance
     * @return HttpRequest
     * @since 1.0
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self))
            self::$_instance = new self;
        return self::$_instance;
    }
    
    /**
     * Parse request route, set controller and action names
     *
     * @return void
     * @since 1.0
     */
    public function parseRoute()
    {
        if (isset($_GET['r'])) {
            $arr = explode('/', $_GET['r']);
            $this->_controller = $arr[0];
            if (count($arr)>1) $this->_action = $arr[1];
        } else {
            $this->_controller = Flame::app()->getDefaultController();
        }
    }
    
    /**
     * Get controller name
     *
     * @return string null if no controller is present
     * @since 1.0
     */
    public function getController()
    {
        return $this->_controller;
    }
    
    /**
     * Get action name
     *
     * @return string null if no action is found
     * @since 1.0
     */
    public function getAction()
    {
        return $this->_action;
    }
    
    /**
     * Get parameter value
     *
     * @param string $param Parameter name
     * @return mixed Parameter value
     * @since 1.0
     */
    public function getParam($param)
    {
        if (isset($_REQUEST[$param]))
            return $_REQUEST[$param];
        return null;
    }

}

?>

<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\Flame as Flame;
use org\x3f\flamework\logging\Logger as Logger;
use org\x3f\flamework\exceptions\FlameException, org\x3f\flamework\exceptions\HttpException;

/**
 * WebApplication class
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class WebApplication {
    /**
     * @var array Namespaces and their corresponding paths, used for class auto-loading
     * @since 1.0
     */
    public $namespaces;
    /**
     * @var array Filter classes 
     * @since 1.0
     */
    public $filters;
    /**
     * @var boolean Whether to enable error auto-handling, default to true 
     * @since 1.0
     */
    public $enableErrorHandling = true;
    /**
     * @var boolean Whether to enable exception auto-handling, default to true 
     * @since 1.0
     */
    public $enableExceptionHandling = true;
    /**
     * @var callable Error handler 
     * @since 1.0
     */
    public $errorHandler;
    /**
     * @var callable Exception handler 
     * @since 1.0
     */
    public $exceptionHandler;
    /**
     * @var boolean Whether to enable debug mode, default to false 
     * @since 1.0
     */
    public $debug = false;
    /**
     * @var string The default controller name
     * @since 1.0
     */
    public $defaultController = 'default';
    /**
     * @var array Database connection data 
     * @since 1.0
     */
    public $db;
    /**
     * @var string The base path in which all private files are placed 
     * @since 1.0
     */
    private $_protectedPath;

    /**
     * @param string $config
     */
    public function __construct($config)
    {
        Flame::setApplication($this);

        // Protected path should be set almost before everything.
        $this->setProtectedPath('protected');

        // Configure application
        if (is_string($config))
            $config = require($config);
        $this->configure($config);

        $this->initErrorHandlers();
    }

    /**
     * Bootstrap method of the application
     * @return void
     * @since 1.0
     */
    public function run()
    {
        $chain = new FilterChain($this, 'processRequest', array(HttpRequest::getInstance()), $this->filters);
        $chain->run();
    }

    /**
     * Configures this application
     * @param array $config
     * @return void
     * @since 1.0
     */
    public function configure($config)
    {
        if (is_array($config)) {
            foreach ($config as $k=>$v){
                $this->$k = $v;
            }
        }
        Flame::registerNamespaces($this->namespaces);
    }
    
    /**
     * Set the protected path
     * @param string $dirName Name of the protected folder
     * @return void
     * @since 1.0
     */
    public function setProtectedPath($dirName)
    {
        if (($this->_protectedPath = realpath($dirName)) === false || !is_dir($this->_protectedPath))
            throw new FlameException('Protected path ('.$this->_protectedPath.') is not a valid path.');
        $this->_protectedPath = realpath($dirName);
    }

    /**
     * Get the protected path
     * @return string Absolute path of the protected folder
     * @since 1.0
     */
    public function getProtectedPath()
    {
        return $this->_protectedPath;
    }
    
    /**
     * Get the temporary path in which to store runtime files
     * @return string Absolute path of the temporary directory
     * @since 1.0
     */
    public function getTempPath()
    {
        return $this->getProtectedPath().DIRECTORY_SEPARATOR.'temp';
    }

    /**
     * Get controller path
     * @return string The controller path
     * @since 1.0
     */
    public function getControllerPath()
    {
        return $this->getProtectedPath().DIRECTORY_SEPARATOR.'controller';
    }

    /**
     * Get view template path
     * @return string The view template path
     * @since 1.0
     */
    public function getViewPath()
    {
        return $this->getProtectedPath().DIRECTORY_SEPARATOR.'view';
    }
    
    /**
     * Initialize auto-handling for errors and exceptions
     * @return void
     * @since 1.0
     */
    public function initErrorHandlers()
    {
        if ($this->enableErrorHandling == true)
            set_error_handler(array($this, 'handleError'), error_reporting());
        if ($this->enableExceptionHandling == true)
            set_exception_handler(array($this, 'handleException'));
    }
    
    /**
     * Handle errors
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     * @since 1.0
     */
    public function handleError($code, $message, $file, $line)
    {
        // prevent recursive errors
        restore_error_handler();
        restore_exception_handler();

        $msg = "Error $code: $message ($file:$line)";
        Flame::error($msg);

        // let errorHandler() return true to prevent displayError()
        if (is_callable($this->errorHandler) && call_user_func($this->errorHandler, $code, $message, $file, $line) !== true)
            $this->displayError($code, $message, $file, $line);

        exit(1);
    }
    
    /**
     * Handle exceptions
     * @param Exception $exception
     * @return void
     * @since 1.0
     */
    public function handleException($exception)
    {
        // prevent recursive errors
        restore_error_handler();
        restore_exception_handler();

        $msg = get_class($exception).': '.$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine()."\n".$exception->getTraceAsString();
        Flame::error($msg);

        // let exceptionHandler() return true to prevent displayException()
        if (is_callable($this->exceptionHandler) && call_user_func($this->exceptionHandler, $exception) !== true)
            $this->displayException($exception);

        exit(1);
    }
    
    /**
     * Display error information
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     * @since 1.0
     */
    public function displayError($code, $message, $file, $line)
    {
        if ($this->debug == true) {
            echo "<h1>Error $code</h1>";
            echo "<p>$message in ($file:$line)</p>";
            echo '<pre>';
            debug_print_backtrace();
            echo '</pre>';
        } else {
            echo "<h1>Error $code</h1>";
            echo "<p>$message</p>";
        }
    }
    
    /**
     * Display exception information
     * @param Exception $exception
     * @return void
     * @since 1.0
     */
    public function displayException($exception)
    {
        if ($this->debug == true) {
            echo '<h1>'.get_class($exception).'</h1>';
            echo '<p>'.$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine().')</p>';
            echo '<pre>'.$exception->getTraceAsString().'</pre>';
        } else {
            echo '<h1>'.get_class($exception).'</h1>';
            echo '<p>'.$exception->getMessage().'</p>';
        }
    }
    
    /**
     * Get the default controller name
     * @return string Controller name
     * @since 1.0
     */
    public function getDefaultController()
    {
        return $this->defaultController;
    }
    
    /**
     * Process request
     * @param HttpRequest $request
     * @return void
     * @since 1.0
     */
    public function processRequest(HttpRequest $request)
    {
        $controller = $this->createController($request->getController());
        $controller->process($request);
    }
    
    /**
     * Create an instance of the controller
     * @param string $controllerName
     * @return Controller Controller instance
     * @since 1.0
     */
    public function createController($controllerName)
    {
        $className = ucfirst($controllerName).'Controller';
        $classFile = $this->getControllerPath().DIRECTORY_SEPARATOR."$className.php";
        if (file_exists($classFile)) {
            $ns = include_once($classFile);
            $fullClassName = "$ns\\$className";
            if (class_exists($fullClassName)) {
                return new $fullClassName();
            } else {
                throw new HttpException(404, "Request to $controllerName is unresolvable.");
            }
        } else {
            throw new HttpException(404, "Request to $controllerName is unresolvable.");
        }
    }
    
}

?>

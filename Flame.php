<?php
namespace org\x3f\flamework;

/**
 * Flamework entry class, provides some common functions
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class Flame {
    /**
     * @var array Namespaces and their corresponding paths, used for class auto-loading
     * @since 1.0
     */
    public static $_namespaces = array();

    /**
     * Create the application instance
     * @param string $config Configuration file path
     * @return WebApplication
     * @since 1.0
     */
    public static function createApplication($config)
    {
        return new base\WebApplication($config);
    }

    /**
     * Autoload classes
     * @param string $className Class name with namespace
     * @return boolean True for success, false for failure
     * @since 1.0
     */
    public static function autoload($className)
    {
        if (!isset(self::$_namespaces[__NAMESPACE__]))
            self::$_namespaces[__NAMESPACE__] = dirname(__FILE__);
        foreach (self::$_namespaces as $ns=>$path){
            if (strpos($className, $ns) == 0) {
                $classFile = $path.str_replace('\\', DIRECTORY_SEPARATOR, substr($className, strlen($ns))).'.php';
                include($classFile);
                return class_exists($className);
            }
        }
        return false;
    }
    
    /**
     * Register a namespace and its abs path
     * @param string $ns Namespace
     * @param string $path Path of the namespace
     * @return void
     * @since 1.0
     */
    public static function registerNamespace($ns, $path)
    {
        self::$_namespaces[$ns] = $path;
    }
    
    /**
     * Deregister a namespace
     * @param string $ns Namespace
     * @return void
     * @since 1.0
     */
    public static function deregisterNamespace($ns)
    {
        unset(self::$_namespaces[$ns]);
    }
    
    /**
     * Register namespaces
     * @param array $namespaces Namespaces and their paths
     * @return void
     * @since 1.0
     */
    public static function registerNamespaces($namespaces)
    {
        if (is_array($namespaces))
            self::$_namespaces = array_merge(self::$_namespaces, $namespaces);
    }
}

spl_autoload_register(__NAMESPACE__.'\\Flame::autoload');
?>

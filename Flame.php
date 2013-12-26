<?php
/**
 * Flamework class file
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 */
namespace org\x3f\flamework;

class Flame {
    /**
     * Namespaces and their corresponding paths, used for class auto-loading
     **/
    public static $_namespaces = array();

    /**
     * Create the application instance
     * @return WebApplication
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
    public static function createApplication($config)
    {
        return new base\WebApplication($config);
    }

    /**
     * Autoload classes
     * @param string $className Class name with namespace
     * @return boolean True for success, false for failure
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
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
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
    public static function registerNamespace($ns, $path)
    {
        self::$_namespaces[$ns] = $path;
    }
    
    /**
     * Deregister a namespace
     * @param string $ns Namespace
     * @return void
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
    public static function deregisterNamespace($ns)
    {
        unset(self::$_namespaces[$ns]);
    }
    
    /**
     * Register namespaces
     * @param array $namespaces Namespaces and their paths
     * @return void
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
    public static function registerNamespaces($namespaces)
    {
        if (is_array($namespaces))
            self::$_namespaces = array_merge(self::$_namespaces, $namespaces);
    }
}

spl_autoload_register(__NAMESPACE__.'\\Flame::autoload');
?>

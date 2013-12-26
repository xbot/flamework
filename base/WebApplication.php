<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\Flame as Flame;

/**
 * The application abstraction
 * @author Donie Leigh <donie.leigh@gmail.com>
 */
class WebApplication 
{
    /**
     * Namespaces and their corresponding paths, used for class auto-loading
     **/
    public $namespaces;

    public function __construct($config)
    {
        if (is_string($config))
            $config = require($config);
        $this->configure($config);
    }

    /**
     * Bootstrap method of the application
     * @return void
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
    public function run()
    {
        // echo 'i am running';

        // TODO: 获取“请求”组件
        // TODO: 获取要执行的Controller和method
        // TODO: 执行controller->method

        echo $this->name;
    }

    /**
     * Configures this application
     * @return void
     * @author Donie Leigh <donie.leigh@gmail.com>
     **/
    public function configure($config)
    {
        if (is_array($config)) {
            foreach ($config as $k=>$v){
                $this->$k = $v;
            }
        }
        Flame::registerNamespaces($this->namespaces);
    }
}

?>

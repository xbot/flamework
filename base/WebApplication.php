<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\Flame as Flame;

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
     * @param string $config
     */
    public function __construct($config)
    {
        if (is_string($config))
            $config = require($config);
        $this->configure($config);
    }

    /**
     * Bootstrap method of the application
     * @return void
     * @since 1.0
     */
    public function run()
    {
        echo 'i am running';

        // TODO: 获取“请求”组件
        // TODO: 获取要执行的Controller和method
        // TODO: 执行controller->method
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
}

?>

<?php
namespace org\x3f\flamework\base;

/**
 * Filter chain
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class FilterChain
{
    /**
     * @var object Object to be filtered 
     * @since 1.0
     */
    public $obj;
    /**
     * @var string Method of the object to be filtered 
     * @since 1.0
     */
    public $method;
    /**
     * @var array Parameters to be passed to the method 
     * @since 1.0
     */
    public $params;
    /**
     * @var array Filters 
     * @since 1.0
     */
    public $filters;
    /**
     * @var int The offset of filters array
     * @since 1.0
     */
    private $_offset = 0;

    public function __construct($obj, $method, $params, $filters)
    {
        $this->obj = $obj;
        $this->method = $method;
        $this->params = $params;
        $this->filters = $filters;
    }
    
    /**
     * Run this filter and the filter chain
     * @return void
     * @since 1.0
     */
    public function run()
    {
        $filter = $this->nextFilter();
        if ($filter instanceof Filter) {
            $filter->filter($this);
        } else {
            call_user_func_array(array($this->obj, $this->method), $this->params);
        }
    }
    
    /**
     * Get next filter
     * @return Filter Filter instance
     * @since 1.0
     */
    private function nextFilter()
    {
        if ($this->_offset < count($this->filters)) {
            $filter = $this->filters[$this->_offset];
            $this->_offset++;
            return new $filter;
        }
    }
    
}
?>

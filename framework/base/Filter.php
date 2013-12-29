<?php
namespace org\x3f\flamework\base;

/**
 * Ancestor class for all filters
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class Filter
{
    /**
     * Run this filter and the filter chain
     * @param FilterChain $chain
     * @return void
     * @since 1.0
     */
    public function filter(FilterChain $chain)
    {
        if ($this->before($chain)) {
            $chain->run();
            $this->after($chain);
        }
    }
    
    /**
     * The logic to be executed before the aspect point
     * @param FilterChain $chain
     * @return boolean Return true to continue the filter chain, return false to break the chain
     * @since 1.0
     */
    protected function before(FilterChain $chain) {
        return true;
    }

    /**
     * The logic to be executed after the aspect point
     * @param FilterChain $chain
     * @return void
     * @since 1.0
     */
    protected function after(FilterChain $chain) {
    }
}

?>

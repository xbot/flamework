<?php
namespace org\x3f\flamedemo\filter;
use org\x3f\flamework\base\Filter;
use org\x3f\flamework\base\FilterChain;

class ActionFilterC extends Filter
{
    public function before(FilterChain $chain)
    {
        echo __METHOD__."<br>";
        return true;
    }
    
    public function after(FilterChain $chain)
    {
        echo __METHOD__."<br>";
    }
}
?>

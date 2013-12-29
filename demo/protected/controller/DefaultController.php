<?php
namespace org\x3f\flamedemo\controller;
use org\x3f\flamework\base\Controller;
use org\x3f\flamework\base\HttpRequest;

/**
 * Class Defaultcontroller 
 * @author 
 */
class Defaultcontroller extends Controller
{
    protected $filters = array(
        'org\\x3f\\flamedemo\\filter\\ActionFilterC' => '/^(index|noindex)$/',
    );

    public function __construct()
    {
        // code
    }

    public function index($param, $request)
    {
        echo __METHOD__.'<br>';
    }
    
}

return __NAMESPACE__;
?>

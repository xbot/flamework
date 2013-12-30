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

    public function index()
    {
        // echo __METHOD__.'<br>';

        $this->render('post/list', array(
            'name' => 'leigh',
            'age' => 23,
        ));
    }
    
}

return __NAMESPACE__;
?>

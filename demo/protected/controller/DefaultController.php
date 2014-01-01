<?php
namespace org\x3f\flamedemo\controller;
use org\x3f\flamework\Flame;
use org\x3f\flamework\base\Controller;
use org\x3f\flamework\base\HttpRequest;
use org\x3f\flamedemo\model\Post;

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
        $this->render('post/list', array(
            'name' => 'leigh',
            'age' => 23,
        ));

        /*
         * $conn = Flame::app()->getDBConnection();
         * $rows = $conn->rows('select * from test');
         */

        /*
         * $p = Post::getModel()->findByPk(1);
         * var_dump($p);
         */

        /*
         * $p = new Post();
         * $p->setIsNew(false);
         * $p->id = 3;
         * $p->title = 'bad name 2';
         * $p->save();
         */

        /*
         * $p = new Post();
         * $p->setIsNew(false);
         * $p->id = 3;
         * $p->delete();
         */

    }
    
}

return __NAMESPACE__;
?>

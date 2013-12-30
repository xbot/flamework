<?php
namespace org\x3f\flamework\base;
use org\x3f\flamework\Flame;
use org\x3f\flamework\exceptions\FlameException, org\x3f\flamework\exceptions\HttpException;

/**
 * Ancestor class for all controllers
 *
 * @author Donie Leigh <donie.leigh@gmail.com>
 * @link http://0x3f.org
 * @copyright Copyright &copy; 2013-2014 Donie Leigh
 * @license BSD (3-terms)
 * @since 1.0
 */
class Controller 
{
    /**
     * @var string The default action
     * @since 1.0
     */
    protected $defaultAction = 'index';
    /**
     * @var array Filter classnames and rules.
     *            This is an associative array, in which keys are classnames of filters,
     *            and values are regular expressions.
     *            For example:
     *                array(
     *                    'org\\x3f\\flamedemo\filter\\PerformanceFilter' => '/^(save|update|delete)$/',
     *                );
     * @since 1.0
     */
    protected $filters = array();

    /**
     * Process http request
     * @param HttpRequest $request
     * @return void
     * @since 1.0
     */
    public function process(HttpRequest $request)
    {
        $action = $request->getAction() === null ? $this->defaultAction : $request->getAction();
        if (method_exists($this, $action)) {
            // do parameter bindings
            $method = new \ReflectionMethod(get_class($this), $action);
            $params = array();
            foreach ($method->getParameters() as $param){
                if (isset($_REQUEST[$param->getName()])) {
                    $params[] = $_REQUEST[$param->getName()];
                } else if ($param->isDefaultValueAvailable()) {
                    $params[] = $param->getDefaultValue();
                } else {
                    throw new HttpException(400, "Parameter ".$param->getName()." is missing.");
                }
            }
            // create filter chain and run it
            $filters = $this->getActionFilters($action);
            $chain = new FilterChain($this, $action, $params, $filters);
            $chain->run();
        } else {
            $msg = 'Request to '.$request->getController().'/'.$action.' cannot be resolved, action does not exist.';
            throw new HttpException(404, $msg);
        }
    }
    
    /**
     * Get filters for the given action
     * @param string $action
     * @return array Filter names
     * @since 1.0
     */
    public function getActionFilters($action)
    {
        $filters = array();
        foreach ($this->filters as $filterClass=>$regex){
            if (preg_match($regex, $action))
                $filters[] = $filterClass;
        }
        return $filters;
    }
    
    /**
     * Render the view template with data
     * @param string $view View template relative path to base path of the templates
     *                     For example, 'post/list' point to file /srv/http/mysite/protected/view/post/list.php
     * @param array $data Associative array in which data is stored as key-value pairs
     * @return void
     * @since 1.0
     */
    public function render($view, $data)
    {
        extract($data, EXTR_PREFIX_SAME, 'tpl_');
        $viewFile = Flame::app()->getViewPath().DIRECTORY_SEPARATOR.$view.'.php';
        if (is_readable($viewFile)) {
            require($viewFile);
        } else {
            throw new FlameException("View template $view does not exist or cannot be readable.");
        }
    }
    
}

?>

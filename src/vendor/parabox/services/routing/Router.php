<?php declare(strict_types=1);
namespace parabox\services\routing;


use parabox\services\request\RequestBodyInterface;

/**
 * Router
 */
class Router
{
    const VERSION = "0.0.1";

    private $routes = [];
    private $notFound;
    private $request;
    private $basePath = '';
    private $viewPath = '';


    public function __construct(RequestBodyInterface $request, callable $_404 = null, string $basePath = _PBX_BASE_PATH_, string $viewPath = _PBX_VIEWS_)
    {
        if (is_null($request)) {
            throw new Exception("RequestInterface expected. Null received in request parameter.");
        }

        if(is_null($_404)) {
            $this->notFound = function( string $url )
            {
                echo ("404 - ¡$url no se encontro!");
            };

        } else {
            $this->notFound = $_404;
        };


        $this->request = $request;
        $this->basePath = $basePath;
        $this->viewPath = $viewPath;
    }


    public function add( string $url, $action )
    {
        $this->routes[$url] = $action;
        return;
    }


    public function setNotFound( callable $action )
    {
        $this->notFound = $action;
        return;
    }


    public function dispatch()
    {
        foreach ($this->routes as $url => $action)
        {
            if( $url == $_SERVER['REQUEST_URI'] )
            {
                if(is_callable($action))
                {
                    return $action();
                }
                $actionArr = explode('#', $action);
                $controller = $actionArr[0];
                $method = $actionArr[1];

                $instancedController = new $controller( $this->request, $this->basePath, $this->viewPath );
                return $instancedController->$method();
            }
        }
        call_user_func_array($this->notFound, [$_SERVER['REQUEST_URI']]);
    }

}
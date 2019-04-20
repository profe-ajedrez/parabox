<?php declare(strict_types=1);
namespace parabox\controllers;


use parabox\views\View;
use parabox\views\ViewLoader;


abstract class BaseController
{
    const VERSION = "0.1.0";

    protected $view;
    protected $request;
    protected $basePath='';
    protected $viewPath='';

    /**
     * Para usar a futuro. Almacenará el tipo de request, POST, GET, PUT o HEAD
     * @var string
     */
    protected $method = '';


    public function __construct( RequestBodyInterface $request, string $basePath, string $viewPath )
    {
        if (is_null($request)) {
            throw new Exception('RequestBodyInterface parameter expected. Received null.');
        }

        $this->view = new  View(
            new ViewLoader($viewPath)
        );

        $this->request  = $request;
        $this->basePath = $basePath;
        $this->viewPath = $viewPath;
    }


    public function getRequest() : Request_Interface
    {
        return $this->request;
    }

    /**
     * @return View
     */
    public function getView() : View
    {
        return $this->view;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getBasePath() : string
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getViewPath() : string
    {
        return $this->viewPath;
    }

    /**
     * Undocumented function
     *
     * @param string $msg
     * @return array
     */
    public function prepareJSonResponse(string $msg="") : array
    {
        $jsonResponse = [];
        $jsonResponse["success"] = false;
        $jsonResponse["msg"] = $msg;

        return $jsonResponse;
    }

}
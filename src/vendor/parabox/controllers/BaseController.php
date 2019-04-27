<?php /** @noinspection ALL */
declare(strict_types=1);
namespace parabox\controllers;

use Exception;
use parabox\services\request\RequestBodyInterface;
use parabox\views\View;
use parabox\views\ViewLoader;


abstract class BaseController
{
    const VERSION = "0.1.0";

    protected $view;
    protected $request;
    protected $basePath='';
    protected $viewPath='';
    protected $viewFragmentPath='';

    /**
     * Para usar a futuro. Almacenará el tipo de request, POST, GET, PUT o HEAD
     * @var string
     */
    protected $method = '';


    /**
     * BaseController constructor.
     * @param RequestBodyInterface $request
     * @param string $basePath
     * @param string $viewPath
     * @param string $viewFragmentPath
     * @throws Exception
     * @throws Exception
     */
    public function __construct(RequestBodyInterface $request, string $basePath, string $viewPath, string $viewFragmentPath = '' )
    {
        if (is_null($request)) {
            throw new Exception('RequestBodyInterface parameter expected. Received null.');
        }

        $this->view = new  View(
            new ViewLoader($viewPath, $viewFragmentPath)
        );

        $this->request  = $request;
        $this->basePath = $basePath;
        $this->viewPath = $viewPath;
        $this->viewFragmentPath = $viewFragmentPath;
    }


    public abstract function index();

    public function getRequest() : RequestBodyInterface
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
     * prepareJSonResponse
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
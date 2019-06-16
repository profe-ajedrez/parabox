<?php declare(strict_types=1);
namespace parabox\services\request;

class RequestBody implements RequestBodyInterface
{
    const VERSION = "0.0.1";

    protected $post   = [];
    protected $get    = [];
    protected $raw    = "";
    protected $head   = [];
    protected $put    = [];
    protected $server = [];
    protected $method = '';


    /**
     * Request constructor.
     * @param array $post
     */
    public function __construct()
    {
        $this->request_bootstrap();
    }


    /**
     *
     */
    protected function request_bootstrap()
    {
        $this->post   = array_merge([], $_POST);
        $this->get    = array_merge([], $_GET);
        $this->raw    = file_get_contents("php://input");
        $this->server = array_merge([], $_SERVER);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getHead(): array
    {
        return $this->head;
    }

    /**
     * @return array
     */
    public function getPut(): array
    {
        return $this->put;
    }


    public function getBody()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'PUT':
                return [];
                break;
            case 'POST':
                return $this->getPost();
                break;
            case 'GET':
                return $this->getGet();
                break;
            case 'HEAD':
                return [];
                break;
            case 'DELETE':
                return [];
                break;
            default:
                return [
                    'POST' => $this->post,
                    'GET'  => $this->get
                ];
                break;
        }
    }

    public function getRaw()
    {
        return $this->raw;
    }
    
    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }
}

<?php /** @noinspection ALL */
declare(strict_types=1);
namespace parabox\views;


use Exception;

class ViewLoader
{
    const VERSION = "0.1.0";
    private $path ='';
    private $fragmentPath = "";

    public function __construct(string $path, string $fragmentPath = '')
    {
        $this->path         = $path;
        $this->fragmentPath = $fragmentPath;
    }


    /**
     * @param string $viewName
     * @return string
     * @throws Exception
     */
    public function load(string $viewName) : string
    {
        if ( file_exists($this->path . $viewName) ) {
            return file_get_contents($this->path . $viewName);
        }
        if ( file_exists($this->fragmentPath . $viewName)) {
            return file_get_contents(($this->fragmentPath . $viewName));
        }
        throw new Exception("No existe la vista: " . $this->path.$viewName);
    }
}
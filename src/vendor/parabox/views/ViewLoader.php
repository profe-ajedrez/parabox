<?php declare(strict_types=1);
namespace parabox\views;


class ViewLoader
{
    const VERSION = "0.1.0";
    private $path ='';


    public function __construct(string $path)
    {
        $this->path = $path;
    }


    public function load(string $viewName) : string
    {
        if( file_exists($this->path . $viewName) ) {
            return file_get_contents($this->path . $viewName);
        }
        throw new Exception("No existe la vista: " . $this->path.$viewName);
    }
}
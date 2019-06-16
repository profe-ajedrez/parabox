<?php
declare(strict_types=1);
namespace parabox\models;


use Exception;
use parabox\controllers\BaseController;
use parabox\services\connection\ConnectionPg as ConnectionPgAlias;
use parabox\services\dependencies\DependencyContainer;
use parabox\utils\JsonFileReader;


class JsonModel
{
    protected $fileName   = "";
    protected $structure  = [];
    protected $data       = [];
    protected $stringData = "";
    protected $found      = false;
    

    public function __construct($file) 
    {
        $this->fileName = $file;    
    }

    public function unload()
    {
        $this->fileName   = "";
        $this->structure  = [];
        $this->data       = [];
        $this->stringData = "";
    }

    public function load()
    {
        $loader      = new JsonFileReader();
        $this->data  = $loader->jsonLoader($this->fileName, true);
        $this->stringData = json_encode($this->data);
    }


    public function exists($value)
    {
        return array_key_exists($value, $this->data);
    }

    public function get(string $key, $value)
    {
        if (array_key_exists($key, $this->structure)) {
            if (array_key_exists($value, $this->data)) {
                return $this->data[$value];
            }
        }
        return [];
    }


    public function searchByKey($id, $key, $value)
    {
        if ($this->exists($id)) {
            $row = $this->data[$id];
            if (array_key_exists($key, $row)) {
                if ($row[$key] == $value) {
                    return $row;
                }
            }
        }
        return [];
    }

    public function writeById($id, $row)
    {
        $this->data[$id]  = $row;
        $this->stringData = json_encode($this->data, JSON_PRETTY_PRINT);
    }

    public function commit()
    {
        $str = json_encode($this->data,  JSON_PRETTY_PRINT);
        $writter = new JsonFileReader();
        $writter->jsonWriter($this->fileName, $str);
    }


    public function stringify()
    {
        $this->stringData = json_encode($this->data, JSON_PRETTY_PRINT);
        return  $this->stringData;
    }
}
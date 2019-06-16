<?php
declare(strict_types=1);
namespace parabox\models;


use Exception;


class JsonUsers extends JsonModel
{
    public function __construct($file)
    {
        parent::__construct($file);
        $this->structure  = [
            "username" => [
                "field" => "admin", 
                "type"  => "string"
            ],            
            "mail" => [
                "field" => "mail",
                "type"  => "string"
            ],
            "password" => [
                "field" => "password",
                "type"  => "string"
            ]
        ];
    }
}
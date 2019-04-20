<?php
declare(strict_types=1);
namespace parabox\services\connection;


interface ConnectionInterface
{
    public static function open(string $cdn = '') : bool;
    public static function close() : bool;
    public static function query(string $sql, array $params);
}

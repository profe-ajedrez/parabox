<?php
declare(strict_types=1);
namespace parabox\service\connection;


interface ConnectionInterface
{
    public static function open(string $cdn = '') : boolean;
    public static function close() : boolean;
    public static function query(string $sql, array $params);
}

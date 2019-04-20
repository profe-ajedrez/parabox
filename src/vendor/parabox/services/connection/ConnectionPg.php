<?php
declare(strict_types=1);
namespace parabox\services\connection;

/**
 * ConnectionPg
 *
 * Implementado interfaz ConnectionInterface estblece una conexiòn a DB Postgre
 * y provee un medio para realizar consultas a través de pg_query_params.
 * Usa el api pg_ de PHP
 *
 * Created 18-04-2018
 *
 *
 * @author Andrés Reyes a.k.a. Jacobopus
 *
 */
class ConnectionPg implements ConnectionInterface
{
    const VERSION = "0.0.1";

    protected static $conn = null;
    protected static $cdn  = null;


    /**
     * open
     *
     * @throws  Exception
     * @param  string $cdn el string de conexiòn para intentar conectarse a una db postgre. si nulo o vacio arroja exception
     * @return boolean
     */
    public static function open(string $cdn = '') : boolean
    {
        if (self::$conn === null  && self::$cdn && empty($cdn)) {
            throw new Exception("String CDN was expected, nothing found and there is no active connection. fool.");
        }

        if (! empty($cdn))  {
            self::$cdn = $cdn;
        }

        if (self::$conn === null) {
            self::$conn = $pg_connect(self::$cdn);
        }

        return (self::$con !== false);
    }


    /**
     * close
     *
     * Intenta cerrar la conexiòn a postgre
     *
     * @throws Exception
     * @return boolean
     */
    public static function close() : boolean
    {
        if (self::$conn === null) {
            throw new Exception("There is no connection to close. fool.");
        }

        if (pg_close(self::$conn)) {
           self::$con = null;
           return true;
        }

        return false;
    }


    /**
     * query
     *
     * Intenta una consulta de cualquier tipo a BD, es un wrapper para pg_query_params
     *
     * @throws Exception   Si $sql nulo o vacio o si no hay conexiòn establecida (Si no se ha usado antes el mètodo open)
     * @param  string $sql
     * @param  array $params
     * @return void
     */
    public static function query(string $sql, array $params = [])
    {
        if (self::$conn === null) {
            throw new Exception("There is no connection to query. Open one before do this, fool.");
        }

        if (empty($sql)) {
            throw new Exception("String QUERY SQL was expected, empty or null found. Give me a damned string query fool.");
        }

        $result = pg_query_params(self::$conn, $sql, $params);

        return $result;
    }
}
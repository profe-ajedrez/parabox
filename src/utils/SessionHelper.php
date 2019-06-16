<?php
declare(strict_types=1);
namespace parabox\utils;

use JsonException;
use TypeError;
use Exception;

use parabox\models\JsonUsers;


class SessionHelper
{
    /**
     * checkSession
     * 
     * Si existe una sesión abierta, redirige a pagina de perfil.
     */
    public static function checkSession($config)
    {
        $sessionKey = $config["sessionKey"];
        session_start();

        if (array_key_exists($sessionKey, $_SESSION)) {
            $session = $_SESSION[$sessionKey];
            $session = json_decode($session);

            header("Location: " . $config["custom-tags"]["panel"]["value"]);
            die();
        }
    }

    public static function checkUsernamePassword(array $config, $payload)
    {
        $response = [];
        $sessionKey = $config["sessionKey"];
        $users  = new JsonUsers($config["jsonFilesPath"] . "js_users.json");
        $users->load();

        if ($users->exists($payload->username)) {
            $user = $users->get("username", $payload->username);

            if (password_verify($payload->password, $user["password"])) {
                $_SESSION[$sessionKey] = json_encode($user);
                return true;
            }
        }
        return false;
        
    }
}
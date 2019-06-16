<?php
declare(strict_types=1);
namespace app\controllers;
error_reporting(E_ALL);
ini_set('display_errors', '1');


use Exception;
use parabox\controllers\BaseController;
use parabox\models\JsonUsers;
use parabox\utils\SessionHelper;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoginController extends BaseController
{

    public function index()
    {
        $now = date("Y-m-dd");

        $logger = new Logger("login-channel");
        $logger->pushHandler(new StreamHandler($this->config["logFilesPath"] . 'log_' . $now . 'log', Logger::DEBUG));

        try{
            SessionHelper::checkSession($this->config);

            $post = $this->request->getPost();
            $logger->info(json_encode($post));

            $response = $this->prepareJSonResponse();
            $response["msg"]     = json_last_error_msg();

            $payload = (array_key_exists("body", $post) ? json_decode($post["body"]) : json_decode("{\"username\" : \"admin\", \"mail\": \"cc@cc.cl\", \"password\":\"$2y$10$X5U53m.l43\/ocSXoqJ5KUeea8EK58LhK2ENsRDHhnN6eJb553s9ZO\"}"));

            if (empty($payload)) {
                $response["msg"] = "Credenciales invalidas";
            } elseif (empty($payload->password)) {
                $response["msg"] = "Password invalida";
            } elseif (!empty($payload->username)) {

                $users  = new JsonUsers($this->config["jsonFilesPath"] . "js_users.json");
                $users->load();

                if ($users->exists($payload->username)) {
                    $user = $users->get("username", $payload->username);
                    $logger->info("user hash: " . $user["password"]);

                    if (password_verify($payload->password, $user["password"])) {
                        $_SESSION[$sessionKey] = json_encode($user);
                        $response["success"]   = true;
                        
                        
                        
                        //$cc = new ControPanel($this->request, $basePath, string $viewPath, string $viewFragmentPath = '', array $config)
                        //header("Location: " . $this->config["custom-tags"]["panel"]["value"]);
                        //die();
                    }

                    $response["msg"] = "Combinación no encontrada de usuario y contraseña";
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            die;
        } catch(Exception $e) {
            $logger->error($e->getMessage());
        }
    }

}

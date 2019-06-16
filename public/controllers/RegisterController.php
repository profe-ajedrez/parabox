<?php
declare(strict_types=1);
namespace app\controllers;

use Exception;
use parabox\controllers\BaseController;
use parabox\utils\JsonFileReader;
use parabox\models\JsonUsers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class RegisterController extends BaseController
{

    public function index()
    {
        $this->view->addFragment("first_fragment.html");
        $this->view->addFragment("meta.html");
        $this->view->addFragment("shared_css.html");
        $this->view->addFragment("closing_head.html");

        $this->view->addFragment("register/register.html");

        $this->view->addFragment("shared_js.html");
        $this->view->addFragment("ending_tags.html");

        $strView = $this->view->render();
        $strView = $this->replaceCustomTags($strView);

        $this->serve($strView);
    }


    public function process() 
    {

        //$payload = json_decode($this->request->getRaw());
        $now = date("Y-m-dd");

        $logger = new Logger("register-channel");
        $logger->pushHandler(new StreamHandler($this->config["logFilesPath"] . 'log_' . $now . 'log', Logger::DEBUG));
        
        $post    = $this->request->getPost();

        $payload = (array_key_exists("body", $post) ? json_decode($post["body"]) : json_decode("{\"username\" : \"\", \"mail\": \"\", \"password\":\"\"}"));
        $logger->info("\$payload = " . json_encode($payload));

        $result  = json_last_error();
        $logger->info("\$last json error = " . $result);

        $response = $this->prepareJSonResponse();
        $response["msg"]     = json_last_error_msg();
                
        if ($result == JSON_ERROR_NONE) {
            if (empty($payload->username)) { 
                $response["msg"] = "Nombre de usuario no puede estar vacio";
            } elseif (empty($payload->mail)) {
                $response["msg"] = "Mail no puede estar vacio";
            } elseif (! filter_var($payload->mail, FILTER_VALIDATE_EMAIL)) {
                $response["msg"] = "Mail debe ser válido";                
            } elseif (empty($payload->password)) {
                $response["msg"] = "password no puede estar vacio";
            } elseif (strlen($payload->password) < 6) {
                $response["msg"] = "Debes usar a menos 6 caracteres para password";
            } else {

                $logger->info("iniciando carga json");                
                    
                $users  = new JsonUsers($this->config["jsonFilesPath"] . "js_users.json");
                $users->load();        

                if ($users->exists($payload->username)) {
                    $response["success"] = false;
                    $response["msg"]     = "Nombre de Usuario ya existe"; 
                    $logger->info("Intento de registrar usuario existente. " . $payload->username);
                } elseif (! empty($users->searchByKey($payload->username, "mail", $payload->mail))) {
                    $response["success"] = false;
                    $response["msg"]     = "Correo de Usuario ya existe"; 
                    $logger->info("Intento de registrar correo existente. " . $payload->mail);
                } else {
                    $payload->password = password_hash ($payload->password, PASSWORD_DEFAULT);
                    $newUser = json_decode(json_encode($payload), true);                        
                    $users->writeById($payload->username, $newUser);
                    $users->commit();

                    $response["success"] = true;
                    $logger->info("¡Nuevo usuario registrado! " . $payload->username);
                }
               
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        die;
    }
}
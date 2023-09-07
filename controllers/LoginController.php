<?php 

namespace Controllers;

use MVC\Router;
use Model\Admin;

class LoginController {
    public static function login( Router $router) {

        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1.- Se valida que se ingrese un email y password
            $auth = new Admin($_POST);
            $errores = $auth->validar();
        
            if(empty($errores)) {
                // 2.- Se valida que el usuario exista
                $resultado = $auth->existeUsuario();
     
                
                if( !$resultado ) {
                    $errores = Admin::getErrores();
                } else {

                    $auth->comprobarPassword($resultado);

                    if($auth) {
                        // 3.- Verifica que el password coinicda
                       $auth->autenticar();
                    } else {
                        $errores =Admin::getErrores();
                    }
                }
            }
        }

        $router->render('auth/login', [
            'errores' => $errores
        ]); 
    }

    public static function logout() {
        session_start();
        // $_SESSION contains session data
        $_SESSION = []; // Elimina la informacion contenida en el arreglo para cerrar la sesion
        header('Location: /');
    }
}
<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index( Router $router ) {

        $propiedades = Propiedad::get(3);

        $router->render('paginas/index', [
            'inicio' => true,
            'propiedades' => $propiedades
        ]);
    }

    public static function nosotros( Router $router ) {
        $router->render('paginas/nosotros', [

        ]);
    }

    public static function propiedades( Router $router ) {

        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {
        $id = validarORedireccionar('/propiedades');

        // Obtener los datos de la propiedad
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog( Router $router ) {

        $router->render('paginas/blog');
    }

    public static function entrada( Router $router ) {
        $router->render('paginas/entrada');
    }


    public static function contacto( Router $router ) {
        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validar 
            $respuestas = $_POST['contacto'];
        
            // Crea un objeto de PHPMailer
            $mail = new PHPMailer();
            // Configuracion de SMTP
            $mail->isSMTP(); // Simple Mail Transfer Protocol
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '40687e58f2cec1';
            $mail->Password = '03fe1da692e7d3';
            $mail->SMTPSecure = 'tls'; // Transport Layer Security
            $mail->Port = 2525;
        
            $mail->setFrom('admin@bienesraices.com', $respuestas['nombre']); // setFrom() Quien envia el correo
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com'); // addAdress() Quien recibe el correo 
            $mail->Subject = 'Tienes un Nuevo Email'; // Subject() Asunto del correo
            // Habilita el HTML 
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8'; // Charset() Codificacion de caracteres UTF-8

            $contenido = '<html>';
            $contenido .= "<p><strong>Has Recibido un email:</strong></p>";
            $contenido .= "<p>Nombre: " . $respuestas['nombre'] . "</p>";
            $contenido .= "<p>Mensaje: " . $respuestas['mensaje'] . "</p>";
            $contenido .= "<p>Vende o Compra: " . $respuestas['opciones'] . "</p>";
            $contenido .= "<p>Presupuesto o Precio: $" . $respuestas['presupuesto'] . "</p>";

            if($respuestas['contacto'] === 'telefono') {
                $contenido .= "<p>Eligió ser Contactado por Teléfono:</p>";
                $contenido .= "<p>Su teléfono es: " .  $respuestas['telefono'] ." </p>";
                $contenido .= "<p>En la Fecha y hora: " . $respuestas['fecha'] . " - " . $respuestas['hora']  . " Horas</p>";
            } else {
                $contenido .= "<p>Eligio ser Contactado por Email:</p>";
                $contenido .= "<p>Su Email  es: " .  $respuestas['email'] ." </p>";
            }

            $contenido .= '</html>';
            $mail->Body = $contenido; // Body() Contenido del correo
            $mail->AltBody = 'Esto es texto alternativo'; // AltBody() Texto plano para clientes de correo que no soportan HTML

            // Envia el email
            if(!$mail->send()){ // returns a boolean
                $mensaje = 'Hubo un Error... intente de nuevo';
            } else {
                $mensaje = 'Email enviado Correctamente';
            }

        }
        
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}
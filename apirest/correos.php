<?php

require_once 'config.php';
require_once "clases/conexion_clase.php";
require_once 'clases/respuestas_clase.php';
require_once './../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$_conexion = new Conexion;
$_respuestas = new respuestas;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    try {
        $datos = json_decode($postBody, true);
        if (!isset($datos['funcion'])) {
            $respuesta = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado el dato obligatorio funcion");
            $response = json_encode($respuesta);
            http_response_code(400);
            echo $response;
        } else {
            if ($datos['funcion'] == 'recordar') {
                if (!isset($datos['correo']) && !isset($datos['idioma'])) {
                    $respuesta = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado el dato obligatorio funcion");
                    $response = json_encode($respuesta);
                    http_response_code(400);
                    echo $response;
                } else {
                    $correo = $datos['correo'];
                    $idioma = $datos['idioma'];
                    $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
                    $result = $_conexion->datos($query);
                    if (!$result->num_rows) {
                        if ($idioma == 'es') {
                            $respuesta = $_respuestas->okF('Error: El correo no existe en nuestra base de datos');
                        } else {
                            $respuesta = $_respuestas->okF('Error: The email does not exist in our database');
                        }
                        $response = json_encode($respuesta);
                        http_response_code(200);
                        echo $response;
                    } else {
                        $row = mysqli_fetch_assoc($result);
                        $nombre = $row['nombre'] . ' ' . $row['apellido'];
                        $usuario = $row['usuario'];
                        $id = $row['id'];
                        $cod = $row['cod'];
                        try {
                            $mail = new PHPMailer(true);
                            $mail->isSMTP();
                            $mail->Host = 'smtp.hostinger.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'emails@soportedosxdos.app';
                            $mail->Password = 'Abfe04**';
                            $mail->SMTPSecure = 'tls';
                            $mail->Port = 587;
                            $mail->setFrom('emails@soportedosxdos.app', 'DOS.DOS');
                            $mail->addAddress($correo, $nombre);
                            if ($idioma == 'es') {
                                $mail->Subject = 'CREDENCIALES DE INGRESO';
                            } else {
                                $mail->Subject = 'LOGIN CREDENTIALS - DOS.DOS';
                            }
                            $mail->isHTML(true);
                            $textoEspanol = 'Saludos ' . $nombre . '. ' . 'Te informamos que tu usuario para autenticarte en nuestra aplicación y el link para restablecer tu contraseña son: ';
                            $textoEspanolHtml = htmlentities($textoEspanol);
                            $textoEnglish = 'Greetings ' . $nombre . '. ' . 'We inform you that your user to authenticate in our application and the link to reset your password are: ';
                            $textoEnglishlHtml = htmlentities($textoEnglish);
                            $clave = '1987082120200804';
                            $link = 'https://dosxdos.app.iidos.com/restablecer_contrasena.php?id=' .  $id . '&usuario=' . $usuario . '&cod=' . $cod . '&clave=' . $clave;
                            if ($idioma == 'es') {
                                $message = '<p style="font-size: 20px; color: black; text-align: center;">' . $textoEspanolHtml . '</p><br/><p style="font-size: 20px; color: black; text-align: center;"><b>' . $usuario . '</b></p><p style="font-size: 20px; color: blue; text-decoration: underline; text-align: center;"><a href="' . $link . '">RESTABLECER TU CONTRASE&Ntilde;A</a></p><br/><div style="display: flex; width: 100%; justify-content: center; align-items: center; text-align: center;"><a href="https://dosxdos.app.iidos.com" style="display: flex; width: 100%; justify-content: center; align-items: center; text-align: center;"><img src="https://dosxdos.app.iidos.com/img/logo2930_original.png" style="width: 260px;"></a></div>';
                            } else {
                                $message = '<p style="font-size: 20px; color: black; text-align: center;">' . $textoEnglishlHtml . '</p><br/><p style="font-size: 20px; color: black; text-align: center;"><b>' . $usuario . '</b></p><p style="font-size: 20px; color: blue; text-decoration: underline; text-align: center;"><a href="' . $link . '">RESET YOUR PASSWORD</a></p><br/><div style="display: flex; width: 100%; justify-content: center; align-items: center; text-align: center;"><a href="https://dosxdos.app.iidos.com" style="display: flex; width: 100%; justify-content: center; align-items: center; text-align: center;"><img src="https://dosxdos.app.iidos.com/img/logo2930_original.png" style="width: 260px;"></a></div>';
                            }
                            $mail->Body = $message;
                            /* ADJUNTOS
                            // Ruta del archivo adjunto
                            $adjunto = 'ruta/al/archivo.pdf';
                            // Añade el archivo adjunto
                            $mail->addAttachment($adjunto);
                            $mail->addAttachment('ruta/al/archivo1.pdf');
                            $mail->addAttachment('ruta/al/archivo2.jpg');
                            // ... añadir más archivos adjuntos según sea necesario*/
                            // Enviar el correo
                            if ($mail->send()) {
                                if ($idioma == 'es') {
                                    $respuesta = $_respuestas->ok('Correo enviado exitosamente');
                                } else {
                                    $respuesta = $_respuestas->ok('Email sent successfully');
                                }
                                $response = json_encode($respuesta);
                                http_response_code(200);
                                echo $response;
                            } else {
                                if ($idioma == 'es') {
                                    $respuesta = $_respuestas->error_500("Error al enviar el correo");
                                } else {
                                    $respuesta = $_respuestas->error_500("Error sending email");
                                }
                                $response = json_encode($respuesta);
                                http_response_code(500);
                                echo $response;
                            }
                        } catch (Exception $e) {
                            if ($idioma == 'es') {
                                $respuesta = $_respuestas->error_500('Error al enviar el correo: ' . $mail->ErrorInfo);
                            } else {
                                $respuesta = $_respuestas->error_500('Error sending email: ' . $mail->ErrorInfo);
                            }
                            $response = json_encode($respuesta);
                            http_response_code(500);
                            echo $response;
                        }
                    }
                }
            } else {
                $respuesta = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un valor correcto en el dato obligatorio funcion");
                $response = json_encode($respuesta);
                http_response_code(400);
                echo $response;
            }
        }
    } catch (Exception $e) {
        $respuesta = $_respuestas->error_500('500 - Error al enviar el correo: ' . $e->getMessage());
        $response = json_encode($respuesta);
        http_response_code(500);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

<?php

require_once 'config.php';
require_once 'clases/lineas_clase.php';

$_respuestas = new Respuestas;
$_lineas = new Lineas;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ruta"]) && isset($_GET["linea"])) {
        $numeroRuta = $_GET['ruta'];
        $numeroLinea = $_GET['linea'];
        $_lineas->linea($numeroRuta, $numeroLinea);
        if ($_lineas->error) {
            $cod = $_lineas->error[2];
            http_response_code($cod);
            $response = json_encode($_lineas->error);
            echo $response;
        } else {
            $cod = $_lineas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_lineas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ruta"])) {
        $numeroRuta = $_GET['ruta'];
        $_lineas->lineas($numeroRuta);
        if ($_lineas->error) {
            $cod = $_lineas->error[2];
            http_response_code($cod);
            $response = json_encode($_lineas->error);
            echo $response;
        } else {
            $cod = $_lineas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_lineas->respuesta);
            echo $response;
        }
    } else {
        $respuesta = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
        $response = json_encode($respuesta);
        http_response_code(400);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $_lineas->put($postBody);
    if ($_lineas->error) {
        $cod = $_lineas->error[2];
        http_response_code($cod);
        $response = json_encode($_lineas->error);
        echo $response;
    } else {
        $cod = $_lineas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_lineas->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}
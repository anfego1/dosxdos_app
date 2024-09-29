<?php

require_once 'config.php';
require_once 'clases/lineas_app_clase.php';

$_respuestas = new Respuestas;
$_lineasApp = new LineasApp;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ot"]) && isset($_GET["lineaOt"])) {
        $ot = $_GET['ot'];
        $numeroLinea = $_GET['lineaOt'];
        $_lineasApp->lineaApp($ot, $numeroLinea);
        if ($_lineasApp->error) {
            $cod = $_lineasApp->error[2];
            http_response_code($cod);
            $response = json_encode($_lineasApp->error);
            echo $response;
        } else {
            $cod = $_lineasApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_lineasApp->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ot"])) {
        $ot = $_GET['ot'];
        $_lineasApp->lineasApp($ot);
        if ($_lineasApp->error) {
            $cod = $_lineasApp->error[2];
            http_response_code($cod);
            $response = json_encode($_lineasApp->error);
            echo $response;
        } else {
            $cod = $_lineasApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_lineasApp->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ano"])) {
        $ano = $_GET['ano'];
        $_lineasApp->lineasAppTotal($ano);
        if ($_lineasApp->error) {
            $cod = $_lineasApp->error[2];
            http_response_code($cod);
            $response = json_encode($_lineasApp->error);
            echo $response;
        } else {
            $cod = $_lineasApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_lineasApp->respuesta);
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
    $_lineasApp->put($postBody);
    if ($_lineasApp->error) {
        $cod = $_lineasApp->error[2];
        http_response_code($cod);
        $response = json_encode($_lineasApp->error);
        echo $response;
    } else {
        $cod = $_lineasApp->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_lineasApp->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

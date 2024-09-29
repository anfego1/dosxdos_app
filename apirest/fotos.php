<?php

require_once 'config.php';
require_once 'clases/respuestas_clase.php';
require_once 'clases/fotos_clase.php';

$_respuestas = new Respuestas;
$_fotos = new Fotos;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $_fotos->post($postBody);
    if ($_fotos->error) {
        $cod = $_fotos->error[2];
        http_response_code($cod);
        $response = json_encode($_fotos->error);
        echo $response;
    } else {
        $cod = $_fotos->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_fotos->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ruta"]) && isset($_GET["linea"]) && isset($_GET["ot"]) && isset($_GET["lineaOt"])) {
        $ruta = $_GET['ruta'];
        $linea = $_GET['linea'];
        $ot = $_GET['ot'];
        $lineaOt = $_GET['lineaOt'];
        $_fotos->fotos($ruta, $linea, $ot, $lineaOt);
        if ($_fotos->error) {
            $cod = $_fotos->error[2];
            http_response_code($cod);
            $response = json_encode($_fotos->error);
            echo $response;
        } else {
            $cod = $_fotos->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_fotos->respuesta);
            echo $response;
        }
    } else if (isset($_GET["otCompleta"]) && isset($_GET["ot"])) {
        $ot = $_GET['ot'];
        $_fotos->fotosOt($ot);
        if ($_fotos->error) {
            $cod = $_fotos->error[2];
            http_response_code($cod);
            $response = json_encode($_fotos->error);
            echo $response;
        } else {
            $cod = $_fotos->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_fotos->respuesta);
            echo $response;
        }
    } else {
        $respuesta = $_respuestas->error_400("Error: No has brindado todas las variables necesarias");
        $response = json_encode($respuesta);
        http_response_code(400);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $_fotos->delete($postBody);
    if ($_fotos->error) {
        $cod = $_fotos->error[2];
        http_response_code($cod);
        $response = json_encode($_fotos->error);
        echo $response;
    } else {
        $cod = $_fotos->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_fotos->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $_fotos->visible($postBody);
    if ($_fotos->error) {
        $cod = $_fotos->error[2];
        http_response_code($cod);
        $response = json_encode($_fotos->error);
        echo $response;
    } else {
        $cod = $_fotos->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_fotos->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}
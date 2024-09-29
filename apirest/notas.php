<?php

require_once 'config.php';
require_once 'clases/notas_clase.php';

$_respuestas = new Respuestas;
$_notas = new Notas;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ot"]) && isset($_GET["linea"])) {
        $ot = $_GET['ot'];
        $linea = $_GET['linea'];
        $_notas->nota($ot, $linea);
        if ($_notas->error) {
            $cod = $_notas->error[2];
            http_response_code($cod);
            $response = json_encode($_notas->error);
            echo $response;
        } else {
            $cod = $_notas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_notas->respuesta);
            echo $response;
        }
    } else {
        $respuesta = $_respuestas->error_400();
        $response = json_encode($respuesta);
        http_response_code(400);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $json = json_decode($postBody, true);
    if (isset($json['ot']) && isset($json['linea']) && isset($json['nota'])) {
        $ot = $json['ot'];
        $linea = $json['linea'];
        $nota = $json['nota'];
        $_notas->addNota($ot, $linea, $nota);
        if ($_notas->error) {
            $cod = $_notas->error[2];
            http_response_code($cod);
            $response = json_encode($_notas->error);
            echo $response;
        } else {
            $cod = $_notas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_notas->respuesta);
            echo $response;
        }
    } else {
        $respuesta = $_respuestas->error_400();
        $response = json_encode($respuesta);
        http_response_code(400);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

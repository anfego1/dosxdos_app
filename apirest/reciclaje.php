<?php

require_once 'config.php';
require_once 'clases/respuestas_clase.php';
require_once 'clases/reciclaje_clase.php';

$_respuestas = new Respuestas;
$_reciclaje = new Reciclaje;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $_reciclaje->archivosReciclados();
    if ($_reciclaje->error) {
        $cod = $_reciclaje->error[2];
        http_response_code($cod);
        $response = json_encode($_reciclaje->error);
        echo $response;
    } else {
        $cod = $_reciclaje->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_reciclaje->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $_reciclaje->delete($postBody);
    if ($_reciclaje->error) {
        $cod = $_reciclaje->error[2];
        http_response_code($cod);
        $response = json_encode($_reciclaje->error);
        echo $response;
    } else {
        $cod = $_reciclaje->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_reciclaje->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $_reciclaje->restaurar($postBody);
    if ($_reciclaje->error) {
        $cod = $_reciclaje->error[2];
        http_response_code($cod);
        $response = json_encode($_reciclaje->error);
        echo $response;
    } else {
        $cod = $_reciclaje->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_reciclaje->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}
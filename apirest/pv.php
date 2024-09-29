<?php

require_once 'config.php';
require_once 'clases/pv_clase.php';

$_respuestas = new Respuestas;
$pv = new Pv;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["no"])) {
        $no = $_GET['no'];
        $pv->puntoDeVenta($no);
        if ($pv->error) {
            $cod = $pv->error[2];
            http_response_code($cod);
            $response = json_encode($pv->error);
            echo $response;
        } else {
            $cod = $pv->respuesta[2];
            http_response_code($cod);
            $response = json_encode($pv->respuesta);
            echo $response;
        }
    } else {
        $pv->puntosDeVenta();
        if ($pv->error) {
            $cod = $pv->error[2];
            http_response_code($cod);
            $response = json_encode($pv->error);
            echo $response;
        } else {
            $cod = $pv->respuesta[2];
            http_response_code($cod);
            $response = json_encode($pv->respuesta);
            echo $response;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $pv->put($postBody);
    if ($pv->error) {
        $cod = $pv->error[2];
        http_response_code($cod);
        $response = json_encode($pv->error);
        echo $response;
    } else {
        $cod = $pv->respuesta[2];
        http_response_code($cod);
        $response = json_encode($pv->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $pv->post($postBody);
    if ($pv->error) {
        $cod = $pv->error[2];
        http_response_code($cod);
        $response = json_encode($pv->error);
        echo $response;
    } else {
        $cod = $pv->respuesta[2];
        http_response_code($cod);
        $response = json_encode($pv->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $pv->delete($postBody);
    if ($pv->error) {
        $cod = $pv->error[2];
        http_response_code($cod);
        $response = json_encode($pv->error);
        echo $response;
    } else {
        $cod = $pv->respuesta[2];
        http_response_code($cod);
        $response = json_encode($pv->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

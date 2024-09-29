<?php

require_once 'config.php';
require_once 'clases/respuestas_clase.php';
require_once 'clases/firmas_clase.php';

$_respuestas = new Respuestas;
$_firmas = new Firmas;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $_firmas->post($postBody);
    if ($_firmas->error) {
        $cod = $_firmas->error[2];
        http_response_code($cod);
        $response = json_encode($_firmas->error);
        echo $response;
    } else {
        $cod = $_firmas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_firmas->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ruta"]) && isset($_GET["linea"]) && isset($_GET["ot"]) && isset($_GET["lineaOt"])) {
        $ruta = $_GET['ruta'];
        $linea = $_GET['linea'];
        $ot = $_GET['ot'];
        $lineaOt = $_GET['lineaOt'];
        $_firmas->firmas($ruta, $linea, $ot, $lineaOt);
        if ($_firmas->error) {
            $cod = $_firmas->error[2];
            http_response_code($cod);
            $response = json_encode($_firmas->error);
            echo $response;
        } else {
            $cod = $_firmas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_firmas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["otCompleta"]) && isset($_GET["ot"])) {
        $ot = $_GET['ot'];
        $_firmas->firmasOt($ot);
        if ($_firmas->error) {
            $cod = $_firmas->error[2];
            http_response_code($cod);
            $response = json_encode($_firmas->error);
            echo $response;
        } else {
            $cod = $_firmas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_firmas->respuesta);
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
    $_firmas->delete($postBody);
    if ($_firmas->error) {
        $cod = $_firmas->error[2];
        http_response_code($cod);
        $response = json_encode($_firmas->error);
        echo $response;
    } else {
        $cod = $_firmas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_firmas->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $_firmas->visible($postBody);
    if ($_firmas->error) {
        $cod = $_firmas->error[2];
        http_response_code($cod);
        $response = json_encode($_firmas->error);
        echo $response;
    } else {
        $cod = $_firmas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_firmas->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}
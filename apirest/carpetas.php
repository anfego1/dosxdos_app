<?php

require_once 'config.php';
require_once 'clases/respuestas_clase.php';
require_once 'clases/carpetas_clase.php';

$_respuestas = new Respuestas;
$_carpetas = new Carpetas;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $_carpetas->post($postBody);
    if ($_carpetas->error) {
        $cod = $_carpetas->error[2];
        http_response_code($cod);
        $response = json_encode($_carpetas->error);
        echo $response;
    } else {
        $cod = $_carpetas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_carpetas->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ruta"]) && isset($_GET["linea"]) && isset($_GET["ot"]) && isset($_GET["lineaOt"])) {
        $ruta = $_GET['ruta'];
        $linea = $_GET['linea'];
        $ot = $_GET['ot'];
        $lineaOt = $_GET['lineaOt'];
        $_carpetas->carpetas($ruta, $linea, $ot, $lineaOt);
        if ($_carpetas->error) {
            $cod = $_carpetas->error[2];
            http_response_code($cod);
            $response = json_encode($_carpetas->error);
            echo $response;
        } else {
            $cod = $_carpetas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_carpetas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["otCompleta"]) && isset($_GET["ot"])) {
        $ot = $_GET['ot'];
        $_carpetas->carpetasOt($ot);
        if ($_carpetas->error) {
            $cod = $_carpetas->error[2];
            http_response_code($cod);
            $response = json_encode($_carpetas->error);
            echo $response;
        } else {
            $cod = $_carpetas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_carpetas->respuesta);
            echo $response;
        }
    } else {
        $respuesta = $_respuestas->error_400("Error: es necesario especificar la ruta y la línea de ruta");
        $response = json_encode($respuesta);
        http_response_code(400);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $_carpetas->delete($postBody);
    if ($_carpetas->error) {
        $cod = $_carpetas->error[2];
        http_response_code($cod);
        $response = json_encode($_carpetas->error);
        echo $response;
    } else {
        $cod = $_carpetas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_carpetas->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $_carpetas->visible($postBody);
    if ($_carpetas->error) {
        $cod = $_carpetas->error[2];
        http_response_code($cod);
        $response = json_encode($_carpetas->error);
        echo $response;
    } else {
        $cod = $_carpetas->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_carpetas->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}
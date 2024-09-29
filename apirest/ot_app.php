<?php

require_once 'config.php';
require_once 'clases/ot_app_clase.php';

$_respuestas = new Respuestas;
$_otApp = new otApp;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ot"])) {
        $ot = $_GET['ot'];
        $_otApp->otApp($ot);
        if ($_otApp->error) {
            $cod = $_otApp->error[2];
            http_response_code($cod);
            $response = json_encode($_otApp->error);
            echo $response;
        } else {
            $cod = $_otApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_otApp->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ano"])) {
        $ano = $_GET['ano'];
        $_otApp->otAppAno($ano);
        if ($_otApp->error) {
            $cod = $_otApp->error[2];
            http_response_code($cod);
            $response = json_encode($_otApp->error);
            echo $response;
        } else {
            $cod = $_otApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_otApp->respuesta);
            echo $response;
        }
    } else if (isset($_GET["anos"])) {
        $_otApp->otAnos();
        if ($_otApp->error) {
            $cod = $_otApp->error[2];
            http_response_code($cod);
            $response = json_encode($_otApp->error);
            echo $response;
        } else {
            $cod = $_otApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_otApp->respuesta);
            echo $response;
        }
    } else if (isset($_GET["tipos"])) {
        $_otApp->otTipos();
        if ($_otApp->error) {
            $cod = $_otApp->error[2];
            http_response_code($cod);
            $response = json_encode($_otApp->error);
            echo $response;
        } else {
            $cod = $_otApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_otApp->respuesta);
            echo $response;
        }
    } else {
        $_otApp->otAppTotal();
        if ($_otApp->error) {
            $cod = $_otApp->error[2];
            http_response_code($cod);
            $response = json_encode($_otApp->error);
            echo $response;
        } else {
            $cod = $_otApp->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_otApp->respuesta);
            echo $response;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $_otApp->put($postBody);
    if ($_otApp->error) {
        $cod = $_otApp->error[2];
        http_response_code($cod);
        $response = json_encode($_otApp->error);
        echo $response;
    } else {
        $cod = $_otApp->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_otApp->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

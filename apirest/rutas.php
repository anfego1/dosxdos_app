<?php

require_once 'config.php';
require_once 'clases/rutas_clase.php';

$_respuestas = new Respuestas;
$_rutas = new Rutas;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["ruta"])) {
        $numeroRuta = $_GET['ruta'];
        $_rutas->ruta($numeroRuta);
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["activas"])) {
        $_rutas->rutasActivas();
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["inactivas"])) {
        $_rutas->rutasInactivas();
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["montador"])) {
        $montador = $_GET["montador"];
        $_rutas->montador($montador);
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["montadores"])) {
        $ruta = $_GET["montadores"];
        $_rutas->montadores($ruta);
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    } else if (isset($_GET["totalMontadores"])) {
        $_rutas->totalMontadores();
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    } else {
        $_rutas->rutas();
        if ($_rutas->error) {
            $cod = $_rutas->error[2];
            http_response_code($cod);
            $response = json_encode($_rutas->error);
            echo $response;
        } else {
            $cod = $_rutas->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_rutas->respuesta);
            echo $response;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $respuesta = $_respuestas->error_405('405 - El método POST no está permitido');
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $respuesta = $_respuestas->error_405('405 - El método PUT no está permitido');
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $respuesta = $_respuestas->error_405('405 - El método DELETE no está permitido');
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}
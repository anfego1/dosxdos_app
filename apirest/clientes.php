<?php

require_once 'config.php';
require_once 'clases/clientes_clase.php';

$_respuestas = new Respuestas;
$_clientes = new Clientes;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["idCliente"]) && isset($_GET["restricciones"])) {
        $idCliente = $_GET['idCliente'];
        $_clientes->restGet($idCliente);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["idCliente"]) && isset($_GET["tiposOt"])) {
        $idCliente = $_GET['idCliente'];
        $_clientes->tiposOt($idCliente);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["codCliente"]) && isset($_GET["firmasCliente"])) {
        $codCliente = $_GET['codCliente'];
        $_clientes->firmasCliente($codCliente);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["idCliente"]) && isset($_GET["restriccionesFirmas"])) {
        $idCliente = $_GET['idCliente'];
        $_clientes->restGetFirmas($idCliente);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["idCliente"]) && isset($_GET["codCliente"]) && isset($_GET["codOt"]) && isset($_GET["clientesOts"])) {
        $idCliente = $_GET['idCliente'];
        $codCliente = $_GET['codCliente'];
        $codOt = $_GET['codOt'];
        $_clientes->clientesOts($idCliente, $codCliente, $codOt);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ot"]) && isset($_GET["lineasDeOt"])) {
        $ot = $_GET['ot'];
        $_clientes->lineasApp($ot);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ot"]) && isset($_GET["lineaOt"]) && isset($_GET["fotos"])) {
        $ot = $_GET['ot'];
        $lineaOt = $_GET['lineaOt'];
        $_clientes->fotos($ot, $lineaOt);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ot"]) && isset($_GET["lineaOt"]) && isset($_GET["firmas"])) {
        $ot = $_GET['ot'];
        $lineaOt = $_GET['lineaOt'];
        $_clientes->firmas($ot, $lineaOt);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ot"]) && isset($_GET["lineaOt"]) && isset($_GET["carpetas"])) {
        $ot = $_GET['ot'];
        $lineaOt = $_GET['lineaOt'];
        $_clientes->carpetas($ot, $lineaOt);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else if (isset($_GET["ot"]) && isset($_GET["carpetasOt"])) {
        $ot = $_GET['ot'];
        $_clientes->carpetasOt($ot);
        if ($_clientes->error) {
            $cod = $_clientes->error[2];
            http_response_code($cod);
            $response = json_encode($_clientes->error);
            echo $response;
        } else {
            $cod = $_clientes->respuesta[2];
            http_response_code($cod);
            $response = json_encode($_clientes->respuesta);
            echo $response;
        }
    } else {
        $answer = $_respuestas->error_400();
        $cod = 400;
        http_response_code($cod);
        $response = json_encode($answer);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $_clientes->post($postBody);
    if ($_clientes->error) {
        $cod = $_clientes->error[2];
        http_response_code($cod);
        $response = json_encode($_clientes->error);
        echo $response;
    } else {
        $cod = $_clientes->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_clientes->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $_clientes->post($postBody);
    if ($_clientes->error) {
        $cod = $_clientes->error[2];
        http_response_code($cod);
        $response = json_encode($_clientes->error);
        echo $response;
    } else {
        $cod = $_clientes->respuesta[2];
        http_response_code($cod);
        $response = json_encode($_clientes->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

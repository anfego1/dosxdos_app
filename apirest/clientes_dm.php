<?php

require_once 'config.php';
require_once 'clases/clientes_dm_clase.php';

$_respuestas = new Respuestas;
$clientes_dm = new ClientesDm;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["cod"])) {
        $cod = $_GET['cod'];
        $clientes_dm->cliente($cod);
        if ($clientes_dm->error) {
            $cod = $clientes_dm->error[2];
            http_response_code($cod);
            $response = json_encode($clientes_dm->error);
            echo $response;
        } else {
            $cod = $clientes_dm->respuesta[2];
            http_response_code($cod);
            $response = json_encode($clientes_dm->respuesta);
            echo $response;
        }
    } else {
        $clientes_dm->clientes();
        if ($clientes_dm->error) {
            $cod = $clientes_dm->error[2];
            http_response_code($cod);
            $response = json_encode($clientes_dm->error);
            echo $response;
        } else {
            $cod = $clientes_dm->respuesta[2];
            http_response_code($cod);
            $response = json_encode($clientes_dm->respuesta);
            echo $response;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $clientes_dm->put($postBody);
    if ($clientes_dm->error) {
        $cod = $clientes_dm->error[2];
        http_response_code($cod);
        $response = json_encode($clientes_dm->error);
        echo $response;
    } else {
        $cod = $clientes_dm->respuesta[2];
        http_response_code($cod);
        $response = json_encode($clientes_dm->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $clientes_dm->post($postBody);
    if ($clientes_dm->error) {
        $cod = $clientes_dm->error[2];
        http_response_code($cod);
        $response = json_encode($clientes_dm->error);
        echo $response;
    } else {
        $cod = $clientes_dm->respuesta[2];
        http_response_code($cod);
        $response = json_encode($clientes_dm->respuesta);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $clientes_dm->delete($postBody);
    if ($clientes_dm->error) {
        $cod = $clientes_dm->error[2];
        http_response_code($cod);
        $response = json_encode($clientes_dm->error);
        echo $response;
    } else {
        $cod = $clientes_dm->respuesta[2];
        http_response_code($cod);
        $response = json_encode($clientes_dm->respuesta);
        echo $response;
    }
} else {
    $respuesta = $_respuestas->error_405();
    $response = json_encode($respuesta);
    http_response_code(405);
    echo $response;
}

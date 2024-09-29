<?php

require_once 'config.php';
require_once 'clases/respuestas_clase.php';
require_once 'clases/usuarios_clase.php';

$_respuestas = new respuestas;
$_usuarios = new usuarios;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["id"])) {
        $usuarioId = $_GET['id'];
        $datosUsuario = $_usuarios->usuario($usuarioId);
        $cod = $datosUsuario[2];
        http_response_code($cod);
        $response = json_encode($datosUsuario);
        echo $response;
    } else if (isset($_GET["clientes"])) {
        $listaUsuarios = $_usuarios->clientes();
        $cod = $listaUsuarios[2];
        http_response_code($cod);
        $response = json_encode($listaUsuarios);
        echo $response;
    } else if (isset($_GET["cliente"])) {
        $id = $_GET["cliente"];
        $listaUsuarios = $_usuarios->cliente($id);
        $cod = $listaUsuarios[2];
        http_response_code($cod);
        $response = json_encode($listaUsuarios);
        echo $response;
    } else {
        $listaUsuarios = $_usuarios->usuarios();
        $cod = $listaUsuarios[2];
        http_response_code($cod);
        $response = json_encode($listaUsuarios);
        echo $response;
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $datosArray = $_usuarios->post($postBody);
    if (!$datosArray[0]) {
        http_response_code($datosArray[2]);
    } else {
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $datosArray = $_usuarios->put($postBody);
    if (!$datosArray[0]) {
        http_response_code($datosArray[2]);
    } else {
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $datosArray = $_usuarios->delete($postBody);
    if (!$datosArray[0]) {
        http_response_code($datosArray[2]);
    } else {
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else {
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
}

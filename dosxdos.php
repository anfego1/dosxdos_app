<?php

require_once 'config.php';

$idUsuario;
$id;
$clase;
$usuario;
$cod;
$contrasena;
$correo;
$movil;
$nombre;
$apellido;
$imagen;
$activo;
$mensaje = '';

if (!isset($_COOKIE['login'])) {
    header("location: index.html");
}

if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}

if (isset($_COOKIE['usuario'])) {
    $idUsuario = $_COOKIE['usuario'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

if ($conexion && $idUsuario) {
    $query = "SELECT * FROM usuarios WHERE id = $idUsuario";
    $result = $conexion->datos($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $clase = $row['clase'];
        $usuario = $row['usuario'];
        $cod = $row['cod'];
        $contrasena = $row['contrasena'];
        $correo = $row['correo'];
        $movil = $row['movil'];
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $imagen = $row['imagen'];
    }
} else {
    $mensaje .= '--Error en la conexión con la base de datos o la obtención del login del usuario: ' . $conexion->error . '--';
}

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="http://localhost/dosxdos_app/css/cdn_data_tables.css">
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="http://localhost/dosxdos_app/img/logoPwa256.png">
    <style>
        @charset "UTF-8";

        /* $variables - 40% */
        :root {
            --x1_7: 0.41vw;
            --x60: 14.56vw;
            --x10: 2.43vw;
            --x300: 72.82vw;
            --x15: 3.64vw;
            --x5: 1.21vw;
            --x70: 16.99vw;
            --x25: 6.07vw;
            --x3: 0.73vw;
            --x1: 0.24vw;
            --x20: 4.85vw;
            --x250: 60.68vw;
            --x30: 7.28vw;
            --x22: 5.34vw;
            --x28: 6.8vw;
            --x1_5: 0.36vw;
            --x2: 0.49vw;
            --x18: 4.37vw;
            --x2_5: 0.61vw;
            --x220: 53.4vw;
            --x50: 12.14vw;
            --x380: 92.23vw;
            --x150: 36.41vw;
        }

        /* INICIO */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            letter-spacing: var(--x1_7);
        }

        .displayOff {
            display: none;
        }

        .displayOn {
            display: flex;
        }

        .borde {
            border: var(--x1) solid black;
        }

        body {
            overflow-x: hidden;
        }

        .oyh {
            overflow-y: hidden;
        }

        /* FUENTES */
        @font-face {
            font-family: 'Roboto';
            src: url("http://localhost/dosxdos_app/css/fuentes/Roboto/Roboto-Light.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Merriweather';
            src: url("http://localhost/dosxdos_app/css/fuentes/Merriweather/Merriweather-Light.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Merriweather-Bold';
            src: url("http://localhost/dosxdos_app/css/fuentes/Merriweather/Merriweather-Bold.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Lora';
            src: url("http://localhost/dosxdos_app/css/fuentes/Lora/Lora-Regular.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Lora-Medium';
            src: url("http://localhost/dosxdos_app/css/fuentes/Lora/Lora-Medium.ttf") format("truetype");
        }

        @font-face {
            font-family: 'Lora-Bold';
            src: url("http://localhost/dosxdos_app/css/fuentes/Lora/Lora-Bold.ttf") format("truetype");
        }

        /* LOADER */
        #loader {
            width: 100vw;
            height: 100vh;
            position: absolute;
            top: 0;
            z-index: 2000;
            background-color: rgba(0, 0, 0, 0.886);
            justify-content: center;
            align-items: center;
        }

        .loader {
            transform: rotateZ(45deg);
            perspective: 1000px;
            border-radius: 50%;
            width: var(--x60);
            height: var(--x60);
            color: #fff;
        }

        .loader:before,
        .loader:after {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: inherit;
            height: inherit;
            border-radius: 50%;
            transform: rotateX(70deg);
            animation: 1s spin linear infinite;
        }

        .loader:after {
            color: #b20b15;
            transform: rotateY(70deg);
            animation-delay: .4s;
        }

        @keyframes rotate {
            0% {
                transform: translate(-50%, -50%) rotateZ(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotateZ(360deg);
            }
        }

        @keyframes rotateccw {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(-360deg);
            }
        }

        @keyframes spin {

            0%,
            100% {
                box-shadow: .2em 0px 0 0px currentcolor;
            }

            12% {
                box-shadow: .2em .2em 0 0 currentcolor;
            }

            25% {
                box-shadow: 0 .2em 0 0px currentcolor;
            }

            37% {
                box-shadow: -.2em .2em 0 0 currentcolor;
            }

            50% {
                box-shadow: -.2em 0 0 0 currentcolor;
            }

            62% {
                box-shadow: -.2em -.2em 0 0 currentcolor;
            }

            75% {
                box-shadow: 0px -.2em 0 0 currentcolor;
            }

            87% {
                box-shadow: .2em -.2em 0 0 currentcolor;
            }
        }

        /* NAVEGACIÓN */
        #encabezado {
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        #logo {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            padding: var(--x10);
        }

        #logo>img {
            width: var(--x300);
        }

        #menu {
            display: flex;
            width: 100%;
            align-items: center;
            padding: var(--x10);
            justify-content: space-between;
            margin-top: var(--x10);
        }

        #usuario {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #usuario:hover {
            cursor: pointer;
        }

        #iconoUsuario {
            display: flex;
            width: var(--x70);
            height: var(--x70);
            border-radius: 50%;
        }

        #iconoUsuario>img {
            width: var(--x70);
            height: var(--x70);
            border-radius: 50%;
        }

        #nombreUsuario {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: var(--x25);
            margin-left: var(--x5);
            font-family: "Merriweather-Bold", "Merriweather-Bold";
        }

        #flechaUsuario {
            display: flex;
            width: var(--x25);
            height: var(--x25);
            margin-left: var(--x3);
        }

        #flechaUsuario>img {
            width: var(--x25);
            height: var(--x25);
        }

        .botonIcono {
            border: var(--x1) solid gray;
            display: flex;
            width: var(--x70);
            height: var(--x70);
            border-radius: 20%;
            background-color: #d31216;
        }

        .botonIcono:hover {
            cursor: pointer;
        }

        .botonIcono:active {
            background-color: #b20b15;
        }

        .botonIcono>img {
            width: var(--x70);
            height: var(--x70);
            border-radius: 20%;
            padding: var(--x10);
        }

        #opcionesMenu {
            width: 100%;
            padding: var(--x15);
            justify-content: space-evenly;
            align-items: flex-start;
            flex-wrap: wrap;
            overflow-x: auto;
        }

        #opcionesMenu2 {
            width: 100%;
            padding: var(--x15);
            justify-content: space-evenly;
            align-items: flex-start;
            flex-wrap: wrap;
            overflow-x: auto;
        }

        .opcionMenu {
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .opcionMenu p {
            display: flex;
            width: var(--x150);
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-wrap: wrap;
            font-size: var(--x20);
            font-family: "Merriweather-Bold", "Merriweather-Bold";
        }

        #opcionesUsuario {
            width: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: var(--x15);
        }

        .boton {
            width: var(--x250);
            border-radius: var(--x30);
            font-family: 'Merriweather-Bold', 'Merriweather-Bold';
            font-size: var(--x22);
            height: var(--x60);
            padding: var(--x10);
            border: var(--x1) solid gray;
            color: white;
            background-color: #d31216;
        }

        .boton2 {
            width: var(--x250);
            border-radius: var(--x30);
            font-family: 'Merriweather-Bold', 'Merriweather-Bold';
            font-size: var(--x22);
            height: var(--x60);
            padding: var(--x10);
            border: var(--x1) solid gray;
            color: white;
            background-color: #d31216;
            margin-top: var(--x15);
        }

        .boton:hover {
            cursor: pointer;
        }

        .boton:active {
            background-color: #b20b15;
        }

        .boton2:hover {
            cursor: pointer;
        }

        .boton2:active {
            background-color: #b20b15;
        }

        .titulo {
            justify-content: center;
            align-items: center;
            font-family: 'Lora-Bold', 'Lora-Bold';
            width: 100%;
            font-size: var(--x28);
            padding: var(--x5);
            text-align: center;
            margin-top: var(--x20);
            position: relative;
        }

        .tituloFijo {
            justify-content: center;
            align-items: center;
            font-family: 'Lora-Bold', 'Lora-Bold';
            width: 100%;
            font-size: var(--x28);
            padding: var(--x5);
            text-align: center;
            position: fixed;
            top: 0;
            margin-top: 0;
            z-index: 10000;
            background-color: #f4f4f4;
        }

        #mensaje {
            position: relative;
            flex-wrap: wrap;
            text-align: center;
            font-size: var(--x18);
            letter-spacing: var(--x2_5);
            background-color: rgba(0, 0, 0, 0.573);
            color: white;
            padding: var(--x10);
            font-family: 'Roboto', 'Roboto-Light';
            width: 100%;
            justify-content: center;
        }

        #mensaje>p {
            display: flex;
            flex-wrap: wrap;
            margin-top: var(--x20);
            margin-bottom: var(--x20);
        }

        #imgCerrar {
            position: absolute;
            width: var(--x28);
            top: var(--x2);
            right: var(--x2);
        }

        #imgCerrar:hover {
            cursor: pointer;
        }

        #contenido {
            width: 100%;
            flex-direction: column;
            padding: var(--x15);
            justify-content: center;
            align-items: center;
            background-color: #e1e1e1;
        }

        .ruta {
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            border-radius: var(--x30);
            font-family: 'Lora-Bold', 'Lora-Bold';
            font-size: var(--x22);
            border: var(--x1) solid gray;
            color: white;
            background-color: #d31216;
            margin-top: var(--x10);
            margin-bottom: var(--x10);
            padding: var(--x15);
        }

        .ruta:hover {
            cursor: pointer;
        }

        .ruta:active {
            background-color: #b20b15;
        }

        .ruta2 {
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            border-radius: var(--x30);
            font-family: 'Lora-Bold', 'Lora-Bold';
            font-size: var(--x22);
            border: var(--x1) solid gray;
            color: white;
            background-color: black;
            margin-top: var(--x10);
            margin-bottom: var(--x10);
            padding: var(--x15);
        }

        .ruta2:hover {
            cursor: pointer;
        }

        .ruta2:active {
            background-color: #b20b15;
        }

        .resaltado {
            background-color: rgba(0, 0, 0, 0.573);
        }

        /* LINEA_MONTADOR */
        #datosBoton {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            border-radius: var(--x30);
            font-family: 'Lora-Bold', 'Lora-Bold';
            font-size: var(--x22);
            border: var(--x1) solid gray;
            color: white;
            background-color: rgba(0, 0, 0, 0.573);
            margin-top: var(--x20);
            margin-bottom: var(--x10);
            padding: var(--x15);
        }

        #datosBoton:hover {
            cursor: pointer;
        }

        #datosBoton:active {
            background-color: black;
        }

        #cajaCanvasFirma {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 2000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #e1e1e1;
        }

        #borrarFirma {
            margin-top: var(--x60);
            margin-bottom: var(--x20);
        }

        .bDark {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            border-radius: var(--x30);
            font-family: 'Lora-Bold', 'Lora-Bold';
            font-size: var(--x22);
            border: var(--x1) solid gray;
            color: white;
            background-color: rgba(0, 0, 0, 0.573);
            margin-top: var(--x10);
            margin-bottom: var(--x10);
            padding: var(--x15);
        }

        .bDark:hover {
            cursor: pointer;
        }

        .bDark:active {
            background-color: black;
        }

        #fotos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            border-radius: var(--x30);
            font-family: 'Lora-Bold', 'Lora-Bold';
            font-size: var(--x22);
            border: var(--x1) solid gray;
            color: white;
            background-color: rgba(0, 0, 0, 0.573);
            margin-top: var(--x20);
            padding: var(--x15);
            margin-top: var(--x30);
            margin-bottom: var(--x20);
        }

        #fotos:hover {
            cursor: pointer;
        }

        #fotos:active {
            background-color: black;
        }

        #datosLinea {
            width: 100%;
            flex-direction: column;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            padding: var(--x20);
            padding-bottom: var(--x30);
            border: var(--x1) solid black;
        }

        .tituloDatos {
            display: flex;
            width: 100%;
            margin-top: var(--x20);
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align: center;
            font-size: var(--x22);
            font-weight: normal;
        }

        .pDatos {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align: center;
            font-size: var(--x20);
            color: #d31216;
        }

        label {
            display: flex;
            width: 100%;
            margin-top: var(--x20);
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align: center;
            font-size: var(--x22);
        }

        input {
            display: flex;
            width: var(--x300);
            height: var(--x50);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: var(--x22);
            color: #d31216;
            margin-top: var(--x10);
            padding: var(--x10);
            border: var(--x1) solid black;
        }

        input:focus {
            outline-color: #b20b15;
        }

        #fotosGaleria {
            font-size: var(--x28);
            width: var(--x380);
            height: var(--x60);
            padding: 0;
            border: none;
        }

        #fotosGaleria:hover {
            cursor: pointer;
        }

        select {
            display: flex;
            width: var(--x300);
            height: var(--x50);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: var(--x22);
            color: #d31216;
            margin-top: var(--x10);
            padding: var(--x10);
            border: var(--x1) solid black;
        }

        select:focus {
            outline-color: #b20b15;
        }

        textarea {
            width: var(--x380);
            height: var(--x150);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: var(--x22);
            color: #d31216;
            margin-top: var(--x10);
            padding: var(--x10);
            border: var(--x1) solid black;
        }

        textarea:focus {
            outline-color: #b20b15;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #cajaEstado {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #cajaForm {
            width: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #listaCamaras {
            width: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: var(--x20);
        }

        #camara {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 2000;
            justify-content: center;
            align-items: center;
            background-color: #e1e1e1;
        }

        #video {
            display: flex;
            width: 100%;
            height: 100%;
        }

        #video:active {
            background-color: rgba(0, 0, 0, 0.573);
        }

        #imgCerrarCamara {
            position: absolute;
            width: var(--x30);
            top: var(--x10);
            right: var(--x10);
            z-index: 2000;
        }

        #imgCerrarFirma {
            position: absolute;
            width: var(--x30);
            top: var(--x10);
            right: var(--x10);
            z-index: 2000;
        }

        #imgCerrarCamara:hover {
            cursor: pointer;
        }

        #imgCerrarFirma:hover {
            cursor: pointer;
        }

        #photosContainer {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
        }

        #firmasContainer {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
        }

        #carpetasContainer {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
        }

        #firmar {
            margin-top: var(--x30);
        }

        .cajaFoto {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
            margin-top: var(--x50);
        }

        .cajaFirma {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
        }

        .imgCamara {
            max-width: 100%;
            height: auto;
        }

        .imgComprimido {
            width: var(--x70);
            height: auto;
        }

        .imgFirma {
            max-width: 100%;
            height: auto;
            background-color: white;
            border: var(--x1) solid black;
        }

        #tituloFirma {
            display: flex;
            width: 100%;
            margin-top: var(--x20);
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align: center;
            font-size: var(--x22);
            font-weight: normal;
            margin-bottom: var(--x10);
        }

        #enviar {
            margin-top: var(--x60);
        }

        .seccionArchivos {
            display: Flex;
            width: 100%;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            margin-top: var(--x20);
            border: var(--x1) solid black;
            padding-left: var(--x10);
            padding-right: var(--x10);
            padding-bottom: var(--x50);
        }

        h2 {
            margin-top: var(--x50);
            font-size: var(--x30);
        }

        .enlaceImg {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
        }

        .enlaceCarpeta {
            border: var(--x1) solid gray;
            display: flex;
            width: var(--x70);
            height: var(--x70);
            border-radius: 20%;
            background-color: #d31216;
        }

        .detalleFoto {
            display: flex;
            flex-direction: column;
            width: 100%;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: var(--x30);
            margin-top: var(--x10);
        }

        .loader2 {
            width: var(--x50);
            height: var(--x50);
            margin-top: var(--x30);
            border: 5px solid #FFF;
            border-bottom-color: #b20b15;
            border-radius: 50%;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .imgTrash {
            width: var(--x50);
        }

        .imgTrash:hover {
            cursor: pointer;
        }

        .checkVisible {
            width: var(--x50);
            margin-left: var(--x30);
            background-color: #d31216;
        }

        .checkVisible:hover {
            cursor: pointer;
        }

        .checkVisible:checked {
            background-color: #d31216;
        }

        .cajaOpcionesFoto {
            display: flex;
            width: 100%;
            justify-content: center;
            margin-top: var(--x20);
        }

        #crearCarpeta {
            margin-top: var(--x50);
            margin-bottom: var(--x50);
        }

        #crearUsuario {
            display: flex;
            width: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .cInpunt {
            display: flex;
            width: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #contrasena2 {
            margin-right: 0;
        }

        #imagen {
            border: none;
        }

        .enlaceBoton {
            display: flex;
            width: 100%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: var(--x20);
        }

        .imagenUsuario {
            width: var(--x50);
            height: var(--x50);
            border-radius: 50%;
        }

        .overfila:hover {
            background-color: rgba(128, 128, 128, 0.479);
            cursor: pointer;
        }

        .overfila:active {
            background-color: gray;
        }

        th {
            font-size: var(--x25);
        }

        #contenedorTabla {
            display: flex;
            flex-direction: column;
            width: 100%;
            overflow-x: scroll;
        }

        .bolder {
            font-weight: bolder;
        }

        tr {
            font-size: var(--x25);
        }

        #cajaImagenPerfil {
            margin-top: var(--x50);
            display: flex;
            width: var(--x150);
            height: var(--x150);
            border-radius: 50%;
        }

        #imagenPerfil {
            margin-top: var(--x50);
            width: var(--x150);
            height: var(--x150);
            border-radius: 50%;
        }

        .requerido {
            color: #d31216;
            font-weight: bolder;
        }

        /* MOVILES GRANDES */
        @media screen and (min-width: 412px) and (max-width: 767px) {
            :root {
                --x1_7: 1.7px;
                --x60: 60px;
                --x10: 10px;
                --x300: 300px;
                --x15: 15px;
                --x5: 5px;
                --x70: 70px;
                --x25: 25px;
                --x3: 3px;
                --x1: 1px;
                --x20: 20px;
                --x250: 250px;
                --x30: 30px;
                --x22: 22px;
                --x28: 28px;
                --x1_5: 1.5px;
                --x2: 2px;
                --x18: 18px;
                --x2_5: 2.5px;
                --x220: 220px;
                --x50: 50px;
                --x380: 380px;
                --x150: 150px;
            }
        }

        /* TABLETS */
        @media screen and (min-width: 768px) and (max-width: 1199px) {
            :root {
                --x1_7: 0.09vw;
                --x60: 3vw;
                --x10: 0.5vw;
                --x300: 15vw;
                --x15: 0.75vw;
                --x5: 0.25vw;
                --x70: 3.5vw;
                --x25: 1.25vw;
                --x3: 0.15vw;
                --x1: 0.05vw;
                --x20: 1vw;
                --x250: 12.5vw;
                --x30: 1.5vw;
                --x22: 1.1vw;
                --x28: 1.4vw;
                --x1_5: 0.08vw;
                --x2: 0.1vw;
                --x18: 0.9vw;
                --x2_5: 0.13vw;
                --x220: 11vw;
                --x50: 2.5vw;
                --x380: 19vw;
                --x150: 7.5vw;
            }

            #menu {
                justify-content: space-evenly;
            }

            .imgCamara {
                max-width: 80%;
            }

            .imgFirma {
                max-width: 80%;
            }

            .seccionArchivos {
                width: 80%;
            }

            .enlaceImg {
                width: 80%;
            }

            #contenedorTabla {
                width: 80%;
            }
        }

        /* ESCRITORIOS */
        @media screen and (min-width: 1200px) {
            :root {
                --x1_7: 1.02px;
                --x60: 36px;
                --x10: 6px;
                --x300: 180px;
                --x15: 9px;
                --x5: 3px;
                --x70: 42px;
                --x25: 15px;
                --x3: 1.8px;
                --x1: 0.6px;
                --x20: 12px;
                --x250: 150px;
                --x30: 18px;
                --x22: 13.2px;
                --x28: 16.8px;
                --x1_5: 0.9px;
                --x2: 1.2px;
                --x18: 10.8px;
                --x2_5: 1.5px;
                --x220: 132px;
                --x50: 30px;
                --x380: 228px;
                --x150: 90px;
            }

            #menu {
                justify-content: space-evenly;
            }

            .imgCamara {
                max-width: 70%;
            }

            .imgFirma {
                max-width: 70%;
            }

            .seccionArchivos {
                width: 80%;
            }

            .enlaceImg {
                width: 70%;
            }

            #contenedorTabla {
                width: 80%;
            }
        }
    </style>
    <script src="js/jquery.js"></script>
    <script src="js/data_tables.js"></script>
    <script src="js/cdn_data_tables.js"></script>
    <script src="js/index_db.js"></script>
    <script>
        let titulo1,
            titulo2,
            mensajePhp;
        <?php if ($mensaje) {
            echo 'mensajePhp = "' . $mensaje . '"';
        } ?>
    </script>
</head>

<body id="body" class="">

    <div id="loader" class="displayOn">
        <span class="loader"></span>
    </div>

    <section id="encabezado" class="displayOn">

        <div id="logo">
            <img src="http://localhost/dosxdos_app/img/logo300.png">
        </div>

        <nav id="menu">

            <div id="usuario">
                <div id="iconoUsuario">
                    <img src="http://localhost/dosxdos_app/img/usuario.png" id="imagenUsuario">
                </div>
                <p id="nombreUsuario"></p>
                <div id="flechaUsuario">
                    <img src="http://localhost/dosxdos_app/img/flechaAbajo.png">
                </div>
            </div>

            <button id="casa" class="botonIcono">
                <img src="http://localhost/dosxdos_app/img/casaWhite.png">
            </button>

        </nav>

        <nav id="opcionesMenu" class="displayOff">

            <div class="opcionMenu displayOff" id="horarios">
                <button class="botonIcono" type="button" id="horariosBoton">
                    <img src="http://localhost/dosxdos_app/img/relojWhite.png">
                </button>
                <p>Horarios</p>
            </div>

            <div class="opcionMenu displayOff" id="archivos">
                <button class="botonIcono" type="button" id="archivosBoton">
                    <img src="http://localhost/dosxdos_app/img/work.png">
                </button>
                <p>OT</p>
            </div>

            <div class="opcionMenu displayOff" id="icLineasOt">
                <button class="botonIcono" type="button" id="icLineasOtBoton">
                    <img src="http://localhost/dosxdos_app/img/task.png">
                </button>
                <p>Líneas OT</p>
            </div>

            <div class="opcionMenu displayOff" id="pv">
                <button class="botonIcono" type="button" id="pvBoton">
                    <img src="http://localhost/dosxdos_app/img/tienda.png">
                </button>
                <p>PV</p>
            </div>

            <div class="opcionMenu displayOff" id="rutasIcono">
                <button class="botonIcono" type="button" id="rutasIconoBoton">
                    <img src="http://localhost/dosxdos_app/img/rutasWhite.png">
                </button>
                <p>Rutas</p>
            </div>

            <div class="opcionMenu displayOff" id="lineasIcono">
                <button class="botonIcono" type="button" id="lineasIconoBoton">
                    <img src="http://localhost/dosxdos_app/img/task.png">
                </button>
                <p>Líneas Ruta</p>
            </div>

            <div class="opcionMenu displayOff" id="usuarios">
                <button class="botonIcono" type="button" id="usuariosBoton">
                    <img src="http://localhost/dosxdos_app/img/usuarios.png">
                </button>
                <p>Usuarios</p>
            </div>

            <div class="opcionMenu displayOff" id="usuariosOficina">
                <button class="botonIcono" type="button" id="usuariosOficinaBoton">
                    <img src="http://localhost/dosxdos_app/img/usuarios.png">
                </button>
                <p>Usuarios</p>
            </div>

            <div class="opcionMenu displayOff" id="rutasMontador">
                <button class="botonIcono" type="button" id="rutasMontadorBoton">
                    <img src="http://localhost/dosxdos_app/img/rutasWhite.png">
                </button>
                <p>Rutas</p>
            </div>

            <div class="opcionMenu displayOff" id="lineasMontador">
                <button class="botonIcono" type="button" id="lineasMontadorBoton">
                    <img src="http://localhost/dosxdos_app/img/task.png">
                </button>
                <p>Líneas</p>
            </div>

            <div class="opcionMenu displayOff" id="dm">
                <button class="botonIcono" type="button" id="dmBoton">
                    <img src="http://localhost/dosxdos_app/img/dm.png">
                </button>
                <p>DM</p>
            </div>

            <div class="opcionMenu displayOff" id="reciclar">
                <button class="botonIcono" type="button" id="reciclarBoton">
                    <img src="http://localhost/dosxdos_app/img/papelera.png">
                </button>
                <p>Reciclar</p>
            </div>

        </nav>

        <nav id="opcionesUsuario" class="displayOff">

            <button type="button" id="editarUsuario" class="boton">Editar usuario</button>
            <button type="button" id="cerrarSesion" class="boton2">Cerrar sesión</button>

        </nav>

        <div id="mensaje" class="displayOff">
            <p id="textoMensaje"></p><img id="imgCerrar" src="http://localhost/dosxdos_app/img/cerrar.png">
        </div>

        <div id="tituloVisible"></div>

        <h1 id="titulo" class="displayOn titulo"></h1>

    </section>

    <?php
    if (isset($_GET['modulo'])) {
        if ($_GET['modulo'] == 'crearUsuario') {
            require_once 'crear_usuario.php';
        } else if ($_GET['modulo'] == 'editarUsuario') {
            if ($id) {
                require_once 'editar_usuario.php';
            } else {
                $mensaje .= 'Es necesario especificar el id del usuario a editar en las variables de la url';
    ?>
                <meta http-equiv="refresh" content="0; url=dosxdos.php?mensaje=<?php echo $mensaje ?>">
            <?php
            }
        } else if ($_GET['modulo'] == 'usuarios') {
            require_once 'usuarios.php';
        } else if ($_GET['modulo'] == 'archivos') {
            ?>
            <meta http-equiv="refresh" content="0; url=ot.html">
    <?php
        } else {
            if ($clase == 'admon') {
                require_once 'usuarios.php';
            }
        }
    }
    ?>


</body>

<script>
    const tituloElement = document.getElementById('titulo');
    const tituloVisible = document.getElementById('tituloVisible');

    function checkVisibility() {
        const tituloVisiblePosition = tituloVisible.getBoundingClientRect();
        const isVisible = tituloVisiblePosition.top > 0;
        const tieneClaseTitulo = tituloElement.classList.contains('tituloFijo');
        if (isVisible && tieneClaseTitulo) {
            tituloElement.classList.remove('tituloFijo');
            tituloElement.classList.add('titulo');
        } else if (!isVisible && !tieneClaseTitulo) {
            tituloElement.classList.remove('titulo');
            tituloElement.classList.add('tituloFijo');
        }
    }
    // Llama a checkVisibility al cargar la página y al cambiar el tamaño de la ventana
    window.addEventListener('load', checkVisibility);
    window.addEventListener('resize', checkVisibility);
    // Llama a checkVisibility al hacer scroll, utilizando requestAnimationFrame
    window.addEventListener('scroll', () => {
        requestAnimationFrame(checkVisibility);
    });

    //ALERTAS AL ELIMINAR
    $(document).ready(function() {
        $(".eliminar").click(function(e) {
            e.preventDefault();
            var res = confirm("Confirma por favor la eliminación");
            if (res == true) {
                var link = $(this).attr("href");
                window.location = link;
            }
        });
    });

    function overfila(id) {
        window.location.href = `http://localhost/dosxdos_app/dosxdos.php?modulo=editarUsuario&id=${id}`;
    }

    /*REENVÍO DE FORMULARIOS */

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    /* CONSTANTES DEL DOM */
    const $opcionesMenu = document.getElementById('opcionesMenu'),
        $opcionesUsuario = document.getElementById('opcionesUsuario'),
        $casa = document.getElementById('casa'),
        $usuario = document.getElementById('usuario'),
        $rutas = document.getElementById('rutas'),
        $loader = document.getElementById('loader'),
        $body = document.getElementById('body'),
        $mensaje = document.getElementById('mensaje'),
        $imgCerrar = document.getElementById('imgCerrar'),
        $textoMensaje = document.getElementById('textoMensaje'),
        buscador = document.getElementById('buscador'),
        $nombreUsuario = document.getElementById('nombreUsuario'),
        $imagenUsuario = document.getElementById('imagenUsuario'),
        $horarios = document.getElementById('horarios'),
        $archivos = document.getElementById('archivos'),
        $usuarios = document.getElementById('usuarios'),
        $rutasIcono = document.getElementById('rutasIcono'),
        $lineasIcono = document.getElementById('lineasIcono'),
        $pv = document.getElementById('pv'),
        $icLineasOt = document.getElementById('icLineasOt'),
        $icClientes = document.getElementById('icClientes'),
        $dm = document.getElementById('dm'),
        $reciclar = document.getElementById('reciclar'),
        $rutasMontador = document.getElementById('rutasMontador'),
        $lineasMontador = document.getElementById('lineasMontador');
    let usuario,
        enviar;

    /* VALIDACIÓN DE FORMULARIOS */

    const expresiones = {
            usuario: /^(?:[a-zA-Z]+|\d+|[a-zA-Z\d]+){4,8}$/,
            contrasena: /^(?:[a-zA-Z\d!@#$%^&*()-+=<>?/\|[\]{}:;,.]+){4,12}$/,
            correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
        },

        /* Campos */
        campos = {
            usuario: false,
            codigo: false,
            contrasena: false,
            contrasena2: false,
            correo: false,
            nombre: false,
        };

    const validarCampo = (expresion, input, campo) => {
        if (expresion.test(input)) {
            campos[campo] = true;
        } else {
            campos[campo] = false;
        }
    }

    function trim(cadena) {
        if (cadena != null) {
            return cadena.replace(/\s+/g, '');
        } else {
            return '';
        }
    }

    const validarVacio = (input) => {
        if (trim(input) != '') {
            return false;
        } else {
            return true;
        }
    }

    /* LISTENERS */
    document.addEventListener('DOMContentLoaded', () => {

        const casaButton = document.getElementById('casa');
        casaButton.addEventListener('click', () => {
            toggleElemento('opcionesMenu');
        });

        const usuarioButton = document.getElementById('usuario');
        usuarioButton.addEventListener('click', () => {
            toggleElemento('opcionesUsuario');
        });

        const cerrarSesion = document.getElementById('cerrarSesion');
        cerrarSesion.addEventListener('click', () => {
            cerrarSesions();
        });

        const imgCerrar = document.getElementById('imgCerrar');
        imgCerrar.addEventListener('click', () => {
            mensajeOff();
        });

        const rutasIcono = document.getElementById('rutasIcono');
        rutasIcono.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/rutas.html";
        });

        const pvBoton = document.getElementById('pv');
        pvBoton.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/pv.html";
        });

        const lineasIcono = document.getElementById('lineasIcono');
        lineasIcono.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/lineas.html";
        })

        const usuarios = document.getElementById('usuarios');
        usuarios.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/dosxdos.php?modulo=usuarios";
        })

        const horariosClick = document.getElementById('horarios');
        horariosClick.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/horarios.html";
        })

        const icLineasOtClick = document.getElementById('icLineasOt');
        icLineasOtClick.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/lineas_ot.html";
        })

        const pvClick = document.getElementById('pv');
        pvClick.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/pv.html";
        })

        const archivosClick = document.getElementById('archivos');
        archivosClick.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/ot.html";
        })

        const usuariosOficina = document.getElementById('usuariosOficina');
        usuariosOficina.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/usuarios_oficina.html";
        })

        const rutasMontador = document.getElementById('rutasMontador');
        rutasMontador.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/rutas_montador.html";
        })

        const lineasMontador = document.getElementById('lineasMontador');
        lineasMontador.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/ruta_montador.html";
        })

        $dm.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/dm.html";
        })

        $reciclar.addEventListener('click', () => {
            window.location.href = "http://localhost/dosxdos_app/reciclar.html";
        })

        //SUBMITS

        if (document.getElementById('crearUsuarioFormulario')) {
            console.log('Formulario de crear usuario reconocido');
            if (document.getElementById('enviar')) {
                enviar = document.getElementById('enviar');
            }
            if (enviar) {
                enviar.addEventListener('click', () => {
                    const formulario = document.getElementById('crearUsuarioFormulario');
                    console.log('Ejecución del listener submit del formulario para crear usuario');
                    loaderOn();
                    let mensaje = '';
                    let formData = new FormData(formulario);
                    window.scrollTo(0, 0);
                    usuario = formData.get('usuario');
                    codigo = formData.get('cod');
                    contrasena = formData.get('contrasena');
                    contrasena2 = formData.get('contrasena2');
                    correo = formData.get('correo');
                    nombre = formData.get('nombre');
                    validarCampo(expresiones.usuario, usuario, 'usuario');
                    const vCodigo = validarVacio(codigo);
                    if (vCodigo) {
                        campos.codigo = false;
                    } else {
                        campos.codigo = true;
                    }
                    validarCampo(expresiones.contrasena, contrasena, 'contrasena');
                    if (contrasena === contrasena2) {
                        campos.contrasena2 = true;
                    }
                    const vCorreo = validarVacio(correo);
                    if (vCorreo) {
                        campos.correo = false;
                    } else {
                        validarCampo(expresiones.correo, correo, 'correo');
                    }
                    const vNombre = validarVacio(nombre);
                    if (vNombre) {
                        campos.nombre = false;
                    } else {
                        campos.nombre = true;
                    }
                    if (!campos.usuario || !campos.codigo || !campos.contrasena || !campos.contrasena2 || !campos.correo || !campos.nombre) {
                        if (!campos.usuario) {
                            mensaje += '-Formato de usuario no válido; en el usuario sólo se permite el uso de letras y dígitos (4 a 8 caracteres sin espacios)-'
                        }
                        if (!campos.codigo) {
                            mensaje += '-Si el usuario es montador o cliente es necesario ingresar el código de NAVISION que pertenece al montador o el cliente. Si el usuario es de tipo oficina o administración es necesario crear un código para comenzar a relacionarlo con NAVISION-'
                        }
                        if (!campos.contrasena) {
                            mensaje += '-Formato de contraseña no válido; en la contraseña sólo se permite el uso de letras, dígitos y caracteres especiales (4 a 12 caracteres sin espacios)-'
                        }
                        if (!campos.contrasena2) {
                            mensaje += '-Las contraseñas no coinciden-'
                        }
                        if (!campos.correo) {
                            mensaje += '-El formato de correo no es válido-'
                        }
                        if (!campos.nombre) {
                            mensaje += '-Es necesario ingresar el nombre del usuario para poder identificarlo en la interfaz de la aplicación-'
                        }
                        $textoMensaje.innerHTML = mensaje;
                        mensajeOn();
                        loaderOff();
                    } else {
                        formulario.submit();
                    }
                })
            }
        }

        if (document.getElementById('editarUsuarioFormulario')) {
            console.log('Formulario de editar usuario reconocido');
            if (document.getElementById('enviar')) {
                enviar = document.getElementById('enviar');
            }
            if (enviar) {
                enviar.addEventListener('click', () => {
                    const formulario = document.getElementById('editarUsuarioFormulario');
                    console.log('Ejecución del listener submit del formulario para editar usuario');
                    loaderOn();
                    let mensaje = '';
                    let formData = new FormData(formulario);
                    window.scrollTo(0, 0);
                    usuario = formData.get('usuario');
                    codigo = formData.get('cod');
                    contrasena = formData.get('contrasena');
                    contrasena2 = formData.get('contrasena2');
                    correo = formData.get('correo');
                    nombre = formData.get('nombre');
                    validarCampo(expresiones.usuario, usuario, 'usuario');
                    const vCodigo = validarVacio(codigo);
                    if (vCodigo) {
                        campos.codigo = false;
                    } else {
                        campos.codigo = true;
                    }
                    validarCampo(expresiones.contrasena, contrasena, 'contrasena');
                    if (contrasena === contrasena2) {
                        campos.contrasena2 = true;
                    }
                    const vCorreo = validarVacio(correo);
                    if (vCorreo) {
                        campos.correo = false;
                    } else {
                        validarCampo(expresiones.correo, correo, 'correo');
                    }
                    const vNombre = validarVacio(nombre);
                    if (vNombre) {
                        campos.nombre = false;
                    } else {
                        campos.nombre = true;
                    }
                    if (!campos.usuario || !campos.codigo || !campos.contrasena || !campos.contrasena2 || !campos.correo || !campos.nombre) {
                        if (!campos.usuario) {
                            mensaje += '-Formato de usuario no válido; en el usuario sólo se permite el uso de letras y dígitos (4 a 8 caracteres sin espacios)-'
                        }
                        if (!campos.codigo) {
                            mensaje += '-Si el usuario es montador o cliente es necesario ingresar el código de NAVISION que pertenece al montador o el cliente. Si el usuario es de tipo oficina o administración es necesario crear un código para comenzar a relacionarlo con NAVISION-'
                        }
                        if (!campos.contrasena) {
                            mensaje += '-Formato de contraseña no válido; en la contraseña sólo se permite el uso de letras, dígitos y caracteres especiales (4 a 12 caracteres sin espacios)-'
                        }
                        if (!campos.contrasena2) {
                            mensaje += '-Las contraseñas no coinciden-'
                        }
                        if (!campos.correo) {
                            mensaje += '-El formato de correo no es válido-'
                        }
                        if (!campos.nombre) {
                            mensaje += '-Es necesario ingresar el nombre del usuario para poder identificarlo en la interfaz de la aplicación-'
                        }
                        $textoMensaje.innerHTML = mensaje;
                        mensajeOn();
                        loaderOff();
                    } else {
                        formulario.submit();
                    }
                })
            }
        }
    });

    function toggleElemento(elementId) {
        const elemento = document.getElementById(elementId);
        elemento.classList.toggle('displayOn');
        elemento.classList.toggle('displayOff');
    }

    if (titulo1) {
        document.title = titulo1;
    } else {
        document.title = 'DOS.DOS';
    }

    if (titulo2) {
        $titulo = document.getElementById('titulo');
        $titulo.innerHTML = titulo2;
    } else {
        $titulo = document.getElementById('titulo');
        $titulo.innerHTML = 'DOS.DOS';
    }

    /* LOADER */

    function scrollToTop() {
        // Para navegadores modernos
        document.documentElement.scrollTop = 0;
        // Para navegadores antiguos
        document.body.scrollTop = 0;
    }

    /* LOADER */
    function loaderOn() {
        scrollToTop();
        $loader.classList.remove('displayOff');
        $loader.classList.add('displayOn');
        $body.classList.add('oyh');
    }

    function loaderOff() {
        setTimeout(() => {
            $loader.classList.remove('displayOn');
            $loader.classList.add('displayOff')
            $body.classList.remove('oyh');
        }, 1000)
    }

    /* MENSAJE */

    function mensajeOn() {
        $mensaje.classList.remove('displayOff');
        $mensaje.classList.add('displayOn');
    }

    function mensajeOff() {
        $mensaje.classList.remove('displayOn');
        $mensaje.classList.add('displayOff');
    }

    $imgCerrar.addEventListener('click', e => {
        mensajeOff();
    })

    function alerta(men) {
        $textoMensaje.innerHTML = men;
        mensajeOn();
        loaderOff();
    }

    if (mensajePhp) {
        alerta(mensajePhp);
        scrollToTop();
    }

    /* CERRAR SESIÓN */

    function eliminarCookie(nombre) {
        const tiempoExpiracion = 1; // Segundo 1 de la Época Unix
        document.cookie = `${nombre}=; expires=${new Date(tiempoExpiracion * 1000).toUTCString()}; path=/;`;
    }

    function cerrarSesions() {
        if (navigator.onLine) {
            try {
                loaderOn();
                localStorage.removeItem('login');
                localStorage.removeItem('ruta');
                localStorage.removeItem('mensaje');
                localStorage.removeItem('linea');
                localStorage.removeItem('lineaActividad');
                localStorage.removeItem('ot');
                eliminarCookie('login');
                eliminarCookie('usuario');
                window.location.href = "http://localhost/dosxdos_app/index.html";
            } catch (error) {
                mensaje = 'ERROR: ' + error.message;
                console.error(error);
                alerta(mensaje);
                scrollToTop();
            }
        } else {
            mensaje = 'No es posible cerrar la sesión sin conexión a internet';
            $textoMensaje.innerHTML = mensaje;
            mensajeOn();
        }
    }

    function convertirFecha(fecha) {
        // Separar la fecha y la hora
        const [fechaParte, horaParte] = fecha.split(' ');
        // Separar la fecha en año, mes y día
        const [anio, mes, dia] = fechaParte.split('-');
        // Formatear la fecha al estilo deseado: DD/MM/AAAA HH:MM
        const fechaFormateada = `${dia}/${mes}/${anio}`;
        // Devolver la fecha formateada junto con la hora original
        return `${fechaFormateada} ${horaParte}`;
    }

    const idsGenerados = new Set(); // Utilizamos un conjunto para evitar IDs duplicados

    function generarId(min, max) {
        // Genera un número aleatorio en el rango [min, max]
        const id = Math.floor(Math.random() * (max - min + 1)) + min;

        // Verifica si el ID ya ha sido generado
        if (idsGenerados.has(id)) {
            // Si ya existe, vuelve a generar el ID de forma recursiva
            return generarId(min, max);
        }

        // Agrega el ID generado al conjunto
        idsGenerados.add(id);

        return id;
    }

    function obtenerConfirmacion() {
        return new Promise((resolve, reject) => {
            const respuesta = confirm('¿Estás segur@ que deseas eliminar el archivo del servidor?');
            if (respuesta) {
                resolve(true);
            } else {
                resolve(false);
            }
        });
    }


    /* LOGIN */

    function vLogin() {
        return new Promise((resolve, reject) => {
            const login = localStorage.getItem('login');
            if (login && login !== null) {
                leerDatos('dosxdos', 'usuario')
                    .then(res => {
                        usuario = res[0];
                        resolve(true);
                    })
                    .catch(err => {
                        resolve(false);
                    })
            } else {
                resolve(false);
            }
        })
    }

    async function appOnline() {
        try {
            const login = await vLogin();
            if (!login) {
                window.location.href = "http://localhost/dosxdos_app/index.html";
            } else {
                const Arrayusuario = await leerDatos('dosxdos', 'usuario');
                usuario = Arrayusuario[0];
                $nombreUsuario.innerHTML = usuario.nombre;
                if (usuario.imagen != '0') {
                    $imagenUsuario.src = usuario.imagen;
                }
                if (usuario.clase == 'admon') {
                    $horarios.classList.replace('displayOff', 'displayOn');
                    $archivos.classList.replace('displayOff', 'displayOn');
                    $pv.classList.replace('displayOff', 'displayOn');
                    $usuarios.classList.replace('displayOff', 'displayOn');
                    $rutasIcono.classList.replace('displayOff', 'displayOn');
                    $lineasIcono.classList.replace('displayOff', 'displayOn');
                    $icLineasOt.classList.replace('displayOff', 'displayOn');
                    $dm.classList.replace('displayOff', 'displayOn');
                    $reciclar.classList.replace('displayOff', 'displayOn');
                }
                if (usuario.clase == 'oficina') {
                    $horarios.classList.replace('displayOff', 'displayOn');
                    $pv.classList.replace('displayOff', 'displayOn');
                    const usuariosOficina = document.getElementById('usuariosOficina');
                    usuariosOficina.classList.replace('displayOff', 'displayOn');
                    $archivos.classList.replace('displayOff', 'displayOn');
                    $rutasIcono.classList.replace('displayOff', 'displayOn');
                    $lineasIcono.classList.replace('displayOff', 'displayOn');
                    $icLineasOt.classList.replace('displayOff', 'displayOn');
                }
                if (usuario.clase == 'diseno') {
                    $horarios.classList.replace('displayOff', 'displayOn');
                    $pv.classList.replace('displayOff', 'displayOn');
                    const usuariosOficina = document.getElementById('usuariosOficina');
                    usuariosOficina.classList.replace('displayOff', 'displayOn');
                    $archivos.classList.replace('displayOff', 'displayOn');
                    $rutasIcono.classList.replace('displayOff', 'displayOn');
                    $lineasIcono.classList.replace('displayOff', 'displayOn');
                    $icLineasOt.classList.replace('displayOff', 'displayOn');
                }
                if (usuario.clase == 'estudio') {
                    $horarios.classList.replace('displayOff', 'displayOn');
                    $pv.classList.replace('displayOff', 'displayOn');
                    const usuariosOficina = document.getElementById('usuariosOficina');
                    usuariosOficina.classList.replace('displayOff', 'displayOn');
                    $archivos.classList.replace('displayOff', 'displayOn');
                    $rutasIcono.classList.replace('displayOff', 'displayOn');
                    $lineasIcono.classList.replace('displayOff', 'displayOn');
                    $icLineasOt.classList.replace('displayOff', 'displayOn');
                }
                if (usuario.clase == 'montador') {
                    $horarios.classList.replace('displayOff', 'displayOn');
                    $rutasMontador.classList.replace('displayOff', 'displayOn');
                    $lineasMontador.classList.replace('displayOff', 'displayOn');
                }
                const editarUsuario = document.getElementById('editarUsuario');
                editarUsuario.addEventListener('click', e => {
                    if (navigator.onLine) {
                        window.location.href = "http://localhost/dosxdos_app/dosxdos.php?modulo=editarUsuario&id=" + usuario.id;
                    } else {
                        mensaje = 'No es posible acceder a las opciones de edición de usuario sin conexión a internet';
                        alerta(mensaje);
                        scrollToTop();
                    }
                })
                loaderOff();
            }
        } catch (error) {
            mensaje = 'ERROR: ' + error.message;
            console.error(error);
            alerta(mensaje);
            scrollToTop();
            loaderOff();
        }
    }

    appOnline();

    window.addEventListener('pageshow', (e) => {
        console.log('Carga completa de los elementos de la página no fetch');
        let login = localStorage.getItem('login');
        if (!login || login === null) {
            window.location.href = "http://localhost/dosxdos_app/index.html";
            loaderOff();
        }
        let mensaje = localStorage.getItem('mensaje');
        if (mensaje && mensaje !== null) {
            $textoMensaje.innerHTML = mensaje;
            mensajeOn();
            localStorage.removeItem('mensaje');
        }
    })

    /* TAREAS EN LA INTERMITENCIA DE LA CONEXIÓN */

    window.addEventListener('online', () => {
        console.log('La conexión a Internet se ha recuperado.');
        window.location.reload();
    });

    window.addEventListener('offline', () => {
        console.log('La conexión a Internet se ha perdido.');
    });
</script>

</html>
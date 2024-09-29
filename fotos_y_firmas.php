<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOTOS Y FIRMAS</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="https://dosxdos.app.iidos.com/img/logoPwa256.png">
    <style>
        /* INICIO */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
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
    </style>
</head>

<body id="body" class="">
    <div id="loader" class="displayOn">
        <span class="loader"></span>
    </div>
</body>

<?php
require_once 'config.php';
if (isset($_REQUEST['ruta']) && isset($_REQUEST['linea']) && isset($_REQUEST['ot']) && isset($_REQUEST['lineaActividad'])) {
    $ruta = $conexion->sanitizar($_REQUEST['ruta']);
    $linea = $conexion->sanitizar($_REQUEST['linea']);
    $ot = $conexion->sanitizar($_REQUEST['ot']);
    $lineaActividad = $conexion->sanitizar($_REQUEST['lineaActividad']);
    echo '<script>';
    echo 'localStorage.setItem("rutaArchivos", "' . $ruta . '");';
    echo 'localStorage.setItem("lineaArchivos", "' . $linea . '");';
    echo 'localStorage.setItem("otArchivos", "' . $ot . '");';
    echo 'localStorage.setItem("lineaActividadArchivos", "' . $lineaActividad . '");';
    echo 'window.location.href = "fotos_y_firmas.html";';
    echo '</script>';
} else {
    $mensaje = 'No han sido especificados en las variables del link los datos necesarios';
    echo '<script>';
    echo 'localStorage.setItem("mensaje", "' . $mensaje . '");';
    echo 'window.location.href = "fotos_y_firmas.html";';
    echo '</script>';
}
?>

</html>
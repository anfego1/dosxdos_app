<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="icon" type="image/png" href="http://localhost/dosxdos_app/img/logoPwa256.png">
    <meta name="description" content="DOS.DOS GRUPO IMAGEN - Aplicación Web Progresiva (PWA)">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            letter-spacing: 0.05vw;
        }

        /* ESCRITORIO */
        :root {
            --x20: 20vw;
            --x0_7: 0.7vw;
            --x35: 35vw;
            --x22: 22vw;
            --x3_5: 3.5vw;
            --x1: 1vw;
            --x2: 2vw;
            --x4: 4vw;
            --x1_2: 1.2vw;
            --x2_5: 2.5vw;
            --x0_5: 0.5vw;
            --x0_8: 0.8vw;
            --x0_9: 0.9vw;
            --x0_3: 0.3vw;
            --z2: 2vw;
            --z1: 1vw;
            --x2_3: 2.3vw;
            --x10: 10vw;
            --x3: 3vw;
        }

        /* FUENTES */
        @font-face {
            font-family: 'FuturaBold';
            src: url("http://localhost/dosxdos_app/css/fuentes/Futura/Futura_Bold.otf") format("truetype");
        }

        @font-face {
            font-family: 'FuturaLight';
            src: url("http://localhost/dosxdos_app/css/fuentes/Futura/Futura_Light.otf") format("truetype");
        }

        @font-face {
            font-family: 'FuturaMedium';
            src: url("http://localhost/dosxdos_app/css/fuentes/Futura/Futura_Medium.otf") format("truetype");
        }

        .displayOn {
            display: flex;
        }

        .displayOff {
            display: none;
        }

        .futuraBold {
            font-family: 'FuturaBold';
        }

        .futuraMedium {
            font-family: 'FuturaMedium';
        }

        .futuraLight {
            font-family: 'FuturaLight';
        }

        body {
            display: flex;
            flex-direction: column;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            width: var(--x20);
            opacity: 0;
            transition: opacity 2s;
        }

        .puntoFondo {
            position: absolute;
            border-radius: 50%;
            width: var(--x0_7);
            height: var(--x0_7);
            background-color: #db0032;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            opacity: 0;
            transition: width 1.5s, height 1.5s, opacity 2s;
        }

        .puntoFondo2 {
            width: var(--x35);
            height: var(--x35);
        }

        .saludo {
            z-index: 10;
            width: var(--x22);
            animation: animate 15s linear infinite;
            opacity: 0;
            transition: opacity 2s;
            align-self: center;
            justify-self: center;
        }

        @keyframes animate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .logo2 {
            position: absolute;
            width: var(--x3_5);
            bottom: var(--x1);
            right: var(--x2);
            opacity: 0;
            transition: opacity 2s;
        }

        .logo2:hover {
            cursor: pointer;
        }

        .formulario {
            flex-direction: column;
            width: 70%;
            margin-top: var(--x4);
        }

        label {
            font-family: 'FuturaMedium';
            color: rgb(255, 255, 255);
            font-size: var(--x1_2);
            margin-bottom: var(--x0_5);
        }

        input {
            width: 100%;
            height: var(--x2_5);
            border-radius: var(--x0_8);
            padding: var(--z1);
            font-family: 'FuturaMedium';
            color: #b1b1b1;
            font-size: var(--x0_9);
            border: none;
            outline: none;
        }

        #usuario {
            margin-bottom: var(--z2);
        }

        #contrasena {
            margin-bottom: var(--x0_3);
        }

        #olvidar {
            text-align: end;
            font-family: 'FuturaLight';
            color: rgb(255, 255, 255);
            margin-bottom: var(--z2);
            font-size: var(--x0_8);
        }

        #olvidar:hover {
            cursor: pointer;
        }

        .accion {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40%;
            height: var(--x2_5);
            border-radius: var(--x0_8);
            padding: var(--z1);
            font-family: 'FuturaBold';
            background-color: rgb(255, 255, 255);
            color: #db0032;
            font-size: var(--x0_9);
            border: none;
            align-self: center;
        }

        .accion:hover {
            cursor: pointer;
        }

        #mensaje {
            position: absolute;
            left: 5.5vw;
            top: 250px;
            width: 20vw;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #alerta {
            width: var(--x3);
        }

        #mensajeTexto {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: justify;
            flex-wrap: wrap;
            font-family: 'FuturaMedium';
            color: rgba(128, 128, 128, 0.945);
            font-size: 1vw;
            margin-top: 1.5vw;
        }

        #email {
            margin-bottom: var(--z2);
        }

        #volver {
            margin-top: var(--z1);
        }

        .idiomas {
            position: absolute;
            top: 2.15vw;
            right: 6.5vw;
            font-family: "FuturaBold";
            font-size: var(--x0_7);
            opacity: 0;
            transition: opacity 2s;
        }

        #separador {
            margin-left: 0.2vw;
            margin-right: 0.2vw;
            margin-top: -0.1vw;
        }

        #english:hover {
            cursor: pointer;
        }

        #espanol:hover {
            cursor: pointer;
        }

        .instalar {
            position: absolute;
            width: var(--x2_3);
            top: 1.5vw;
            right: 2vw;
            flex-direction: column;
            opacity: 0;
            transition: opacity 2s;
        }

        .instalar>img {
            width: var(--x2_3);
        }

        .instalar>img:hover {
            cursor: pointer;
        }

        #tipInstalar {
            width: var(--x10);
            margin-top: 0.1vw;
            border-radius: 0.7vw;
            background-color: #e5e5e5;
            color: #999999;
            padding: 0.5vw;
            font-family: 'FuturaLight';
            font-size: var(--x0_9);
            flex-wrap: wrap;
            align-self: flex-end;
            justify-content: center;
            text-align: center;
        }

        #contrasena {
            margin-bottom: var(--z2);
        }

        #contrasena2 {
            margin-bottom: var(--x0_3);
        }

        #reestablecer {
            margin-top: var(--z2);
        }

        .accion2 {
            justify-content: center;
            align-items: center;
            width: 40%;
            height: var(--x2_5);
            border-radius: var(--x0_8);
            padding: var(--z1);
            font-family: 'FuturaBold';
            background-color: rgb(255, 255, 255);
            color: #db0032;
            font-size: var(--x0_9);
            border: none;
            align-self: center;
        }

        .accion2:hover {
            cursor: pointer;
        }

        .noVisible {
            opacity: 0;
            transition: opacity 2s;
        }

        .visible {
            opacity: 1;
        }

        /* TABLETS = ROOT + 60% */
        @media screen and (min-width: 540px) and (max-width: 1200px) {
            :root {
                --x20: 32vw;
                --x0_7: 1.12vw;
                --x35: 56vw;
                --x22: 35.2vw;
                --x3_5: 5.6vw;
                --x4: 6.4vw;
                --x1_2: 1.92vw;
                --x2_5: 4vw;
                --x0_5: 0.8vw;
                --x0_8: 1.28vw;
                --x0_9: 1.44vw;
                --x0_3: 0.48vw;
                --z2: 3.2vw;
                --z1: 1.6vw;
                --x2_3: 3.68vw;
                --x10: 16vw;
                --x3: 4.8vw;
            }

            .idiomas {
                top: 2.6vw;
                right: 7.5vw;
            }

            #mensaje {
                top: 10px;
                left: 2vw;
            }

            #mensajeTexto {
                font-size: 1.3vw;
            }
        }

        /* MOVILES = ROOT + 172% */
        @media screen and (max-width: 539px) {
            :root {
                --x20: 54.4vw;
                --x0_7: 1.9vw;
                --x35: 95.2vw;
                --x22: 59.84vw;
                --x3_5: 9.52vw;
                --x4: 10.88vw;
                --x1_2: 3.26vw;
                --x2_5: 4vw;
                --x0_5: 1.36vw;
                --x0_8: 2.17vw;
                --x0_9: 2.44vw;
                --x0_3: 0.81vw;
                --z2: 5.44vw;
                --z1: 2.72vw;
                --x2_3: 6.25vw;
                --x10: 27.2vw;
                --x3: 8.16vw;
            }

            .idiomas {
                top: 3.5vw;
                right: 12vw;
            }

            #mensaje {
                top: 50px;
                width: 60vw;
                left: 20vw;
            }

            #mensajeTexto {
                font-size: 2.5vw;
            }
        }
    </style>
    <!-- PWA -->
    <meta name="theme-color" content="#000000">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="http://localhost/dosxdos_app/img/dosxdos.png">
    <link rel="apple-touch-startup-image" href="http://localhost/dosxdos_app/img/dosxdos.png">
    <link rel="manifest" href="http://localhost/dosxdos_app/manifest.json">
    <script src="http://localhost/dosxdos_app/js/index_db.js"></script>
    <!-- INICIO BASE DE DATOS DEL CLIENTE - INDEXDB -->
    <script>
        const stores = [
            'usuario',
            'rutas',
            'lineas',
            'peticionesLineas',
            'peticionesFotos',
            'peticionesFirmas',
        ]
        db('dosxdos', stores)
            .then(res => {
                if (res) {
                    console.log('Base de datos abierta exitosamente')
                }
            })
            .catch(err => {
                console.log(err)
            })
    </script>
    <!-- SERVICEWORKER -->
    <script src="http://localhost/dosxdos_app/serviceworker.js"></script>
    <script src="http://localhost/dosxdos_app//sw.js"></script>
    <?php

    if (isset($_GET['id']) && isset($_GET['usuario']) && isset($_GET['cod']) && isset($_GET['clave'])) {
        $id = $_GET['id'];
        $usuario = $_GET['usuario'];
        $cod = $_GET['cod'];
        $clave = $_GET['clave'];
        if ($clave == 1987082120200804) {
    ?>
            <script>
                reestablecer = true;
                console.log('Reestablecer: ' + reestablecer);
            </script>
        <?php
            $claveNueva = 2020080419870821;
        } else {
        ?>
            <script>
                reestablecer = false;
                console.log('Reestablecer: ' + reestablecer);
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            reestablecer = false;
            console.log('Reestablecer: ' + reestablecer);
        </script>
    <?php
    }

    ?>

</head>

<body>

    <div id="mensaje" class="displayOff">
        <img id="alerta" src="http://localhost/dosxdos_app/img/alerta.png">
        <p id="mensajeTexto"></p>
    </div>

    <div class="puntoFondo displayOn" id="punto">
        <img src="http://localhost/dosxdos_app/img/saludo.png" class="saludo displayOn" id="saludo">
        <form action="restablecer_contrasena.php" method="post" id="formularioReestablecer" class="displayOff formulario noVisible">
            <input type="hidden" name="reestablecer" value="1">
            <input type="hidden" name="id" value="<?php print $id ?>">
            <input type="hidden" name="usuario" value="<?php print $usuario ?>">
            <input type="hidden" name="cod" value="<?php print $cod ?>">
            <input type="hidden" name="clave" value="<?php print $claveNueva ?>">
            <input type="hidden" name="idioma" id="inputIdioma" value="">
            <label for="contrasena" id="labelContrasena"></label>
            <input type="password" name="contrasena" id="contrasena" maxlength="12">
            <label for="contrasena2" id="labelContrasena2"></label>
            <input type="password" name="contrasena2" id="contrasena2" maxlength="12">
            <button type="submit" id="reestablecer" class="displayOn accion2"></button>
        </form>
    </div>

    <img src="http://localhost/dosxdos_app/img/logo2930.png" class="logo displayOn" id="logo">

    <img src="http://localhost/dosxdos_app/img/logo_clientes.png" id="logo2" class="logo2">

    <div id="idiomas" class="idiomas displayOn">
        <p id="espanol">ESP</p>
        <p id="separador">|</p>
        <p id="english">ENG</p>
    </div>

    <div id="instalar" class="instalar displayOn">
        <img src="http://localhost/dosxdos_app/img/instalar.png" id="instalarImg">
        <p id="tipInstalar" class="displayOff"></p>
    </div>

</body>

<?php

require_once './apirest/clases/conexion_clase.php';

$id = '';
$usuario = '';
$cod = '';
$clave = '';
$claveNueva = 0;

if (isset($_POST['reestablecer'])) {
    if (isset($_POST['id']) && isset($_POST['usuario']) && isset($_POST['cod']) && isset($_POST['clave']) && isset($_POST['idioma']) && isset($_POST['contrasena']) && isset($_POST['contrasena2'])) {

        $id = $_POST['id'];
        $usuario = $_POST['usuario'];
        $cod = $_POST['cod'];
        $clave = $_POST['clave'];
        $contrasena = $_POST['contrasena'];
        $contrasena2 = $_POST['contrasena2'];
        $idioma = $_POST['idioma'];
        $link = 'http://localhost/dosxdos_app/restablecer_contrasena.php?id=' . $id . '&usuario=' .  $usuario . '&cod=' . $cod . '&clave=1987082120200804';
        if ($clave != 2020080419870821) {
?>
            <script>
                idioma = <?php print("'". $idioma . "'") ?>;
                mensaj = '';
                if (idioma == 'es') {
                    mensaj = 'Error: La constante de clave de restablecimiento no es correcta';
                } else {
                    mensaj = 'Error: Reset key constant is not correct';
                }
                localStorage.setItem('mensaje', mensaj);
                window.location.href = "<?php print $link ?>";
            </script>
        <?php
        }
        if ($contrasena != $contrasena2) {
        ?>
            <script>
                idioma = <?php print("'". $idioma . "'") ?>;
                mensaj = '';
                if (idioma == 'es') {
                    mensaj = 'Error: Las contraseñas no coinciden';
                } else {
                    mensaj = 'Error: Passwords do not match';
                }
                localStorage.setItem('mensaje', mensaj);
                window.location.href = "<?php print $link ?>";
            </script>
        <?php
        }
        $query = "UPDATE usuarios SET contrasena=\"$contrasena\" WHERE id=\"$id\" AND usuario=\"$usuario\" AND cod=\"$cod\"";
        $conexion = new Conexion;
        if ($conexion->errno) {
        ?>
            <script>
                idioma = <?php print("'". $idioma . "'") ?>;
                mensaj = '';
                if (idioma == 'es') {
                    mensaj = 'Error en la conexión con la base de datos: ' + <?php print $conexion->error ?>;
                } else {
                    mensaj = 'Database connection error: ' + <?php print $conexion->error ?>;
                }
                localStorage.setItem('mensaje', mensaj);
                window.location.href = "<?php print $link ?>";
            </script>
        <?php
        }
        $result = $conexion->datos($query);
        if (!$result) {
        ?>
            <script>
                idioma = <?php print("'". $idioma . "'") ?>;
                mensaj = '';
                if (idioma == 'es') {
                    mensaj = 'Error en el servidor, la contraseña no ha sido restablecida: ' + <?php print $conexion->error ?>;
                } else {
                    mensaj = 'Server error, password has not been reset: ' + <?php print $conexion->error ?>;
                }
                localStorage.setItem('mensaje', mensaj);
                window.location.href = "<?php print $link ?>";
            </script>
        <?php
        } else {
        ?>
            <script>
                idioma = <?php print("'". $idioma . "'") ?>;
                mensaj = '';
                if (idioma == 'es') {
                    mensaj = 'Tu contraseña ha sido restablecida exitosamente';
                } else {
                    mensaj = 'Your password has been successfully reset';
                }
                localStorage.setItem('mensaje', mensaj);
                window.location.href = "http://localhost/dosxdos_app/index.html";
            </script>
        <?php
        }
    } else {
        ?>
        <script>
            mensaj = 'Error: No han sido enviadas todas las variables necesarias para el restablecimiento - Error: Not all variables necessary for the reset have been sent';
            localStorage.setItem('mensaje', mensaj);
            window.location.href = "<?php print $link ?>";
        </script>
<?php
    }
}

?>

<script>
    idioma = '';
    usuario = '';
    jsonIdioma = '';

    /* EVITAR REENVÍO DE FORMULARIOS */
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    function mensaje(men) {
        const $mensaje = document.getElementById('mensaje');
        const $mensajeTexto = document.getElementById('mensajeTexto');
        $mensajeTexto.innerHTML = men;
        $mensaje.classList.remove('displayOff');
        $mensaje.classList.add('displayOn');
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
    }

    function espanol() {
        return new Promise((resolve, reject) => {
            fetch('http://localhost/dosxdos_app/espanol.json')
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`Error en la solicitud del json en español: ${res.status}`);
                    }
                    return res.json();
                })
                .then(idiom => {
                    resolve(idiom);
                })
                .catch((error) => {
                    console.error(error.massage);
                    console.error(error);
                    reject(error);
                })
        })
    }

    function english() {
        return new Promise((resolve, reject) => {
            fetch('http://localhost/dosxdos_app/english.json')
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`Error in the json request in English: ${res.status}`);
                    }
                    return res.json();
                })
                .then(idiom => {
                    resolve(idiom);
                })
                .catch((error) => {
                    console.error(error.massage);
                    console.error(error);
                    reject(error);
                })
        })
    }

    function cargarDatoTexto(id, valor) {
        const elemento = document.getElementById(id);
        temporalDiv = document.createElement("div");
        temporalDiv.innerHTML = valor;
        textoConvertido = temporalDiv.textContent || temporalDiv.innerText;
        elemento.innerHTML = textoConvertido;
    }

    async function cargarIdioma(idm) {
        try {
            if (navigator.onLine) {
                if (idm == 'es') {
                    idioma = idm;
                    jsonIdioma = await espanol();
                    document.title = jsonIdioma.tittle2;
                    cargarDatoTexto('labelContrasena', jsonIdioma.reContrasena);
                    cargarDatoTexto('labelContrasena2', jsonIdioma.reContrasena2);
                    cargarDatoTexto('reestablecer', jsonIdioma.reestablecer);
                    cargarDatoTexto('tipInstalar', jsonIdioma.instalar);
                    localStorage.setItem('idioma', idioma);
                    const inputIdioma = document.getElementById('inputIdioma');
                    inputIdioma.value = idioma;

                } else {
                    idioma = idm;
                    jsonIdioma = await english();
                    document.title = jsonIdioma.tittle2;
                    cargarDatoTexto('labelContrasena', jsonIdioma.reContrasena);
                    cargarDatoTexto('labelContrasena2', jsonIdioma.reContrasena2);
                    cargarDatoTexto('reestablecer', jsonIdioma.reestablecer);
                    cargarDatoTexto('tipInstalar', jsonIdioma.instalar);
                    localStorage.setItem('idioma', idioma);
                    const inputIdioma = document.getElementById('inputIdioma');
                    inputIdioma.value = idioma;
                }
            } else {
                if (idm == 'es') {
                    mensaje('Sin conexión a internet no es posible realizar esta acción');
                } else {
                    mensaje('Without an internet connection it is not possible to perform this action');
                }
            }
        } catch (error) {
            console.error(error);
        }
    }

    /* VALIDACIÓN DE FORMULARIOS */
    /* Expresiones regulares */
    const expresiones = {
            contrasena: /^(?:[a-zA-Z\d!@#$%^&*()-+=<>?/\|[\]{}:;,.]+){4,12}$/,
        },

        /* Campos */
        campos = {
            contrasena: false,
            contrasena2: false
        };

    /* Función VALIDAR CAMPO */
    const validarCampo = (expresion, input, campo) => {
        if (expresion.test(input)) {
            campos[campo] = true;
        } else {
            campos[campo] = false;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', e => {
            const formularioLogin = document.getElementById('formularioLogin');
            const formularioRecordar = document.getElementById('formularioRecordar');
            if (e.target.id == 'espanol') {
                cargarIdioma('es');
            } else if (e.target.id == 'english') {
                cargarIdioma('en');
            } else if (e.target.id == 'logo2') {
                window.location.href = "http://localhost/dosxdos_app/index.html";
            }
        })
        const $instalar = document.getElementById('instalar');
        const $tipInstalar = document.getElementById('tipInstalar');
        $instalar.addEventListener('mouseover', () => {
            $tipInstalar.classList.replace('displayOff', 'displayOn');
        });
        $instalar.addEventListener('mouseout', () => {
            $tipInstalar.classList.replace('displayOn', 'displayOff');
        });
    })

    function inicio() {
        try {
            const logo = document.getElementById('logo');
            const punto = document.getElementById('punto');
            const saludo = document.getElementById('saludo');
            const logo2 = document.getElementById('logo2');
            const formularioLogin = document.getElementById('formularioReestablecer');
            const idiomas = document.getElementById('idiomas');
            const instalar = document.getElementById('instalar');
            setTimeout(() => {
                logo.classList.add('visible');
                punto.classList.add('visible');
                setTimeout(() => {
                    punto.classList.add('puntoFondo2');
                    setTimeout(() => {
                        saludo.classList.add('visible');
                        logo.classList.remove('displayOn');
                        logo.classList.add('displayOff');
                        setTimeout(() => {
                            saludo.classList.remove('visible');
                            setTimeout(() => {
                                saludo.classList.replace('displayOn', 'displayOff');
                                formularioLogin.classList.replace('displayOff', 'displayOn');
                                setTimeout(() => {
                                    formularioLogin.classList.add('visible');
                                    logo2.classList.add('visible');
                                    idiomas.classList.add('visible');
                                    instalar.classList.add('visible');
                                }, 200);
                            }, 1000);
                        }, 2000);
                    }, 1500);
                }, 1000);
            }, 500);
        } catch (error) {
            console.error(error);
        }
    }

    function inicio2() {
        return new Promise((resolve, reject) => {
            try {
                const logo = document.getElementById('logo');
                const punto = document.getElementById('punto');
                const saludo = document.getElementById('saludo');
                const logo2 = document.getElementById('logo2');
                const formularioLogin = document.getElementById('formularioReestablecer');
                const idiomas = document.getElementById('idiomas');
                const instalar = document.getElementById('instalar');
                const mensaje = document.getElementById('mensaje');
                formularioLogin.classList.replace('displayOn', 'displayOff');
                idiomas.classList.replace('displayOn', 'displayOff');
                instalar.classList.replace('displayOn', 'displayOff');
                setTimeout(() => {
                    logo.classList.add('visible');
                    punto.classList.add('visible');
                    setTimeout(() => {
                        punto.classList.add('puntoFondo2');
                        setTimeout(() => {
                            saludo.classList.add('visible');
                            logo.classList.remove('displayOn');
                            logo.classList.add('displayOff');
                            setTimeout(() => {
                                saludo.classList.remove('visible');
                                setTimeout(() => {
                                    saludo.classList.replace('displayOn', 'displayOff');
                                    resolve(true);
                                }, 1000);
                            }, 2000);
                        }, 1500);
                    }, 1000);
                }, 500);
            } catch (error) {
                console.error(error);
                reject(error);
            }
        })
    }

    /* PAGESHOW */

    window.addEventListener('pageshow', (e) => {
        let mensaj = localStorage.getItem('mensaje');
        if (mensaj && mensaj !== null) {
            mensaje(mensaj);
            localStorage.removeItem('mensaje');
        }
    })

    async function appOnline() {
        try {
            if (!reestablecer) {
                await inicio2();
                idioma = localStorage.getItem('idioma');
                if (idioma && idioma !== null) {
                    const htmlElement = document.querySelector('html');
                    await cargarIdioma(idioma);
                    if (htmlElement) {
                        htmlElement.lang = idioma;
                    }
                } else {
                    const idiomaNavegador = navigator.language || navigator.userLanguage;
                    idioma = idiomaNavegador.split('-')[0];
                    const htmlElement = document.querySelector('html');
                    if (htmlElement) {
                        htmlElement.lang = idioma;
                    }
                }
                if (idioma == 'es') {
                    mensaj = 'El link proporcionado no posee todos los datos y las variables necesarias para realizar el restablecimiento de la contrasena'
                    mensaje(mensaj);
                } else {
                    mensaj = 'The link provided does not have all the data and variables necessary to perform the password reset'
                    mensaje(mensaj);
                }
            } else {
                inicio();
                idioma = localStorage.getItem('idioma');
                if (idioma && idioma !== null) {
                    const htmlElement = document.querySelector('html');
                    await cargarIdioma(idioma);
                    if (htmlElement) {
                        htmlElement.lang = idioma;
                    }
                } else {
                    const idiomaNavegador = navigator.language || navigator.userLanguage;
                    idioma = idiomaNavegador.split('-')[0];
                    const htmlElement = document.querySelector('html');
                    if (htmlElement) {
                        htmlElement.lang = idioma;
                    }
                    await cargarIdioma(idioma);
                }
                if ('standalone' in window.navigator && window.navigator.standalone) {
                    const instalarApp = document.getElementById('instalar');
                    instalarApp.classList.replace('displayOn', 'displayOff');
                }
            }
        } catch (error) {
            console.error(error);
        }
    }

    async function appOffline() {
        try {
            await inicio2();
            idioma = localStorage.getItem('idioma');
            if (idioma && idioma !== null) {
                const htmlElement = document.querySelector('html');
                if (htmlElement) {
                    htmlElement.lang = idioma;
                }
            } else {
                const idiomaNavegador = navigator.language || navigator.userLanguage;
                idioma = idiomaNavegador.split('-')[0];
                const htmlElement = document.querySelector('html');
                if (htmlElement) {
                    htmlElement.lang = idioma;
                }
            }
            if (idioma == 'es') {
                mensaje('Sin conexión a internet no es posible reestablecer la contraseña');
            } else {
                mensaje('Without an internet connection it is not possible to reset the password')
            }
        } catch (error) {
            console.error(error);
        }
    }

    /* TAREAS AL INICIAR LA PANTALLA CON EL ESTADO DE LA CONEXIÓN */
    if (navigator.onLine) {
        console.log('La aplicación está en línea.');
        appOnline();
    } else {
        console.log('La aplicación está fuera de línea.');
        appOffline();
    }

    /* TAREAS EN LA INTERMITENCIA DE LA CONEXIÓN */
    window.addEventListener('online', () => {
        location.reload();
    });
    window.addEventListener('offline', () => {
        console.log('La conexión a Internet se ha perdido.');
    });

    //SUBMIT
    document.addEventListener("submit", (e) => {
        if (e.target.matches('#formularioReestablecer')) {
            e.preventDefault();
            let mensaj = '';
            let formData = new FormData(e.target);
            window.scrollTo(0, 0);
            const contrasena = formData.get('contrasena');
            const contrasena2 = formData.get('contrasena2');
            validarCampo(expresiones.contrasena, contrasena, 'contrasena');
            if (!campos.contrasena) {
                e.target.reset();
                if (idioma == 'es') {
                    mensaj += 'Formato de contraseña no válido; en la contraseña sólo se permite el uso de letras, dígitos y caracteres especiales (4 a 12 caracteres sin espacios).';
                } else {
                    mensaj += 'Invalid password format; Only letters, digits and special characters (4 to 12 characters without spaces) are allowed in the password.';
                }
                mensaje(mensaj);
            } else {
                if (contrasena != contrasena2) {
                    e.target.reset();
                    if (idioma == 'es') {
                        mensaj += 'Las contraseñas no coinciden';
                    } else {
                        mensaj += 'Passwords do not match';
                    }
                    mensaje(mensaj);
                } else {
                    e.target.submit();
                }
            }
        }
    })
</script>

</html>
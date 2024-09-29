<?php

if (!isset($_COOKIE['login'])) {
    header("location: index.html");
}

if ((isset($_REQUEST["crearUsuario"]))) {
    require_once "config.php";
    if (!$conexion) {
        header("location: dosxdos.php?modulo=crearUsuario&mensaje=Error de conexión a la base de datos: $conexion->error");
    }
    $usuario = $conexion->sanitizar($_REQUEST["usuario"]);
    $clase = $conexion->sanitizar($_REQUEST["clase"]);
    $cod = $conexion->sanitizar($_REQUEST["cod"]);
    /*$clave = md5($clave);*/
    $contrasena = $conexion->sanitizar($_REQUEST["contrasena"]);
    $correo = $conexion->sanitizar($_REQUEST["correo"]);
    $movil = $conexion->sanitizar($_REQUEST["movil"]);
    $nombre = $conexion->sanitizar($_REQUEST["nombre"]);
    $apellido = $conexion->sanitizar($_REQUEST["apellido"]);
    $nombreImagen;
    if ($_FILES["imagen"]["name"][0]) {
        $tipo = explode('/', $conexion->sanitizar($_FILES["imagen"]["type"][0]))[0];
        $extension = explode('/', $conexion->sanitizar($_FILES["imagen"]["type"][0]))[1];
    }
    ($_FILES["imagen"]["name"][0]) ? $imagen = uniqid() . '_' . $tipo . '_' . $extension . '_' . $conexion->sanitizar($_FILES["imagen"]["name"][0]) : $nombreImagen = 0;
    if ($_FILES["imagen"]["name"][0]) {
        $nombreImagen = 'img_usuarios/' . $imagen;
    }
    $query = "INSERT INTO usuarios (usuario, cod, contrasena, clase, correo, movil, nombre, apellido, imagen) VALUES (\"$usuario\", \"$cod\", \"$contrasena\", \"$clase\", \"$correo\", \"$movil\", \"$nombre\", \"$apellido\", \"$nombreImagen\")";
    $result = $conexion->datosPost($query);
    if ($result) {
        if ($imagen) {

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"][0], "img_usuarios/" . $imagen)) {
                header("location: dosxdos.php?modulo=crearUsuario&mensaje=El usuario se ha creado exitosamente");
            } else {
                header("location: dosxdos.php?modulo=crearUsuario&mensaje=El usuario se ha creado exitosamente, pero no ha sido posible guardar la imagen");
            }
        } else {
            header("location: dosxdos.php?modulo=crearUsuario&mensaje=El usuario se ha creado exitosamente");
        }
    } else {
        $error = $conexion->error;
        header("location: dosxdos.php?modulo=crearUsuario&mensaje=Error de base de datos, el usuario no ha sido creado. Por favor verifica si el usuario ya existe - $error");
    }
}

?>

<section id="contenido" class="displayOn">

    <nav id="opcionesMenu2" class="displayOn">

        <div class="opcionMenu displayOn" id="crearUsuario">
            <a href="https://dosxdos.app.iidos.com/dosxdos.php?modulo=usuarios" class="enlaceBoton">
                <div class="opcionMenu" id="lineasIcono">
                    <button class="botonIcono" type="button" id="rutasIconoBoton">
                        <img src="https://dosxdos.app.iidos.com/img/back.png">
                    </button>
                </div>
            </a>
            <p class='bolder'>Volver</p>
        </div>

    </nav>

    <form action="crear_usuario.php" method="post" id="crearUsuarioFormulario" enctype="multipart/form-data" class="row g-4" novalidate>

        <!-- Clase -->
        <div class="cInput">
            <label for="clase" class="requerido">CLASE:</label>
            <select id="clase" name="clase">
                <option value="montador" selected>Montador</option>
                <option value="oficina">Oficina</option>
                <option value="cliente">Cliente</option>
                <option value="admon">Administrador</option>
                <option value="diseno">Diseño</option>
                <option value="estudio">Estudio</option>
            </select>
        </div>

        <!-- Usuario -->
        <div class="cInput">
            <label for="usuario" class="requerido">USUARIO:</label>
            <input type="text" name="usuario" id="usuario" maxlength="8">
        </div>

        <!-- Código -->
        <div class="cInput">
            <label for="cod" class="requerido">CÓDIGO:</label>
            <input type="text" name="cod" id="cod">
        </div>

        <!-- Contraseña -->
        <div class="cInput">
            <label for="contrasena" class="requerido">CONTRASEÑA:</label>
            <input type="password" name="contrasena" id="contrasena" maxlength="12">
        </div>

        <!-- Contraseña2 -->
        <div class="cInput">
            <label for="contrasena2" class="requerido">REPETIR CONTRASEÑA:</label>
            <input type="password" name="contrasena2" id="contrasena2" maxlength="12">
        </div>

        <!-- Correo -->
        <div class="cInput requerido">
            <label for="correo">CORREO:</label>
            <input type="mail" name="correo" id="correo">
        </div>

        <!-- Móvil -->
        <div class="cInput">
            <label for="movil">MÓVIL:</label>
            <input type="number" name="movil" id="movil">
        </div>

        <!-- Nombre -->
        <div class="cInput" class="requerido">
            <label for="nombre" class="requerido">NOMBRE:</label>
            <input type="text" name="nombre" id="nombre">
        </div>

        <!-- Apellido -->
        <div class="cInput">
            <label for="apellido">APELLIDO:</label>
            <input type="text" name="apellido" id="apellido">
        </div>

        <!-- Imagen -->
        <div class="cInput">
            <label for="apellido">IMAGEN:</label>
            <input type="file" name="imagen[]" accept="image/*" id="imagen">
        </div>

        <div id="CajaimagenPerfil"><img src="https://dosxdos.app.iidos.com/img/usuario.png" id="imagenPerfil"></div>

        <input type="hidden" name="crearUsuario" value="1">

        <button type="button" id="enviar" class="ruta">CREAR USUARIO</button>
        
        <a href="https://dosxdos.app.iidos.com/dosxdos.php?modulo=usuarios" id="cancelar"><button type="button" class="ruta">CANCELAR</button></a>

    </form>

</section>

<script>
    titulo1 = <?php echo ("'" . $nombre . '_' . 'CREAR USUARIO' . "'") ?>;
    titulo2 = 'CREAR USUARIO';
    /* CAMBIO DE IMAGEN */
    const $imagen = document.getElementById('imagen'),
        $imagenPerfil = document.getElementById('imagenPerfil');
    const cambioImagen = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
    $imagen.addEventListener('change', (e) => {
        archivo = e.target.files[0];
        if (archivo) {
            cambioImagen(archivo)
                .then(file => {
                    $imagenPerfil.src = file;
                })
                .catch(error => {
                    console.log(error);
                    $imagenPerfil.src = "";
                    $imagenPerfil.setAttribute('alt', 'Error')
                })
        }
    })
</script>
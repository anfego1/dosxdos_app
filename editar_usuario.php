<?php

if (!isset($_COOKIE['login'])) {
    header("location: index.html");
}

$usuarioEditado = false;
$mensajeUsuarioEditado = '';

if ((isset($_REQUEST["editarUsuario"]))) {
    require_once "config.php";
    if (!$conexion) {
        header("location: dosxdos.php?modulo=editarUsuario&mensaje=Error de conexión a la base de datos: $conexion->error&id=$id");
    }
    $idUsuario = $_COOKIE['usuario'];
    $id = $conexion->sanitizar($_REQUEST["id"]);
    $usuarioActual = $conexion->sanitizar($_REQUEST["usuarioActual"]);
    $usuario = $conexion->sanitizar($_REQUEST["usuario"]);
    $clase = $conexion->sanitizar($_REQUEST["clase"]);
    $cod = $conexion->sanitizar($_REQUEST["cod"]);
    /*$clave = md5($clave);*/
    $contrasena = $conexion->sanitizar($_REQUEST["contrasena"]);
    $correo = $conexion->sanitizar($_REQUEST["correo"]);
    $movil = $conexion->sanitizar($_REQUEST["movil"]);
    $nombre = $conexion->sanitizar($_REQUEST["nombre"]);
    $apellido = $conexion->sanitizar($_REQUEST["apellido"]);
    $imagenActual = $conexion->sanitizar($_REQUEST["imagenActual"]);
    $activo = $conexion->sanitizar($_REQUEST["activo"]);
    $nombreImagen;
    ($_FILES["imagen"]["name"][0]) ? $imagen = uniqid() . '_' . $tipo . '_' . $extension . '_' . $conexion->sanitizar($_FILES["imagen"]["name"][0]) : $nombreImagen = $imagenActual;
    if ($_FILES["imagen"]["name"][0]) {
        $tipo = explode('/', $conexion->sanitizar($_FILES["imagen"]["type"][0]))[0];
        $extension = explode('/', $conexion->sanitizar($_FILES["imagen"]["type"][0]))[1];
    }
    if ($_FILES["imagen"]["name"][0]) {
        $nombreImagen = 'img_usuarios/' . $imagen;
    }
    if ($usuario == $usuarioActual) {
        $query = "UPDATE usuarios SET cod=\"$cod\", contrasena=\"$contrasena\", clase=\"$clase\", correo=\"$correo\", movil=\"$movil\", nombre=\"$nombre\", apellido=\"$apellido\", imagen=\"$nombreImagen\", activo=\"$activo\" WHERE id=\"$id\"";
    } else {
        $query = "UPDATE usuarios SET usuario=\"$usuario\", cod=\"$cod\", contrasena=\"$contrasena\", clase=\"$clase\", correo=\"$correo\", movil=\"$movil\", nombre=\"$nombre\", apellido=\"$apellido\", imagen=\"$nombreImagen\", activo=\"$activo\" WHERE id=\"$id\"";
    }
    $result = $conexion->datos($query);
    if ($result) {
        if ($imagen) {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"][0], "img_usuarios/" . $imagen)) {
                unlink($imagenActual);
                if ($idUsuario == $id) {
                    $usuarioEditado = true;
                    $mensajeUsuarioEditado = 'Tu usuario ha sido editado exitosamente';
                } else {
                    header("location: dosxdos.php?modulo=editarUsuario&mensaje=El usuario ha sido editado exitosamente&id=$id");
                }
            } else {
                if ($idUsuario == $id) {
                    $usuarioEditado = true;
                    $mensajeUsuarioEditado = 'Tu usuario ha sido editado exitosamente, pero no ha sido posible guardar la imagen';
                } else {
                    header("location: dosxdos.php?modulo=editarUsuario&mensaje=El usuario se ha editado exitosamente, pero no ha sido posible guardar la imagen&id=$id");
                }
            }
        } else {
            if ($idUsuario == $id) {
                $usuarioEditado = true;
                $mensajeUsuarioEditado = 'Tu usuario ha sido editado exitosamente';
            } else {
                header("location: dosxdos.php?modulo=editarUsuario&mensaje=El usuario se ha editado exitosamente&id=$id");
            }
        }
    } else {
        $error = $conexion->error;
        header("location: dosxdos.php?modulo=editarUsuario&mensaje=Error de base de datos, el usuario no ha sido editado. Por favor verifica si el usuario ya existe - $error&id=$id");
    }
}

if ((isset($_REQUEST["eliminar"]))) {
    require_once "config.php";
    if (!$conexion) {
        header("location: dosxdos.php?modulo=editarUsuario&mensaje=Error de conexión a la base de datos: $conexion->error&id=$id");
    }
    $id = $conexion->sanitizar($_REQUEST["id"]);
    $accion = 1;
    $query = "UPDATE usuarios SET eliminado=\"$accion\" WHERE id=\"$id\"";
    $result = $conexion->datos($query);
    if ($result) {
        header("location: dosxdos.php?modulo=usuarios&mensaje=El usuario ha sido eliminado");
    } else {
        $error = $conexion->error;
        header("location: dosxdos.php?modulo=usuarios&mensaje=Error de base de datos, el usuario no ha sido eliminado - $error");
    }
}

$query = "SELECT * FROM usuarios WHERE id = $id";
$result = $conexion->datos($query);
if ($result) {
    $row = $result->fetch_assoc();
    $usuarioE = $row['usuario'];
    $codE = $row['cod'];
    $contrasenaE = $row['contrasena'];
    $claseE = $row['clase'];
    $correoE = $row['correo'];
    $movilE = $row['movil'];
    $nombreE = $row['nombre'];
    $apellidoE = $row['apellido'];
    $imagenE = $row['imagen'];
    $activoE = $row['activo'];
} else {
    $mensaje .= '--Error en la conexión con la base de datos para la obtención de la información del usuario: ' . $conexion->error . '--';
    header("location: dosxdos.php?modulo=editarUsuario&mensaje=$mensaje");
}

if ($idUsuario == $id && $usuarioEditado) {
?>
    <script>
        mensaje = <?php echo ('"' . $mensajeUsuarioEditado . '"') ?>;
        nuevoUsuario = {
            id: <?php if ($idUsuario) {
                    echo ('"' . $idUsuario . '"');
                } else {
                    echo ('"' . '' . '"');
                } ?>,
            usuario: <?php if ($usuario) {
                            echo ('"' . $usuario . '"');
                        } else {
                            echo ('"' . '' . '"');
                        } ?>,
            cod: <?php if ($cod) {
                        echo ('"' . $cod . '"');
                    } else {
                        echo ('"' . '' . '"');
                    } ?>,
            contrasena: <?php if ($contrasena) {
                            echo ('"' . $contrasena . '"');
                        } else {
                            echo ('"' . '' . '"');
                        } ?>,
            clase: <?php if ($clase) {
                        echo ('"' . $clase . '"');
                    } else {
                        echo ('"' . '' . '"');
                    } ?>,
            correo: <?php if ($correo) {
                        echo ('"' . $correo . '"');
                    } else {
                        echo ('"' . '' . '"');
                    } ?>,
            movil: <?php if ($movil) {
                        echo ('"' . $movil . '"');
                    } else {
                        echo ('"' . '' . '"');
                    } ?>,
            nombre: <?php if ($nombre) {
                        echo ('"' . $nombre . '"');
                    } else {
                        echo ('"' . '' . '"');
                    } ?>,
            apellido: <?php if ($apellido) {
                            echo ('"' . $apellido . '"');
                        } else {
                            echo ('"' . '' . '"');
                        } ?>,
            imagen: <?php
                    echo ('"' . $nombreImagen . '"');
                    ?>,
            activo: <?php if ($activo) {
                        echo ('"' . $activo . '"');
                    } else {
                        echo ('"' . '' . '"');
                    } ?>,
        }

        function agregarDato(database, store, data) {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open(database);
                request.onsuccess = (event) => {
                    const db = event.target.result;
                    const transaction = db.transaction(store, 'readwrite');
                    const datosStore = transaction.objectStore(store);
                    const clearRequest = datosStore.clear();
                    clearRequest.onsuccess = (clearEvent) => {
                        const requestAgregar = datosStore.add(data);
                        requestAgregar.onsuccess = (event) => {
                            resolve(true);
                        };
                        requestAgregar.onerror = (event) => {
                            console.error(`Error en la función agregarDato al agregar los datos al almacén ${store}: ${event.target.error}`);
                            reject(event.target.error);
                        };
                    };
                    clearRequest.onerror = (event) => {
                        console.error(`Error en la función agregarDato al limpiar el almacén ${store} para ingresar los nuevos datos: ${event.target.error}`);
                        reject(event.target.error);
                    };
                };
                request.onerror = (event) => {
                    console.error(`Error en la función agregarDato al abrir la base de datos ${database} para ingresar los nuevos datos: ${event.target.error}`);
                    reject(event.target.error);
                }
            })
        }

        async function procesarUsuario() {
            try {
                const actualizado = await agregarDato('dosxdos', 'usuario', nuevoUsuario);
                if (actualizado) {
                    const mensajeActualizado = mensaje;
                    localStorage.setItem('mensaje', mensajeActualizado);
                    if (nuevoUsuario.clase == 'montador') {
                        window.location.href = "http://localhost/dosxdos_app/rutas_montador.html";
                    } else {
                        window.location.href = "http://localhost/dosxdos_app/ot.html";
                    }
                }
            } catch (error) {
                const mensajeActualizado = 'El usuario ha sido editado exitosamente, pero no ha sido actualizado en la base de datos local, es necesario que cierres la sesión y vuelvas a realizar login para efectuar los cambios: ' + error.message;
                localStorage.setItem('mensaje', mensajeActualizado);
                if (nuevoUsuario.clase == 'montador') {
                    window.location.href = "http://localhost/dosxdos_app/rutas_montador.html";
                } else {
                    window.location.href = "http://localhost/dosxdos_app/ot.html";
                }
            }
        }

        procesarUsuario();
    </script>
<?php
    die();
}

?>

<section id="contenido" class="displayOn">

    <nav id="opcionesMenu2" class="displayOn">
        <?php
        if ($clase == 'admon') {
        ?>
            <div class="opcionMenu displayOn" id="crearUsuario">
                <a href="http://localhost/dosxdos_app/dosxdos.php?modulo=usuarios" class="enlaceBoton">
                    <div class="opcionMenu" id="lineasIcono">
                        <button class="botonIcono" type="button" id="rutasIconoBoton">
                            <img src="http://localhost/dosxdos_app/img/back.png">
                        </button>
                    </div>
                </a>
                <p class='bolder'>Volver</p>
            </div>
        <?php
        }
        if ($clase == 'montador') {
        ?>
            <div class="opcionMenu displayOn" id="crearUsuario">
                <a href="http://localhost/dosxdos_app/rutas_montador.html" class="enlaceBoton">
                    <div class="opcionMenu" id="lineasIcono">
                        <button class="botonIcono" type="button" id="rutasIconoBoton">
                            <img src="http://localhost/dosxdos_app/img/back.png">
                        </button>
                    </div>
                </a>
                <p class='bolder'>Volver</p>
            </div>
        <?php
        }
        if ($clase != 'montador' && $clase != 'admon') {
            ?>
                <div class="opcionMenu displayOn" id="crearUsuario">
                    <a href="http://localhost/dosxdos_app/ot.html" class="enlaceBoton">
                        <div class="opcionMenu" id="lineasIcono">
                            <button class="botonIcono" type="button" id="rutasIconoBoton">
                                <img src="http://localhost/dosxdos_app/img/back.png">
                            </button>
                        </div>
                    </a>
                    <p class='bolder'>Volver</p>
                </div>
            <?php
            }
        ?>

    </nav>

    <form action="editar_usuario.php" method="post" id="editarUsuarioFormulario" enctype="multipart/form-data" class="row g-4" novalidate>

        <!-- Clase -->
        <?php
        if ($clase == 'admon') {
        ?>
            <div class="cInput">
                <label for="clase" class="requerido">CLASE:</label>
                <select id="clase" name="clase">
                    <option value="montador" <?php if ($claseE == 'montador') echo 'selected'; ?>>Montador</option>
                    <option value="oficina" <?php if ($claseE == 'oficina') echo 'selected'; ?>>Oficina</option>
                    <option value="cliente" <?php if ($claseE == 'cliente') echo 'selected'; ?>>Cliente</option>
                    <option value="diseno" <?php if ($claseE == 'diseno') echo 'selected'; ?>>Diseño</option>
                    <option value="estudio" <?php if ($claseE == 'estudio') echo 'selected'; ?>>Estudio</option>
                    <option value="admon" <?php if ($claseE == 'admon') echo 'selected'; ?>>Administrador</option>
                </select>
            </div>
        <?php
        } else {
        ?>
            <input type="hidden" name="clase" value="<?php echo $claseE; ?>">
        <?php
        }
        ?>

        <input type="hidden" name="usuarioActual" value="<?php echo $usuarioE; ?>">

        <!-- Usuario -->
        <div class="cInput">
            <label for="usuario" class="requerido">USUARIO:</label>
            <input type="text" name="usuario" id="usuario" value="<?php echo $usuarioE; ?>" maxlength="8">
        </div>

        <!-- Código -->
        <?php
        if ($clase == 'admon') {
        ?>
            <div class="cInput">
                <label for="cod" class="requerido">CÓDIGO:</label>
                <input type="text" name="cod" id="cod" value="<?php echo $codE; ?>">
            </div>
        <?php
        } else {
        ?>
            <input type="hidden" name="cod" value="<?php echo $codE; ?>">
        <?php
        }
        ?>

        <!-- Contraseña -->
        <div class="cInput">
            <label for="contrasena" class="requerido">CONTRASEÑA:</label>
            <input type="password" name="contrasena" id="contrasena" value="<?php echo $contrasenaE; ?>" maxlength="12">
        </div>

        <!-- Contraseña2 -->
        <div class="cInput">
            <label for="contrasena2" class="requerido">REPETIR CONTRASEÑA:</label>
            <input type="password" name="contrasena2" id="contrasena2" value="<?php echo $contrasenaE; ?>" maxlength="12">
        </div>

        <!-- Correo -->
        <div class="cInput">
            <label for="correo">CORREO:</label>
            <input type="mail" name="correo" id="correo" value="<?php echo $correoE; ?>">
        </div>

        <!-- Móvil -->
        <div class="cInput">
            <label for="movil">MÓVIL:</label>
            <input type="number" name="movil" id="movil" value="<?php echo $movilE; ?>">
        </div>

        <!-- Nombre -->
        <div class="cInput" class="requerido">
            <label for="nombre" class="requerido">NOMBRE:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $nombreE; ?>">
        </div>

        <!-- Apellido -->
        <div class="cInput">
            <label for="apellido">APELLIDO:</label>
            <input type="text" name="apellido" id="apellido" value="<?php echo $apellidoE; ?>">
        </div>

        <!-- Imagen -->
        <div class="cInput">
            <label for="apellido">IMAGEN:</label>
            <input type="file" name="imagen[]" accept="image/*" id="imagen">
        </div>

        <div id="CajaimagenPerfil"><img src="<?php if ($imagenE) {
                                                    echo ('http://localhost/dosxdos_app/' . $imagenE);
                                                } else {
                                                    echo 'http://localhost/dosxdos_app/img/usuario.png';
                                                }  ?>" id="imagenPerfil"></div>

        <input type="hidden" name="editarUsuario" value="1">

        <input type="hidden" name="imagenActual" value="<?php echo $imagenE ?>" id="imagenActual">

        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <?php
        if ($clase == 'admon') {
        ?>
            <div class="cInput">
                <label for="activo">ESTADO:</label>
                <select id="activo" name="activo">
                    <option value="1" <?php if ($activoE == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if ($activoE == 0) echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
        <?php
        } else {
        ?>
            <input type="hidden" name="activo" value="<?php echo $activoE; ?>">
        <?php
        }
        ?>

        <button type="button" id="enviar" class="ruta">EDITAR USUARIO</button>

        <?php
        if ($clase == 'admon') {
        ?>
            <a href="http://localhost/dosxdos_app/editar_usuario.php?eliminar=1&id=<?php echo $id ?>" class="eliminar"><button type="button" id="eliminarUsuario" class="ruta">ELIMINAR USUARIO</button></a>
        <?php
        }
        if ($clase == 'admon') {
        ?>
            <a href="http://localhost/dosxdos_app/dosxdos.php?modulo=usuarios" id="cancelar"><button type="button" class="ruta">CANCELAR</button></a>
        <?php
        }
        if ($clase == 'montador') {
            ?>
                <a href="http://localhost/dosxdos_app/rutas_montador.html" id="cancelar"><button type="button" class="ruta">CANCELAR</button></a>
            <?php
            }
        if ($clase != 'montador' && $clase != 'admon') {
            ?>
                <a href="http://localhost/dosxdos_app/ot.html" id="cancelar"><button type="button" class="ruta">CANCELAR</button></a>
            <?php
            }
        ?>
        
    </form>

</section>

<script>
    actualizarUsuario = false;
    titulo1 = <?php echo ("'" . $nombre . '_' . 'EDITAR USUARIO - ' . $nombreE . ' ' . $apellidoE . "'") ?>;
    titulo2 = <?php echo ("'" . 'EDITAR USUARIO - ' . $nombreE . ' ' . $apellidoE . "'") ?>;
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
<?php
if (!isset($_COOKIE['login'])) {
    header("location: index.html");
}
?>

<section id="contenido" class="displayOn">

    <nav id="opcionesMenu2" class="displayOn">

        <div class="opcionMenu displayOn" id="crearUsuario">
            <a href="http://localhost/dosxdos_app/dosxdos.php?modulo=crearUsuario" class="enlaceBoton">
                <div class="opcionMenu" id="lineasIcono">
                    <button class="botonIcono" type="button" id="rutasIconoBoton">
                        <img src="http://localhost/dosxdos_app/img/usuarioWhite.png">
                    </button>
                </div>
            </a>
            <p class='bolder'>Crear Usuario</p>
        </div>

    </nav>

    <div id="contenedorTabla">
        <table id="tablaUsuarios">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Clase</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                    <th>Cod</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!$conexion) {
                ?>
                    <meta http-equiv="refresh" content="0; url=dosxdos.php?mensaje=Error de conexión a la base de datos: <?php print "$conexion->errno - $conexion->error" ?>">
                    <?php
                }
                $query = "SELECT * FROM usuarios";
                $result = $conexion->datos($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        if (!$row['eliminado']) {
                    ?>
                            <tr class="overfila" <?php print "onclick=\"overfila('$row[id]')\"" ?>>

                                <td>
                                    <?php
                                    if ($row['imagen']) {
                                    ?>
                                        <img src="<?php print $row['imagen'] ?>" class="imagenUsuario">
                                    <?php
                                    } else {
                                    ?>
                                        <img src="img/usuario.png" class="imagenUsuario">
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><?php print $row['nombre'] ?></td>
                                <td><?php print $row["apellido"] ?></td>
                                <td><?php print $row["clase"] ?></td>
                                <td><?php print $row["correo"] ?></td>
                                <td><?php print $row["usuario"] ?></td>
                                <td><?php print $row["cod"] ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
            </tbody>
        </table>
    </div>
<?php
                } else {
?>
    <meta http-equiv="refresh" content="0; url=dosxdos.php.php?mensaje=Error al realizar la consulta en la base de datos">
<?php
                }
?>

</section>

<script>
    titulo1 = <?php echo ("'" . $nombre . '_' . 'USUARIOS' . "'") ?>;
    titulo2 = 'USUARIOS';
    //DataTables
    $(document).ready(function() {
        $('#tablaUsuarios').DataTable({
            "searching": true,
            "ordering": true,
            "select": true,
            "paging": true,
            "lengthMenu": [100, 500, 1000],
            "language": {
                url: "data_tables.json",
            }
        });
    });
</script>
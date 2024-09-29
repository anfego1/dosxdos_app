<?php

    require_once "./apirest/clases/conexion_clase.php";

    date_default_timezone_set('Atlantic/Canary');

    ini_set("display_errors", 0);
    ini_set("display_startup_errors", 0);
    mysqli_report(MYSQLI_REPORT_OFF);

    $conexion = new Conexion;

?>
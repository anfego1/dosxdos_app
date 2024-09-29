<?php

    require_once 'clases/respuestas_clase.php';
    require_once "clases/conexion_clase.php";

    date_default_timezone_set('Atlantic/Canary');

    ini_set('curl.cainfo', '/dev/null');
    set_time_limit(0);
    ini_set('default_socket_timeout', 28800);

    ini_set("display_errors", 0);
    ini_set("display_startup_errors", 0);
    mysqli_report(MYSQLI_REPORT_OFF);

    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: http://localhost/dosxdos_app');

?>
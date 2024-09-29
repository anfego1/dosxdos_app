<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="http://localhost/dosxdos_app/img/logoPwa256.png">
    <title>SINCRONIZADOR OTS Y LÍNEAS DE OT - dosxdos.app.iidos.com</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            letter-spacing: 0.05vw;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100vw;
            margin: 0;
        }

        p {
            color: #db0032;
            font-size: 2vw;
            margin-bottom: 0.5vw;
        }

        #porcentaje {
            color: black;
            font-size: 3vw;
            margin-top: 2vw;
            margin-bottom: 2vw;
        }
    </style>
</head>

<body>

    <p id="porcentaje">0%</p>

    <script>
        const porcentaje = document.getElementById("porcentaje");

        function loading(width) {
            porcentaje.innerHTML = width + '%';
        }
    </script>

    <?php

    echo '<p>INICIO DE SINCRONIZADOR...</p>';
    @ob_flush();
    flush();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    /*ini_set("display_errors", 0);
    ini_set("display_startup_errors", 0);
    mysqli_report(MYSQLI_REPORT_OFF);*/

    ini_set('curl.cainfo', '/dev/null');
    set_time_limit(0);
    ini_set('default_socket_timeout', 28800);
    date_default_timezone_set('Atlantic/Canary');

    /*header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');*/

    require_once "clases/conexion_clase.php";

    $porcentaje = 0;
    $porcentajeOt = 0;
    $porcentajeTotal = 0;
    $conexion = new Conexion;
    $url = "http://172.16.16.156:8047/DynamicsNAV110App/WS/dos.dos/Codeunit/WebPageFunctionsWS";
    $ots = [];
    $tablaFotos = '';
    $tablaFirmas = '';
    $tablaCarpetas = '';
    $tablaLineas = '';
    $longitudOts = 0;
    $longitudOt = 0;
    $longitudLineas = 0;
    $longitudLineasTabla = 0;
    $estado = 0;
    $ejecucion = 0;
    $detalleError = '';
    $sinc = [];
    $fecha = new DateTime();
    $fecha = $fecha->format('Y-m-d H:i:s');
    $horaActual = date('H:i:s');
    $anoActual = new DateTime();
    $anoActual = $anoActual->format('Y');
    $mensaje = "";


    if (extension_loaded('soap')) {
        echo '<p>Extensión SOAP reconocida en el servidor...</p>';
        echo '<script>loading(' . $porcentaje . ')</script>';
        @ob_flush();
        flush();
        // Request parameters :
        $NavUsername = "USERAPP1";
        $NavAccessKey = "WkksQz7t4tRrnJJN9V+QhlOUMV0UD7lvPrFu6MNYHwc=";
        $CodeunitMethod = "LeerOTs";
        $params = array(
            'codigoOT' => '',
            'outJsonOTs' => ''
        );
        $context = stream_context_create([
            'http' => [
                'user_agent' => 'PHPSoapClient',
                'timeout' => 28800,
            ],
        ]);
        // SOAP request header
        $options = array(
            'authentication' => SOAP_AUTHENTICATION_BASIC,
            'login' => $NavUsername,
            'password' => $NavAccessKey,
            'trace' => 1,
            'exception' => 0,
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
        );
        try {
            $client = new SoapClient(trim($url), $options);
            if ($client) {
                echo '<p>Cliente SOAP creado exitosamente...</p>';
                echo '<p>Solicitando OTS a NAVISION...</p>';
                $porcentaje = 1;
                echo '<script>loading(' . $porcentaje . ')</script>';
                @ob_flush();
                flush();
                $soap_response = $client->__soapCall($CodeunitMethod, array('parameters' => $params));
                $respuesta_string = $soap_response->outJsonOTs;
                if ($respuesta_string) {
                    $respuesta = json_decode($respuesta_string, true);
                    $ots = $respuesta["OTs"]["OT"];
                    $longitudOts = count($ots);
                    //print ($longitudOts);
                    //var_dump($ots);
                    echo '<p>' . $longitudOts . ' OTS de NAVISION recibidas exitosamente...</p>';
                    echo '<p>INICIANDO SINCRONIZACIÓN DE OTS...</p>';
                    $porcentaje = 2;
                    echo '<script>loading(' . $porcentaje . ')</script>';
                    @ob_flush();
                    flush();
                    $query = "SELECT * FROM sinc";
                    $result = $conexion->datos($query);
                    if ($result) {
                        //var_dump($respuesta);
                        $row = $result->fetch_assoc();
                        $sinc['longitudOts'] = $row['longitudOts'];
                        $sinc['fecha'] = $row['fecha'];
                        $sinc['estado'] = $row['estado'];
                        $sinc['ejecucion'] = $row['ejecucion'];
                        $sinc['detalleError'] = $row['detalleError'];
                        //var_dump($sinc);
                        //if ($longitudOts > $sinc['longitudOts']) {
                        // SINCRONISMO DE OTS
                        echo '<p>INICIO de sincronización de OTS...</p>';
                        $porcentaje = 2;
                        echo '<script>loading(' . $porcentaje . ')</script>';
                        @ob_flush();
                        flush();
                        $porcentajeOt = 48.5 / $longitudOts;
                        foreach ($ots as $ot) {
                            if ($ot['No.'] && $ot['No.'] != null) {
                                $nombre = addslashes($ot['No.']);
                            } else {
                                $nombre = $uniqueId = uniqid('SIN NÚMERO DE OT', true);
                            }
                            $query = "SELECT * FROM ot WHERE nombre = '$nombre'";
                            $resultOt = $conexion->datos($query);
                            if (!$resultOt) {
                                $mensaje .= ' - Error al sincronizar: ' . $nombre . ' - ';
                                /* temp print */
                                //print $mensaje;
                                continue;
                            }
                            if ($ot['FechaCreacion'] && $ot['FechaCreacion'] != null) {
                                $fechaOt = $ot['FechaCreacion'];
                                $fechaOt = DateTime::createFromFormat('d/m/y', $fechaOt);
                                $fechaOt = $fechaOt->format('Y-m-d');
                                $fechaOt = $fechaOt . ' ' . $horaActual;
                                $anoOt = $ot['FechaCreacion'];
                                $anoOt = DateTime::createFromFormat('d/m/y', $anoOt);
                                $anoOt = $anoOt->format('Y');
                            } else {
                                $fechaOt = $fecha;
                                $anoOt = $anoActual;
                            }
                            if ($ot['FacturaCliente'] && $ot['FacturaCliente'] != null) {
                                $cliente = $ot['FacturaCliente'];
                                $query = "SELECT * FROM clientes WHERE cod = '$cliente'";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $nombreCliente = '';
                                } else {
                                    $row = $result->fetch_assoc();
                                    if ($result->num_rows) {
                                        if ($row['nombre'] && $row['nombre'] != null) {
                                            $nombreCliente = addslashes($row['nombre']);
                                        } else {
                                            $nombreCliente = '';
                                        }
                                    } else {
                                        $nombreCliente = '';
                                    }
                                }
                            } else {
                                $cliente = 0;
                                $nombreCliente = '';
                            }
                            if ($ot['TipoOT'] && $ot['TipoOT'] != null) {
                                $tipo = addslashes($ot['TipoOT']);
                            } else {
                                $tipo = 0;
                            }
                            if ($ot['Descripcion'] && $ot['Descripcion'] != null) {
                                $descripcion = addslashes($ot['Descripcion']);
                            } else {
                                $descripcion = '';
                            }
                            if ($ot['Firma'] && $ot['Firma'] != null) {
                                $firma = addslashes($ot['Firma']);
                            } else {
                                $firma = '';
                            }
                            if (!$resultOt->num_rows) {
                                $query = "INSERT INTO ot (nombre, ano, cliente, nombreCliente, tipo, descripcion, firma, fechaIn) VALUES (\"$nombre\", \"$anoOt\", \"$cliente\", \"$nombreCliente\", \"$tipo\", \"$descripcion\", \"$firma\", \"$fechaOt\")";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $mensaje .= ' - Error al sincronizar: ' . $nombre . ' - ';
                                    /* temp print */
                                    //print $mensaje;
                                }
                                $tablaFotos = 'fotos' . $anoOt;
                                $query = "CREATE TABLE IF NOT EXISTS `$tablaFotos` (
                                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                                        `nombre` VARCHAR(350) NOT NULL,
                                        `ruta` VARCHAR(150) NOT NULL,
                                        `linea` INT NOT NULL,
                                        `ot` VARCHAR(150) NOT NULL,
                                        `lineaActividad` INT NOT NULL,
                                        `ext` VARCHAR(50) NOT NULL,
                                        `cliente` INT NOT NULL,
                                        `nombreCliente` VARCHAR(350) NOT NULL,
                                        `pv` INT NOT NULL,
                                        `pvNombre` VARCHAR(350) NOT NULL,
                                        `usuario` INT NOT NULL,
                                        `visible` INT NOT NULL DEFAULT 0,
                                        `usuarioVisible` INT NOT NULL,
                                        `ano` INT NOT NULL,
                                        `fecha` DATETIME NOT NULL,
                                        UNIQUE KEY `unique_nombre` (`nombre`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $mensaje .= ' - Error al crear la tabla: ' . $tablaFotos . ' - ';
                                    /* temp print */
                                    //print $mensaje;
                                }
                                $tablaFirmas = 'firmas' . $anoOt;
                                $query = "CREATE TABLE IF NOT EXISTS `$tablaFirmas` (
                                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                                        `nombre` VARCHAR(350) NOT NULL,
                                        `ruta` VARCHAR(150) NOT NULL,
                                        `linea` INT NOT NULL,
                                        `ot` VARCHAR(150) NOT NULL,
                                        `lineaActividad` INT NOT NULL,
                                        `ext` VARCHAR(50) NOT NULL,
                                        `cliente` INT NOT NULL,
                                        `nombreCliente` VARCHAR(350) NOT NULL,
                                        `pv` INT NOT NULL,
                                        `pvNombre` VARCHAR(350) NOT NULL,
                                        `usuario` INT NOT NULL,
                                        `visible` INT NOT NULL DEFAULT 0,
                                        `usuarioVisible` INT NOT NULL,
                                        `ano` INT NOT NULL,
                                        `fecha` DATETIME NOT NULL,
                                        UNIQUE KEY `unique_nombre` (`nombre`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $mensaje .= ' - Error al crear la tabla: ' . $tablaFirmas . ' - ';
                                    /* temp print */
                                    //print $mensaje;
                                }
                                $tablaCarpetas = 'carpetas' . $anoOt;
                                $query = "CREATE TABLE IF NOT EXISTS `$tablaCarpetas` (
                                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                                        `nombre` VARCHAR(350) NOT NULL,
                                        `ruta` VARCHAR(150) NOT NULL,
                                        `linea` INT NOT NULL,
                                        `ot` VARCHAR(150) NOT NULL,
                                        `lineaActividad` INT NOT NULL,
                                        `ext` VARCHAR(50) NOT NULL,
                                        `cliente` INT NOT NULL,
                                        `nombreCliente` VARCHAR(350) NOT NULL,
                                        `pv` INT NOT NULL,
                                        `pvNombre` VARCHAR(350) NOT NULL,
                                        `usuario` INT NOT NULL,
                                        `visible` INT NOT NULL DEFAULT 0,
                                        `usuarioVisible` INT NOT NULL,
                                        `ano` INT NOT NULL,
                                        `fecha` DATETIME NOT NULL,
                                        UNIQUE KEY `unique_nombre` (`nombre`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $mensaje .= ' - Error al crear la tabla: ' . $tablaCarpetas . ' - ';
                                    /* temp print */
                                    //print $mensaje;
                                }
                                $tablaLineas = 'lineas' . $anoOt;
                                $query = "CREATE TABLE IF NOT EXISTS `$tablaLineas` (
                                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                                    `ruta` VARCHAR(150) NOT NULL,
                                    `linea` INT NOT NULL,
                                    `ot` VARCHAR(150) NOT NULL,
                                    `lineaOt` INT NOT NULL,
                                    `cliente` INT NOT NULL,
                                    `nombreCliente` VARCHAR(350) NOT NULL,
                                    `pv` INT NOT NULL,
                                    `firma` VARCHAR(350) NOT NULL,
                                    `usuario` INT NOT NULL,
                                    `visible` INT NOT NULL DEFAULT 0,
                                    `usuarioVisible` INT NOT NULL,
                                    `fecha` DATETIME NOT NULL,
                                    `anulada` INT NOT NULL DEFAULT 0
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $mensaje .= ' - Error al crear la tabla: ' . $tablaLineas . ' - ';
                                    /* temp print */
                                    //print $mensaje;
                                }
                                $ejecucion = 1;
                                $porcentaje += $porcentajeOt;
                                $porcentajeTotal = round($porcentaje);
                                echo '<script>loading(' . $porcentajeTotal . ')</script>';
                                @ob_flush();
                                flush();
                            } else {
                                $row = $resultOt->fetch_assoc();
                                $idOt = $row['id'];
                                $query = "UPDATE ot SET cliente=\"$cliente\", nombreCliente=\"$nombreCliente\", tipo=\"$tipo\", descripcion=\"$descripcion\", firma=\"$firma\", fechaIn=\"$fechaOt\" WHERE id=\"$idOt\"";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $mensaje .= ' - Error al sincronizar: ' . $nombre . ' - ';
                                    /* temp print */
                                    //print $mensaje;
                                }
                                $porcentaje += $porcentajeOt;
                                $porcentajeTotal = round($porcentaje);
                                echo '<script>loading(' . $porcentajeTotal . ')</script>';
                                @ob_flush();
                                flush();
                            }
                        }
                        //}
                        // SINCRONISMO DE LÍNEAS
                        echo '<p>FIN de sincronización de OTS...</p>';
                        echo '<p>INICIO de sincronización de LÍNEAS DE OTS...</p>';
                        @ob_flush();
                        flush();
                        foreach ($ots as $ot) {
                            $nombre = addslashes($ot['No.']);
                            $query = "SELECT * FROM ot WHERE nombre = '$nombre'";
                            $resultOt = $conexion->datos($query);
                            if (!$resultOt) {
                                $mensaje .= ' - Error 1 al sincronizar las líneas de la OT: ' . $nombre . ' - ';
                                /* temp print */
                                //print $mensaje;
                                continue;
                            }
                            if ($resultOt->num_rows) {
                                $rowOt = $resultOt->fetch_assoc();
                                $anoOt = $rowOt['ano'];
                                $tablaLineas = "lineas" . $anoOt;
                                $cliente = $ot['FacturaCliente'];
                                $query = "SELECT * FROM clientes WHERE cod = '$cliente'";
                                $result = $conexion->datos($query);
                                if (!$result) {
                                    $nombreCliente = '';
                                } else {
                                    $row = $result->fetch_assoc();
                                    if ($result->num_rows) {
                                        if ($row['nombre'] && $row['nombre'] != null) {
                                            $nombreCliente = addslashes($row['nombre']);
                                        } else {
                                            $nombreCliente = '';
                                        }
                                    } else {
                                        $nombreCliente = '';
                                    }
                                }
                                if ($ot['Firma'] && $ot['Firma'] != null) {
                                    $firma = addslashes($ot['Firma']);
                                } else {
                                    $firma = '';
                                }
                                $CodeunitMethod = "LeerActividadesOT";
                                $params = array(
                                    'codigoOT' => $nombre,
                                    'outJsonLineasOT' => ''
                                );
                                try {
                                    $client = new SoapClient(trim($url), $options);
                                    if ($client) {
                                        $soap_response = $client->__soapCall($CodeunitMethod, array('parameters' => $params));
                                        $respuesta_string = $soap_response->outJsonLineasOT;
                                        if ($respuesta_string) {
                                            $respuesta = json_decode($respuesta_string, true);
                                            $lineas = [];
                                            $i = 0;
                                            if (isset($respuesta["LineasOT"]["LineaOT"]["No."])) {
                                                foreach ($respuesta as $LineasOT) {
                                                    foreach ($LineasOT as $LineaOT) {
                                                        $lineas[$i]["ot"] = $LineaOT["No."];
                                                        $lineas[$i]["linea"] = $LineaOT["No.Linea"];
                                                        $lineas[$i]["pv"] = $LineaOT["No.PuntoVenta"];
                                                        $lineas[$i]["observaciones"] = $LineaOT["Observaciones"];
                                                        $lineas[$i]["anulada"] = $LineaOT["Anulada"];
                                                        $i++;
                                                    }
                                                }
                                            } else {
                                                foreach ($respuesta as $LineasOT) {
                                                    foreach ($LineasOT as $LineaOT1) {
                                                        foreach ($LineaOT1 as $LineaOT) {
                                                            $lineas[$i]["ot"] = $LineaOT["No."];
                                                            $lineas[$i]["linea"] = $LineaOT["No.Linea"];
                                                            $lineas[$i]["pv"] = $LineaOT["No.PuntoVenta"];
                                                            $lineas[$i]["observaciones"] = $LineaOT["Observaciones"];
                                                            $lineas[$i]["anulada"] = $LineaOT["Anulada"];
                                                            $i++;
                                                        }
                                                    }
                                                }
                                            }
                                            $longitudLineas = count($lineas);
                                            $query = "SELECT * FROM $tablaLineas WHERE ot = '$nombre'";
                                            $result = $conexion->datos($query);
                                            if (!$result) {
                                                $mensaje .= ' - Error al sincronizar las líneas de: ' . $nombre . ' - ';
                                                /* temp print */
                                                //print $mensaje;
                                                continue;
                                            }
                                            $longitudLineasTabla = $result->num_rows;
                                            //if ($longitudLineas && $longitudLineas > $longitudLineasTabla) {
                                            foreach ($lineas as $lineaOfLineas) {
                                                $lineaActual = $lineaOfLineas['linea'];
                                                $query = "SELECT * FROM $tablaLineas WHERE ot = '$nombre' AND lineaOt = '$lineaActual'";
                                                $result = $conexion->datos($query);
                                                if (!$result) {
                                                    $mensaje .= ' - Error al sincronizar la línea: ' . $lineaActual . ' de ' . $nombre . ' - ';
                                                    /* temp print */
                                                    //print $mensaje;
                                                    continue;
                                                }
                                                if ($lineaOfLineas['linea'] && $lineaOfLineas['linea'] != null) {
                                                    $lineaActividadLinea = addslashes($lineaOfLineas['linea']);
                                                } else {
                                                    $lineaActividadLinea = 0;
                                                }
                                                if ($lineaOfLineas['pv'] && $lineaOfLineas['pv'] != null) {
                                                    $lineaActividadPv = addslashes($lineaOfLineas['pv']);
                                                } else {
                                                    $lineaActividadPv = 0;
                                                }
                                                if ($lineaOfLineas['observaciones'] && $lineaOfLineas['observaciones'] != null) {
                                                    $lineaActividadObservaciones = addslashes($lineaOfLineas['observaciones']);
                                                } else {
                                                    $lineaActividadObservaciones = '';
                                                }
                                                if ($lineaOfLineas['anulada'] && $lineaOfLineas['anulada'] != null) {
                                                    if ($lineaOfLineas['anulada'] == 'No') {
                                                        $lineaAnulada = 0;
                                                    } else {
                                                        $lineaAnulada = 1;
                                                    }
                                                } else {
                                                    $lineaAnulada = 0;
                                                }
                                                $ruta = 0;
                                                $lineaRuta = 0;
                                                $usuario = 0;
                                                $usuarioVisible = 0;
                                                if (!$result->num_rows) {
                                                    $query = "INSERT INTO $tablaLineas (ruta, linea, ot, lineaOt, cliente, nombreCliente, pv, firma, usuario, usuarioVisible, fecha, anulada) VALUES (\"$ruta\", \"$lineaRuta\", \"$nombre\", \"$lineaActividadLinea\", \"$cliente\", \"$nombreCliente\", \"$lineaActividadPv\", \"$firma\", \"$usuario\", \"$usuarioVisible\", \"$fecha\", \"$lineaAnulada\")";
                                                    $result = $conexion->datos($query);
                                                    if (!$result) {
                                                        $mensaje .= ' - Error al sincronizar la línea: ' . $lineaActual . ' de ' . $nombre . ' - ';
                                                        /* temp print */
                                                        //print $mensaje;
                                                    }
                                                    $ejecucion = 1;
                                                } else {
                                                    $row = $result->fetch_assoc();
                                                    $idLinea = $row['id'];
                                                    $query = "UPDATE $tablaLineas SET cliente=\"$cliente\", nombreCliente=\"$nombreCliente\", pv=\"$lineaActividadPv\", firma=\"$firma\", anulada=\"$lineaAnulada\" WHERE id=\"$idLinea\"";
                                                    $result = $conexion->datos($query);
                                                    if (!$result) {
                                                        $mensaje .= ' - Error al sincronizar la línea: ' . $lineaActual . ' de ' . $nombre . ' - ';
                                                        /* temp print */
                                                        //print $mensaje;
                                                    }
                                                }
                                            }
                                            //}
                                        } else {
                                            $estado = 0;
                                            $detalleError = 'Error en la respuesta SOAP para actualizar las líneas de la OT: ' . $nombre;
                                            $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
                                            $result = $conexion->datos($query);
                                            $mensaje .= 'Error en la respuesta SOAP para actualizar las líneas de la OT: ' . $nombre . ' - ';
                                            /* temp print */
                                            //print $mensaje;
                                        }
                                    } else {
                                        $estado = 0;
                                        $detalleError = '500 - Error al crear el cliente SOAP al traer las líneas de la OT: ' . $nombre;
                                        $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
                                        $result = $conexion->datos($query);
                                        $mensaje .= '500 - Error al crear el cliente SOAP al traer las líneas de la OT: ' . $nombre . ' - ';
                                        /* temp print */
                                        //print $mensaje;
                                    }
                                } catch (SoapFault $soapFault) {
                                    $estado = 0;
                                    $errorMessage = $soapFault->getMessage();
                                    $detalleError = '500 - Error en la llamada o respuesta de la Api de Navision en las líneas de la OT: ' . $nombre . ' ' . $errorMessage . ' - ';
                                    $detalleError = addslashes($detalleError);
                                    $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
                                    $result = $conexion->datos($query);
                                    $mensaje .= $detalleError;
                                    /* temp print */
                                    //print $mensaje;
                                }
                            } else {
                                $mensaje .= 'Error 2 al sincronizar las líneas de la OT: ' . $nombre;
                                /* temp print */
                                //print $mensaje;
                            }
                            $porcentaje += $porcentajeOt;
                            $porcentajeTotal = round($porcentaje);
                            echo '<script>loading(' . $porcentajeTotal . ')</script>';
                            @ob_flush();
                            flush();
                        }
                    } else {
                        $estado = 0;
                        $detalleError = $conexion->error;
                        $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
                        $result = $conexion->datos($query);
                        $mensaje .= 'Error al traer los datos de la sincronización: ' . $detalleError . ' - ';
                        /* temp print */
                        //print $mensaje;
                    }
                } else {
                    $estado = 0;
                    $detalleError = 'Error al recibir la respuesta string de todas las OT de la base de datos del servidor en la api de Navision';
                    $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
                    $result = $conexion->datos($query);
                    $mensaje .= 'Error al recibir la respuesta string de todas las OT de la base de datos del servidor en la api de Navision';
                }
            } else {
                $estado = 0;
                $detalleError = '500 - Error al crear el cliente SOAP de las OT';
                $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
                $result = $conexion->datos($query);
                $mensaje .= '500 - Error al crear el cliente SOAP de las OT' . ' - ';
                /* temp print */
                //print $mensaje;
            }
        } catch (SoapFault $soapFault) {
            $estado = 0;
            $errorMessage = $soapFault->getMessage();
            $detalleError = '500 - Error en la llamada o respuesta de las OT de la Api de Navision: ' . $errorMessage . ' - ';
            $detalleError = addslashes($detalleError);
            $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
            $result = $conexion->datos($query);
            $mensaje .= $detalleError;
            /* temp print */
            //print $mensaje;
        }
        $estado = 1;
        $query = "UPDATE sinc SET longitudOts=\"$longitudOts\", fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$mensaje\" WHERE id = 1";
        $result = $conexion->datos($query);
        if (!$result) {
            $detalleError = $conexion->error;
            $query = "UPDATE sinc SET longitudOts=\"$longitudOts\", fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
            $result = $conexion->datos($query);
            $mensaje .= 'Sincronización realizada. Error al actualizar los datos de la sincronización: ' . $detalleError . ' - ';
            /* temp print */
            //print $mensaje;
        }
        echo '<p>SINCRONIZACIÓN TERMINADA</p>';
        $porcentajeTotal = 100;
        echo '<script>loading(' . $porcentajeTotal . ')</script>';
        echo '<p>FIN DE SINCRONIZADOR</p>';
        @ob_flush();
        flush();
    } else {
        $estado = 0;
        $detalleError = '500 - No existe o no está configurada la extensión Soap en la solicitud de OTS en el servidor donde está alojada la api intermedia';
        $query = "UPDATE sinc SET fecha=\"$fecha\", estado=\"$estado\", ejecucion=\"$ejecucion\", detalleError=\"$detalleError\" WHERE id = 1";
        $result = $conexion->datos($query);
        echo '<p>500 - No existe o no está configurada la extensión Soap en la solicitud de OTS en el servidor donde está alojada la api intermedia</p>';
        $porcentajeTotal = 100;
        echo '<script>loading(' . $porcentajeTotal . ')</script>';
        echo '<p>FIN DE SINCRONIZADOR</p>';
        @ob_flush();
        flush();
    }

    die('<p>FIN DEL SCRIPT EN EL SERVIDOR</p>');

    ?>
</body>

</html>
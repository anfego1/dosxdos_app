<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";
require_once "crm_clase.php";

class Lineas extends Conexion
{
    public $respuesta = '';
    public $error = '';

    public function lineas($ruta)
    {
        try {
            $crm = new Crm;
            $camposLineas = "Codigo_de_l_nea,C_digo_de_OT_relacionada,Punto_de_venta,rea,Tipo_de_OT,Tipo_de_trabajo,Descripci_n_Tipo_Trabajo,Zona,Sector,Direcci_n,Nombre_de_Empresa,Fecha_actuaci_n,Fase,Motivo_de_incidencia,Observaciones_internas,Observaciones_montador,Horas_actuaci_n,D_as_actuaci_n,Minutos_actuaci_n,Poner,Quitar,Alto_medida,Ancho_medida,Fotos,Firma_de_la_OT_relacionada,Estado_de_Actuaci_n,nombreCliente,nombreOt,nombrePv,codPv,Navision_OT";
            $query = "SELECT $camposLineas FROM Products WHERE Fase=\"$ruta\"";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $lineasData = $crm->respuesta[1]['data'];
                    $lineas = [];
                    $iLineas = 0;
                    foreach ($lineasData as $lineaData) {
                        $idCliente = $lineaData['Nombre_de_Empresa']['id'];
                        $codLinea = $lineaData['Codigo_de_l_nea'];
                        $nombrePv = $lineaData['nombrePv'];
                        $codigoPv = $lineaData['codPv'];
                        $nombreCliente = $lineaData['nombreCliente'];
                        $lineas[$iLineas]['Proyecto'] = $ruta;
                        $lineas[$iLineas]['Linea'] = $codLinea;
                        $lineas[$iLineas]['OT'] = $lineaData['C_digo_de_OT_relacionada'];
                        $lineas[$iLineas]['LineaActividad'] = $codLinea;
                        $lineas[$iLineas]['PuntoVenta'] = $codigoPv;
                        $lineas[$iLineas]['NombrePuntoVenta'] = $nombrePv;
                        $lineas[$iLineas]['TipoTrabajo'] = $lineaData['Tipo_de_trabajo'];
                        $lineas[$iLineas]['Zona'] = $lineaData['Zona'];
                        $lineas[$iLineas]['Sector'] = $lineaData['Sector'];
                        $lineas[$iLineas]['Area'] = $lineaData['rea'];
                        $lineas[$iLineas]['DireccionPuntoVenta'] = $lineaData['Direcci_n'];
                        $lineas[$iLineas]['Cliente'] = $idCliente;
                        $lineas[$iLineas]['NombreCliente'] = $nombreCliente;
                        $lineas[$iLineas]['Montadores'] = 0;
                        $lineas[$iLineas]['FechaActuacion'] = $lineaData['Fecha_actuaci_n'];
                        $lineas[$iLineas]['Estado'] = $lineaData['Estado_de_Actuaci_n'];
                        $lineas[$iLineas]['ComisionGenerada'] = "No";
                        $lineas[$iLineas]['TipoOT'] = $lineaData['Tipo_de_OT'];
                        $lineas[$iLineas]['ComisionValidada'] = "No";
                        $lineas[$iLineas]['Incidencia'] = $lineaData['Motivo_de_incidencia'];
                        $lineas[$iLineas]['DescripcionIncidencia'] = $lineaData['Motivo_de_incidencia'];
                        $lineas[$iLineas]['Observaciones'] = $lineaData['Observaciones_internas'];
                        $lineas[$iLineas]['DescripcionOT'] = $lineaData['nombreOt'];
                        $lineas[$iLineas]['DescripcionTipoTrabajo'] = $lineaData['Descripci_n_Tipo_Trabajo'];
                        $lineas[$iLineas]['ObservacionesTecnico'] = $lineaData['Observaciones_montador'];
                        $lineas[$iLineas]['TiempoActuacion'] = $lineaData['Horas_actuaci_n'];
                        $lineas[$iLineas]['FotosSubidas'] = 0;
                        $lineas[$iLineas]['RutaActiva'] = "Sí";
                        $lineas[$iLineas]['DiasActuacion'] = $lineaData['D_as_actuaci_n'];
                        $lineas[$iLineas]['MinutosActuacion'] = $lineaData['Minutos_actuaci_n'];
                        $lineas[$iLineas]['Poner'] = $lineaData['Poner'];
                        $lineas[$iLineas]['Quitar'] = $lineaData['Quitar'];
                        $lineas[$iLineas]['Alto'] = $lineaData['Alto_medida'];
                        $lineas[$iLineas]['Ancho'] = $lineaData['Ancho_medida'];
                        $lineas[$iLineas]['Revisado'] = "No";
                        $lineas[$iLineas]['WebLink'] = $lineaData['Fotos'];
                        $lineas[$iLineas]['Firma'] = $lineaData['Firma_de_la_OT_relacionada'];
                        $lineas[$iLineas]['Navision_OT'] = $lineaData['Navision_OT'];
                        $iLineas++;
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($lineas);
                    $this->respuesta = $answer;
                } else {
                    $lineas = [];
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($lineas);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    
    public function put($json)
    {
        try {
            $crm = new Crm;
            $json = json_decode($json, true, 512, JSON_UNESCAPED_UNICODE);
            $json['ObservacionesTecnico'] = parent::sanitizar($json['ObservacionesTecnico']);
            $lineaDatos = $this->linea2($json['CodigoRuta'], $json['LineaRuta']);
            if ($lineaDatos) {
                $usuario = $_COOKIE['usuario'];
                $ot = $lineaDatos['C_digo_de_OT_relacionada'];
                $ot = addslashes($ot);
                $ot = parent::sanitizar($ot);
                $cliente = $lineaDatos['Nombre_de_Empresa']['id'];
                $cliente = addslashes($cliente);
                $cliente = parent::sanitizar($cliente);
                $nombreCliente = $lineaDatos['nombreCliente'];
                $nombreCliente = addslashes($nombreCliente);
                $nombreCliente = parent::sanitizar($nombreCliente);
                $tipo = $lineaDatos['Tipo_de_OT'];
                $tipo = addslashes($tipo);
                $tipo = parent::sanitizar($tipo);
                $descripcion = $lineaDatos['nombreOt'];
                $descripcion = addslashes($descripcion);
                $descripcion = parent::sanitizar($descripcion);
                $firma = $lineaDatos['Firma_de_la_OT_relacionada'];
                $firma = addslashes($firma);
                $firma = parent::sanitizar($firma);
                $ano_actual = date('Y');
                $fecha = date('Y-m-d H:i:s');
                $tablaFotos = '';
                $tablaFirmas = '';
                $tablaCarpetas = '';
                $tablaLineas = '';
                $LineaVector = [];
                $LineaVector['data'][0]['id'] = $lineaDatos['id'];
                $LineaVector['data'][0]['montadorUsuarioApp'] = $usuario;
                $LineaVector['data'][0]['Estado_de_Actuaci_n'] = $json['Estado'];
                if ($json['Estado'] == "Realizado") {
                    $LineaVector['data'][0]['Fase'] = "Terminadas";
                }
                if ($json['Incidencia']) {
                    $LineaVector['data'][0]['Motivo_de_incidencia'] = $json['Incidencia'];
                    $LineaVector['data'][0]['Montador_de_la_incidencia'] = $usuario;
                    $LineaVector['data'][0]['Fase'] = "Incidencias";
                }
                $LineaVector['data'][0]['D_as_actuaci_n'] = $json['DiasActuacion'];
                $LineaVector['data'][0]['Fecha_actuaci_n'] = $json['FechaActuacion'];
                $LineaVector['data'][0]['Horas_actuaci_n'] = $json['HorasActuacion'];
                $LineaVector['data'][0]['Minutos_actuaci_n'] = $json['MinutosActuacion'];
                $LineaVector['data'][0]['Observaciones_montador'] = $json['ObservacionesTecnico'];
                $LineaVector['data'][0]['Fotos'] = $json['LinkWeb'];
                
                $LineaJson = json_encode($LineaVector);
                $crm->actualizar("actualizarLinea", $LineaJson);
                if ($crm->estado) {
                    // BASE DE DATOS DE LA APP
                    $query = "SELECT * FROM ot WHERE nombre = '$ot'";
                    $result = parent::datos($query);
                    if (!$result->num_rows) {
                        $query = "INSERT INTO ot (nombre, ano, cliente, nombreCliente, tipo, descripcion, firma, fechaIn) VALUES ('$ot', '$ano_actual', '$cliente', '$nombreCliente', '$tipo', '$descripcion', '$firma', '$fecha')";
                        $result = parent::datos($query);
                        if ($result) {
                            $tablaFotos = 'fotos' . $ano_actual;
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
                            $result = parent::datos($query);
                            if (!$result) {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error al crear la tabla fotos del año actual');
                                $this->error = $answer;
                                return;
                            }
                            $tablaFirmas = 'firmas' . $ano_actual;
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
                            $result = parent::datos($query);
                            if (!$result) {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error al crear la tabla firmas del año actual');
                                $this->error = $answer;
                                return;
                            }
                            $tablaCarpetas = 'carpetas' . $ano_actual;
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
                            $result = parent::datos($query);
                            if (!$result) {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error al crear la tabla carpetas del año actual');
                                $this->error = $answer;
                                return;
                            }
                            $tablaLineas = 'lineas' . $ano_actual;
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
                            $result = parent::datos($query);
                            if (!$result) {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error al crear la tabla lineas del año actual');
                                $this->error = $answer;
                                return;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - No fue posible ingresar la nueva ot en la tabla de la base de datos');
                            $this->error = $answer;
                            return;
                        }
                    } else {
                        $row = $result->fetch_assoc();
                        $tablaLineas = 'lineas' . $row['ano'];
                        $tablaFirmas = 'firmas' . $row['ano'];
                        $tablaCarpetas = 'carpetas' . $row['ano'];
                    }
                    $puntoVenta = $lineaDatos['codPv'];
                    $puntoVenta = addslashes($puntoVenta);
                    $puntoVenta = parent::sanitizar($puntoVenta);
                    $ruta = $json['CodigoRuta'];
                    $ruta = parent::sanitizar($ruta);
                    $linea = $lineaDatos['Codigo_de_l_nea'];
                    $linea = parent::sanitizar($linea);
                    $lineaOt = $lineaDatos['Codigo_de_l_nea'];
                    $lineaOt = parent::sanitizar($lineaOt);
                    $query = "SELECT * FROM  $tablaLineas WHERE ot = '$ot' AND lineaOt = '$lineaOt'";
                    $result = parent::datos($query);
                    if (!$result->num_rows) {
                        $query = "INSERT INTO  $tablaLineas (ruta, linea, ot, lineaOt, cliente, nombreCliente, pv, firma, usuario, fecha) VALUES ('$ruta', '$linea', '$ot', '$lineaOt', '$cliente', '$nombreCliente', '$puntoVenta', '$firma', '$usuario', '$fecha')";
                        $result = parent::datos($query);
                        if (!$result) {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - No fue posible ingresar la novedad en la tabla lineas de la base de datos');
                            $this->error = $answer;
                            return;
                        } else {
                            $respuesta = true;
                            $respuestas = new Respuestas;
                            $answer = $respuestas->ok($respuesta);
                            $this->respuesta = $answer;
                        }
                    } else {
                        $row = $result->fetch_assoc();
                        $idLinea = $row['id'];
                        $query = "UPDATE $tablaLineas SET ruta = '$ruta', linea = '$linea', usuario = '$usuario' WHERE id = $idLinea";
                        $result = parent::datos($query);
                        if (!$result) {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - No fue posible ingresar la novedad de la actualización del campo usuario en la tabla lineas de la base de datos');
                            $this->error = $answer;
                            return;
                        } else {
                            $respuesta = true;
                            $respuestas = new Respuestas;
                            $answer = $respuestas->ok($respuesta);
                            $this->respuesta = $answer;
                        }
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500($crm->respuestaError);
                    $this->error = $answer;
                    return;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500('500 - No fue posible consultar la línea en la APIREST CRM desde la API intermedia');
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }
    
    public function linea($ruta, $lineaBuscar)
    {
        try {
            $crm = new Crm;
            $camposLineas = "Codigo_de_l_nea,C_digo_de_OT_relacionada,Punto_de_venta,rea,Tipo_de_OT,Tipo_de_trabajo,Descripci_n_Tipo_Trabajo,Zona,Sector,Direcci_n,Nombre_de_Empresa,Fecha_actuaci_n,Fase,Motivo_de_incidencia,Observaciones_internas,Observaciones_montador,Horas_actuaci_n,D_as_actuaci_n,Minutos_actuaci_n,Poner,Quitar,Alto_medida,Ancho_medida,Fotos,Firma_de_la_OT_relacionada,Estado_de_Actuaci_n,nombreCliente,nombreOt,nombrePv,codPv,Navision_OT";
            $query = "SELECT $camposLineas FROM Products WHERE Codigo_de_l_nea = $lineaBuscar";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $lineaData = $crm->respuesta[1]['data'][0];
                    $lineas = [];
                    $idCliente = $lineaData['Nombre_de_Empresa']['id'];
                    $codLinea = $lineaData['Codigo_de_l_nea'];
                    $nombrePv = $lineaData['nombrePv'];
                    $codigoPv = $lineaData['codPv'];
                    $nombreCliente = $lineaData['nombreCliente'];
                    $lineas[0]['Proyecto'] = $ruta;
                    $lineas[0]['Linea'] = $codLinea;
                    $lineas[0]['OT'] = $lineaData['C_digo_de_OT_relacionada'];
                    $lineas[0]['LineaActividad'] = $codLinea;
                    $lineas[0]['PuntoVenta'] = $codigoPv;
                    $lineas[0]['NombrePuntoVenta'] = $nombrePv;
                    $lineas[0]['TipoTrabajo'] = $lineaData['Tipo_de_trabajo'];
                    $lineas[0]['Zona'] = $lineaData['Zona'];
                    $lineas[0]['Sector'] = $lineaData['Sector'];
                    $lineas[0]['Area'] = $lineaData['rea'];
                    $lineas[0]['DireccionPuntoVenta'] = $lineaData['Direcci_n'];
                    $lineas[0]['Cliente'] = $idCliente;
                    $lineas[0]['NombreCliente'] = $nombreCliente;
                    $lineas[0]['Montadores'] = 0;
                    $lineas[0]['FechaActuacion'] = $lineaData['Fecha_actuaci_n'];
                    $lineas[0]['Estado'] = $lineaData['Estado_de_Actuaci_n'];
                    $lineas[0]['ComisionGenerada'] = "No";
                    $lineas[0]['TipoOT'] = $lineaData['Tipo_de_OT'];
                    $lineas[0]['ComisionValidada'] = "No";
                    $lineas[0]['Incidencia'] = $lineaData['Motivo_de_incidencia'];
                    $lineas[0]['DescripcionIncidencia'] = $lineaData['Motivo_de_incidencia'];
                    $lineas[0]['Observaciones'] = $lineaData['Observaciones_internas'];
                    $lineas[0]['DescripcionOT'] = $lineaData['nombreOt'];
                    $lineas[0]['DescripcionTipoTrabajo'] = $lineaData['Descripci_n_Tipo_Trabajo'];
                    $lineas[0]['ObservacionesTecnico'] = $lineaData['Observaciones_montador'];
                    $lineas[0]['TiempoActuacion'] = $lineaData['Horas_actuaci_n'];
                    $lineas[0]['FotosSubidas'] = 0;
                    $lineas[0]['RutaActiva'] = "Sí";
                    $lineas[0]['DiasActuacion'] = $lineaData['D_as_actuaci_n'];
                    $lineas[0]['MinutosActuacion'] = $lineaData['Minutos_actuaci_n'];
                    $lineas[0]['Poner'] = $lineaData['Poner'];
                    $lineas[0]['Quitar'] = $lineaData['Quitar'];
                    $lineas[0]['Alto'] = $lineaData['Alto_medida'];
                    $lineas[0]['Ancho'] = $lineaData['Ancho_medida'];
                    $lineas[0]['Revisado'] = "No";
                    $lineas[0]['WebLink'] = $lineaData['Fotos'];
                    $lineas[0]['Firma'] = $lineaData['Firma_de_la_OT_relacionada'];
                    $lineas[0]['Navision_OT'] = $lineaData['Navision_OT'];
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($lineas);
                    $this->respuesta = $answer;
                } else {
                    $lineas = [];
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF($lineas);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    public function linea2($ruta, $lineaBuscar)
    {
        try {
            $crm = new Crm;
            $camposLineas = "Codigo_de_l_nea,C_digo_de_OT_relacionada,Punto_de_venta,rea,Tipo_de_OT,Tipo_de_trabajo,Descripci_n_Tipo_Trabajo,Zona,Sector,Direcci_n,Nombre_de_Empresa,Fecha_actuaci_n,Fase,Motivo_de_incidencia,Observaciones_internas,Observaciones_montador,Horas_actuaci_n,D_as_actuaci_n,Minutos_actuaci_n,Poner,Quitar,Alto_medida,Ancho_medida,Fotos,Firma_de_la_OT_relacionada,Estado_de_Actuaci_n,nombreCliente,nombreOt,nombrePv,codPv";
            $query = "SELECT $camposLineas FROM Products WHERE Codigo_de_l_nea = $lineaBuscar";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $lineaData = $crm->respuesta[1]['data'][0];
                    return $lineaData;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}

/*
$_lineas = new Lineas;
$json = file_get_contents('navision.json');
$_lineas->put($json);
var_dump($_lineas->respuesta);
var_dump($_lineas->error);
*/

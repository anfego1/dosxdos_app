<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";
require_once "crm_clase.php";

class Carpetas extends conexion
{
    public $table = '';
    public $tableFotos = '';
    public $tableFirmas = '';
    public $tablaLineas = '';
    public $lineaNavision = '';
    public $carpetas = [];
    public $carpetasOt = [];
    public $fotos = [];
    public $firmas = [];
    public $archivos = [];
    public $id = '';
    public $nombre = '';
    public $ruta = '';
    public $linea = '';
    public $ot = '';
    public $lineaActividad = '';
    public $ext = '';
    public $cliente = '';
    public $nombreCliente = '';
    public $pv = '';
    public $pvNombre = '';
    public $usuario = '';
    public $usuarioElimina = '';
    public $ano = '';
    public $anoOt = '';
    public $fecha = '';
    public $fechaElimina = '';
    public $tipo = '';
    public $rutaArchivo = '';
    public $accion = '';
    public $respuesta = '';
    public $error = '';

    public function crearZip($archivos, $nombreZip)
    {
        try {
            // Verificar que haya archivos para comprimir
            if (empty($archivos)) {
                return false;
            }
            // Crear una instancia de ZipArchive
            $zip = new ZipArchive();
            // Verificar si se pudo crear la instancia de ZipArchive
            if (!$zip) {
                return false;
            }
            // Abrir o crear el archivo ZIP
            if ($zip->open($nombreZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Agregar cada archivo al archivo ZIP
                foreach ($archivos as $archivo) {
                    // Verificar si el archivo existe
                    if (file_exists($archivo)) {
                        $nombreArchivo = basename($archivo);
                        // Divide el string en un array
                        $arrayString = explode("_", $nombreArchivo);
                        // Elimina la fecha (el quinto elemento del array)
                        unset($arrayString[6]);
                        // Concatena el array de nuevo en un string
                        $string_sin_fecha = implode("_", $arrayString);
                        // Agregar el archivo al ZIP
                        $zip->addFile($archivo, $string_sin_fecha);
                    }
                }
                // Cerrar el archivo ZIP
                $zip->close();
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al crear el archivo zip: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function post($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['ruta']) || !isset($datos['linea']) || !isset($datos['ot']) || !isset($datos['lineaActividad']) || !isset($datos['cliente']) || !isset($datos['nombreCliente']) || !isset($datos['pv']) || !isset($datos['pvNombre']) || !isset($datos['usuario'])) {
                if (isset($datos['otCompleta']) && isset($datos['ot']) && isset($datos['usuario'])) {
                    $this->postOt($json);
                } else {
                    $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
                }
            } else {
                $this->ruta = parent::sanitizar($datos['ruta']);;
                $this->linea = parent::sanitizar($datos['linea']);
                $this->ot = parent::sanitizar($datos['ot']);
                $this->lineaActividad = parent::sanitizar($datos['lineaActividad']);
                $this->cliente = parent::sanitizar($datos['cliente']);
                $this->nombreCliente = parent::sanitizar($datos['nombreCliente']);
                $this->pv = parent::sanitizar($datos['pv']);
                $this->pvNombre = parent::sanitizar($datos['pvNombre']);
                $this->usuario = parent::sanitizar($datos['usuario']);
                $nombreCarpeta = $this->ot;
                $ano_actual = date('Y');
                $fechaActual = date('Y-m-d H:i:s');
                $this->fecha = $fechaActual;
                $lineaNavision = $this->linea2($this->ruta, $this->linea);
                $this->lineaNavision = $lineaNavision[0];
                if (!$this->lineaNavision) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - No fue posible consultar la línea en la API Navision desde la API intermedia');
                    $this->error = $answer;
                    return;
                }
                $ot = $this->lineaNavision['OT'];
                $ot = addslashes($ot);
                $ot = parent::sanitizar($ot);
                $cliente = $this->lineaNavision['Cliente'];
                $cliente = addslashes($cliente);
                $cliente = parent::sanitizar($cliente);
                $nombreCliente = $this->lineaNavision['NombreCliente'];
                $nombreCliente = addslashes($nombreCliente);
                $nombreCliente = parent::sanitizar($nombreCliente);
                $tipo = $this->lineaNavision['TipoOT'];
                $tipo = addslashes($tipo);
                $tipo = parent::sanitizar($tipo);
                $descripcion = $this->lineaNavision['DescripcionOT'];
                $descripcion = addslashes($descripcion);
                $descripcion = parent::sanitizar($descripcion);
                $puntoVenta = $this->lineaNavision['PuntoVenta'];
                $puntoVenta = addslashes($puntoVenta);
                $puntoVenta = parent::sanitizar($puntoVenta);
                $firma = $this->lineaNavision['Firma'];
                $firma = addslashes($firma);
                $firma = parent::sanitizar($firma);
                $ruta = $this->lineaNavision['Proyecto'];
                $ruta = parent::sanitizar($ruta);
                $linea = $this->lineaNavision['Linea'];
                $linea = parent::sanitizar($linea);
                $lineaOt = $this->lineaNavision['LineaActividad'];
                $lineaOt = parent::sanitizar($lineaOt);
                $tablaFotos = '';
                $tablaFirmas = '';
                $tablaCarpetas = '';
                $tablaLineas = '';

                $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
                $result = parent::datos($query);
                if (!$result->num_rows) {
                    $query = "INSERT INTO ot (nombre, ano, cliente, nombreCliente, tipo, descripcion, fechaIn) VALUES ('$ot', '$ano_actual', '$cliente', '$nombreCliente', '$tipo', '$descripcion', '$this->fecha')";
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
                    $this->table = $tablaCarpetas;
                    $this->tablaLineas = $tablaLineas;
                    $this->tableFirmas = $tablaFirmas;
                    $this->tableFotos = $tablaFotos;
                    $this->anoOt = $ano_actual;
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $this->table = 'carpetas' . $row['ano'];
                    $this->tableFotos = 'fotos' . $row['ano'];
                    $this->tableFirmas = 'firmas' . $row['ano'];
                    $this->tablaLineas = 'lineas' . $row['ano'];
                    $this->anoOt = $row['ano'];
                }

                $query = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->ot' AND lineaOt = '$this->lineaActividad'";
                if (!$result->num_rows) {
                    $query = "INSERT INTO $this->tablaLineas (ruta, linea, ot, lineaOt, cliente, nombreCliente, pv, firma, usuario, fecha) VALUES ('$this->ruta', '$this->linea', '$ot', '$lineaOt', '$cliente', '$nombreCliente', '$puntoVenta', '$firma', '$this->usuario', '$this->fecha')";
                    $result = parent::datos($query);
                    if (!$result) {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - No fue posible ingresar la novedad en la tabla lineas de la base de datos');
                        $this->error = $answer;
                        return;
                    }
                }

                /* CONSULTA DE LAS FOTOS */
                $query = "SELECT * FROM $this->tableFotos WHERE ruta = '$this->ruta' AND linea = '$this->linea' AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $i = 0;
                        $filePathFoto = __DIR__ . "/../../fotos/" . "/" . $this->anoOt . "/" . $nombreCarpeta . "/";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $this->fotos[$i] = $filePathFoto . $row['nombre'];
                            $i++;
                        }
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error sql al buscar las fotos visibles');
                    $this->error = $answer;
                }
                /* CONSULTA DE LAS FIRMAS */
                $query = "SELECT * FROM $this->tableFirmas WHERE ruta = '$this->ruta' AND linea = '$this->linea' AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $i = 0;
                        $filePathFirma = __DIR__ . "/../../firmas/" . "/" . $this->anoOt . "/" . $nombreCarpeta . "/";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $this->firmas[$i] = $filePathFirma . $row['nombre'];
                            $i++;
                        }
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error sql al buscar las firmas visibles');
                    $this->error = $answer;
                }
                $this->archivos = array_merge($this->fotos, $this->firmas);
                /* CREACIÓN Y GUARDAR ZIP */
                $filePathAno = __DIR__ . "/../../carpetas/" . $this->anoOt;
                if (!file_exists($filePathAno)) {
                    mkdir($filePathAno, 0777, true);
                }
                // Ruta donde se almacenará
                $filePath = __DIR__ . "/../../carpetas/" . $this->anoOt . "/" . $nombreCarpeta;
                // Crea la carpeta si no existe
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                $extension = 'zip';
                $fechaArchivo = date('Y-m-d');
                $idNombre = uniqid();
                $idNombre = substr($idNombre, -3);
                $nombre = $this->ot . "_" . $this->lineaActividad . "_" . $this->pvNombre . "_" . $idNombre . "." . $extension;
                $fileZip = $filePath . "/" . $nombre;
                $crearCarpeta = $this->crearZip($this->archivos, $fileZip);
                if ($crearCarpeta) {
                    $query = "INSERT INTO $this->table (nombre, ruta, linea, ot, lineaActividad, ext, cliente, nombreCliente, pv, pvNombre, usuario, ano, fecha) VALUES ('$nombre', '$this->ruta', '$this->linea', '$this->ot', '$this->lineaActividad', '$extension', '$this->cliente', '$this->nombreCliente', '$this->pv', '$this->pvNombre', '$this->usuario', '$this->anoOt', '$fechaActual')";
                    $result = parent::datosPost($query);
                    if ($result) {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok('Carpeta creada exitosamente');
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - Error sql al ingresar los datos del archivo zip en la base de datos');
                        $this->error = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la función crearZip');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al guardar las imágenes: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function postOt($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['otCompleta']) || !isset($datos['ot']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->ruta = 0;
                $this->linea = 0;
                $this->ot = parent::sanitizar($datos['ot']);
                $this->lineaActividad = 0;
                $this->cliente = 0;
                $this->nombreCliente = 'CARPETA DE OT';
                $this->pv = 0;
                $this->pvNombre = 'CARPETA DE OT';
                $this->usuario = parent::sanitizar($datos['usuario']);
                $nombreCarpeta = $this->ot;
                $ano_actual = date('Y');
                $fechaActual = date('Y-m-d H:i:s');
                $this->fecha = $fechaActual;
                $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
                $result = parent::datos($query);
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $this->table = 'carpetas' . $row['ano'];
                    $this->tableFotos = 'fotos' . $row['ano'];
                    $this->tableFirmas = 'firmas' . $row['ano'];
                    $this->anoOt = $row['ano'];
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error sql al buscar la ot en la base de datos');
                    $this->error = $answer;
                    return;
                }

                /* CONSULTA DE LAS FOTOS */
                $query = "SELECT * FROM $this->tableFotos WHERE ot = '$this->ot' AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $i = 0;
                        $filePathFoto = __DIR__ . "/../../fotos/" . "/" . $this->anoOt . "/" . $nombreCarpeta . "/";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $this->fotos[$i] = $filePathFoto . $row['nombre'];
                            $i++;
                        }
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error sql al buscar las fotos visibles');
                    $this->error = $answer;
                }
                /* CONSULTA DE LAS FIRMAS */
                $query = "SELECT * FROM $this->tableFirmas WHERE ot = '$this->ot' AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $i = 0;
                        $filePathFirma = __DIR__ . "/../../firmas/" . "/" . $this->anoOt . "/" . $nombreCarpeta . "/";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $this->firmas[$i] = $filePathFirma . $row['nombre'];
                            $i++;
                        }
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error sql al buscar las firmas visibles');
                    $this->error = $answer;
                }
                $this->archivos = array_merge($this->fotos, $this->firmas);
                /* CREACIÓN Y GUARDAR ZIP */
                $filePathAno = __DIR__ . "/../../carpetas/" . $this->anoOt;
                if (!file_exists($filePathAno)) {
                    mkdir($filePathAno, 0777, true);
                }
                // Ruta donde se almacenará
                $filePath = __DIR__ . "/../../carpetas/" . $this->anoOt . "/" . $nombreCarpeta;
                // Crea la carpeta si no existe
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                $extension = 'zip';
                $fechaArchivo = date('Y-m-d');
                $idNombre = uniqid();
                $idNombre = substr($idNombre, -3);
                $nombre = $this->ot . "_" . $idNombre . "." . $extension;
                $fileZip = $filePath . "/" . $nombre;
                $crearCarpeta = $this->crearZip($this->archivos, $fileZip);
                if ($crearCarpeta) {
                    $query = "INSERT INTO $this->table (nombre, ruta, linea, ot, lineaActividad, ext, cliente, nombreCliente, pv, pvNombre, usuario, ano, fecha) VALUES ('$nombre', '$this->ruta', '$this->linea', '$this->ot', '$this->lineaActividad', '$extension', '$this->cliente', '$this->nombreCliente', '$this->pv', '$this->pvNombre', '$this->usuario', '$this->anoOt', '$fechaActual')";
                    $result = parent::datosPost($query);
                    if ($result) {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok('Carpeta creada exitosamente');
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - Error sql al ingresar los datos del archivo zip en la base de datos');
                        $this->error = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la función crearZip');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al guardar las imágenes: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function carpetas($ruta, $linea, $ot, $lineaOt)
    {
        try {
            $this->ot = $ot;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->carpetas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'carpetas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot' AND lineaActividad = '$lineaOt'";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../carpetas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $carpeta = [];
                            $carpeta['id'] = $row['id'];
                            $carpeta['nombre'] = $row['nombre'];
                            $carpeta['link'] = 'http://localhost/dosxdos_app/carpetas/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                            $carpeta['nombre'] = $row['nombre'];
                            $carpeta['ruta'] = $row['ruta'];
                            $carpeta['linea'] = $row['linea'];
                            $carpeta['ot'] = $row['ot'];
                            $carpeta['lineaActividad'] = $row['lineaActividad'];
                            $carpeta['ext'] = $row['ext'];
                            $carpeta['cliente'] = $row['cliente'];
                            $carpeta['nombreCliente'] = $row['nombreCliente'];
                            $carpeta['pv'] = $row['pv'];
                            $carpeta['pvNombre'] = $row['pvNombre'];
                            $carpeta['usuario'] = $row['usuario'];
                            if ($row['usuario']) {
                                $usuariocarpeta = $row['usuario'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuariocarpeta'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función carpetas() de la clase carpetas: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $carpeta['nombreUsuario'] = $row2['nombre'];
                                $carpeta['apellidoUsuario'] = $row2['apellido'];
                            }
                            $carpeta['visible'] = $row['visible'];
                            $carpeta['usuarioVisible'] = $row['usuarioVisible'];
                            if ($row['usuarioVisible']) {
                                $usuarioVisible = $row['usuarioVisible'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioVisible'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función carpetas() de la clase carpetas: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $carpeta['nombreUsuarioVisible'] = $row2['nombre'];
                                $carpeta['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $carpeta['ano'] = $row['ano'];
                            $carpeta['fecha'] = $row['fecha'];
                            $this->carpetas[$i] = $carpeta;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->carpetas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las carpetas');
                    $this->error = $answer;
                }
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función carpetas() de la clase carpetas: ' . $e->getMessage());
            $this->error = $answer;
        }
    }

    public function delete($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['ot']) || !isset($datos['id']) || !isset($datos['usuarioElimina']) || !isset($datos['tipo'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->ot = $datos['ot'];
                $this->id = $datos['id'];
                $this->usuarioElimina = $datos['usuarioElimina'];
                $this->tipo = $datos['tipo'];
                $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
                $result = parent::datos($query);
                if (!$result->num_rows) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_400('Error 400: La carpeta no puede ser eliminada ya que las carpetas de la OT no existen en la base de datos');
                    $this->error = $answer;
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $this->table = 'carpetas' . $row['ano'];
                    $query = "SELECT * FROM $this->table WHERE id = '$this->id'";
                    $result = parent::datos($query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $this->nombre = $row['nombre'];
                        $this->ruta = $row['ruta'];
                        $this->linea = $row['linea'];
                        $this->ot = $row['ot'];
                        $this->lineaActividad = $row['lineaActividad'];
                        $this->ext = $row['ext'];
                        $this->cliente = $row['cliente'];
                        $this->nombreCliente = $row['nombreCliente'];
                        $this->pv = $row['pv'];
                        $this->pvNombre = $row['pvNombre'];
                        $this->usuario = $row['usuario'];
                        $this->ano = $row['ano'];
                        $this->fecha = $row['fecha'];
                        $this->fechaElimina = date('Y-m-d H:i:s');
                        $query = "INSERT INTO reciclaje (nombre, ruta, linea, ot, lineaActividad, ext, cliente, nombreCliente, pv, pvNombre, usuario, usuarioElimina, ano, fecha, fechaElimina, tipo) VALUES ('$this->nombre', '$this->ruta', '$this->linea', '$this->ot', '$this->lineaActividad', '$this->ext', '$this->cliente','$this->nombreCliente', '$this->pv', '$this->pvNombre', '$this->usuario', '$this->usuarioElimina', '$this->ano', '$this->fecha', '$this->fechaElimina', '$this->tipo')";
                        $result = parent::datosPost($query);
                        if ($result) {
                            $query = "DELETE FROM $this->table WHERE id = '$this->id'";
                            $result = parent::datos($query);
                            if ($result) {
                                $archivo = __DIR__ . "/../../carpetas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                                $nuevaRuta = __DIR__ . "/../../reciclaje/" . $row['nombre'];
                                // Mover el archivo
                                if (rename($archivo, $nuevaRuta)) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->ok('Carpeta eliminada exitosamente');
                                    $this->respuesta = $answer;
                                } else {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error al mover la carpeta a la carpeta de reciclaje');
                                    $this->error = $answer;
                                }
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error en la eliminación sql de los datos de la carpeta en la tabla de carpetas');
                                $this->error = $answer;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la inserción sql de los datos de la carpeta en la tabla de reciclaje');
                            $this->error = $answer;
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - Error en la consulta sql de los datos de la carpeta');
                        $this->error = $answer;
                    }
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función delete() de la clase Carpetas: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function visible($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['id']) || !isset($datos['tabla']) || !isset($datos['accion']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->table = $datos['tabla'];
                $this->id = $datos['id'];
                $this->accion = $datos['accion'];
                $this->usuario = $datos['usuario'];
                $query = "UPDATE $this->table SET visible = '$this->accion', usuarioVisible = '$this->usuario' WHERE id = $this->id";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('La carpeta ahora es visible para el cliente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido poner la carpeta visible al cliente');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función visible() de la clase Carpetas: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function carpetasOt($ot)
    {
        try {
            $this->ot = $ot;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->carpetas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'carpetas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot'";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../carpetas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $carpeta = [];
                            $carpeta['id'] = $row['id'];
                            $carpeta['nombre'] = $row['nombre'];
                            $carpeta['link'] = 'http://localhost/dosxdos_app/carpetas/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                            $carpeta['nombre'] = $row['nombre'];
                            $carpeta['ruta'] = $row['ruta'];
                            $carpeta['linea'] = $row['linea'];
                            $carpeta['ot'] = $row['ot'];
                            $carpeta['lineaActividad'] = $row['lineaActividad'];
                            $carpeta['ext'] = $row['ext'];
                            $carpeta['cliente'] = $row['cliente'];
                            $carpeta['nombreCliente'] = $row['nombreCliente'];
                            $carpeta['pv'] = $row['pv'];
                            $carpeta['pvNombre'] = $row['pvNombre'];
                            $carpeta['usuario'] = $row['usuario'];
                            if ($row['usuario']) {
                                $usuariocarpeta = $row['usuario'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuariocarpeta'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función carpetas() de la clase carpetas: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $carpeta['nombreUsuario'] = $row2['nombre'];
                                $carpeta['apellidoUsuario'] = $row2['apellido'];
                            }
                            $carpeta['visible'] = $row['visible'];
                            $carpeta['usuarioVisible'] = $row['usuarioVisible'];
                            if ($row['usuarioVisible']) {
                                $usuarioVisible = $row['usuarioVisible'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioVisible'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función carpetas() de la clase carpetas: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $carpeta['nombreUsuarioVisible'] = $row2['nombre'];
                                $carpeta['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $carpeta['ano'] = $row['ano'];
                            $carpeta['fecha'] = $row['fecha'];
                            $this->carpetas[$i] = $carpeta;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->carpetas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las carpetas');
                    $this->error = $answer;
                }
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función carpetas() de la clase carpetas: ' . $e->getMessage());
            $this->error = $answer;
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
                    return $lineas;
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

/*$_carpetas = new carpetas;
$json = file_get_contents('carpetas.json');
$_carpetas->delete($json);
echo $_carpetas->respuesta;
echo $_carpetas->error;*/

/*$_carpetas = new carpetas;
$json = file_get_contents('carpetas.json');
$_carpetas->post($json);
echo $_carpetas->respuesta;
echo $_carpetas->error;*/

/*$_carpetas = new carpetas;
//$json = file_get_contents('carpetas.json');
$_carpetas->carpetas('R-0000', '20000', 'OT-20528');
echo $_carpetas->respuesta;
echo $_carpetas->error;*/

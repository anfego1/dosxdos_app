<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";
require_once "crm_clase.php";

class Firmas extends conexion
{
    public $table = '';
    public $tablaLineas = '';
    public $tablaFotos = '';
    public $tablaCarpetas = '';
    public $lineaNavision = '';
    public $firmas = [];
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

    public function post($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['ruta']) || !isset($datos['linea']) || !isset($datos['firmas']) || !isset($datos['ot']) || !isset($datos['lineaActividad']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->ruta = parent::sanitizar($datos['ruta']);
                $this->linea = parent::sanitizar($datos['linea']);
                $this->ot = parent::sanitizar($datos['ot']);
                $this->lineaActividad = parent::sanitizar($datos['lineaActividad']);
                $this->fecha = parent::sanitizar($datos['fecha']);
                $this->firmas = $datos['firmas'];
                $this->cliente = parent::sanitizar($datos['cliente']);
                $this->nombreCliente = parent::sanitizar($datos['nombreCliente']);
                $this->pv = parent::sanitizar($datos['pv']);
                $this->pvNombre = parent::sanitizar($datos['pvNombre']);
                $this->usuario = parent::sanitizar($datos['usuario']);
                $nombreCarpeta = $this->ot;
                $ano_actual = date('Y');
                $fechaActual = date('Y-m-d H:i:s');
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
                    $this->table = $tablaFirmas;
                    $this->tablaLineas = $tablaLineas;
                    $this->tablaFotos = $tablaFotos;
                    $this->tablaCarpetas = $tablaCarpetas;
                    $this->anoOt = $ano_actual;
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $this->table = 'firmas' . $row['ano'];
                    $this->tablaLineas = 'lineas' . $row['ano'];
                    $this->tablaFotos = 'firmas' . $row['ano'];
                    $this->tablaCarpetas = 'carpetas' . $row['ano'];
                    $this->anoOt = $row['ano'];
                }

                $query = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->ot' AND lineaOt = '$this->lineaActividad'";
                $result = parent::datos($query);
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

                $filePathAno = __DIR__ . "/../../firmas/" . $this->anoOt;
                if (!file_exists($filePathAno)) {
                    mkdir($filePathAno, 0777, true);
                }
                // Ruta donde se almacenarán los archivos
                $filePath = __DIR__ . "/../../firmas/" . $this->anoOt . "/" . $nombreCarpeta;
                // Crea la carpeta si no existe
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
                foreach ($this->firmas as $index => $imageData) {
                    $partes = explode(";base64,", $imageData);
                    $extension = explode('/', mime_content_type($imageData))[1];
                    $file_base64 = base64_decode($partes[1]);
                    $idNombre = uniqid();
                    $idNombre = substr($idNombre, -3) . $index;
                    $nombre = $this->ot . "_" . $this->lineaActividad . "_" . $this->pvNombre . "_" . $idNombre . "." . $extension;
                    $file = $filePath . "/" . $nombre;
                    $query = "INSERT INTO $this->table (nombre, ruta, linea, ot, lineaActividad, ext, cliente, nombreCliente, pv, pvNombre, usuario, ano, fecha) VALUES ('$nombre', '$this->ruta', '$this->linea', '$this->ot', '$this->lineaActividad', '$extension', '$this->cliente', '$this->nombreCliente', '$this->pv', '$this->pvNombre', '$this->usuario', '$this->anoOt', '$fechaActual')";
                    $result = parent::datosPost($query);
                    file_put_contents($file, $file_base64);
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok('Se han guardado las imágenes exitosamente');
                $this->respuesta = $answer;
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al guardar las imágenes: ' . $e->getMessage());
            $this->error = $answer;
        }
    }

    public function firmas($ruta, $linea, $ot, $lineaOt)
    {
        try {
            $this->ot = $ot;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->firmas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'firmas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot' AND lineaActividad = '$lineaOt'";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../firmas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $foto = [];
                            $foto['id'] = $row['id'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['link'] = 'http://localhost/dosxdos_app/firmas/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['ruta'] = $row['ruta'];
                            $foto['linea'] = $row['linea'];
                            $foto['ot'] = $row['ot'];
                            $foto['lineaActividad'] = $row['lineaActividad'];
                            $foto['ext'] = $row['ext'];
                            $foto['cliente'] = $row['cliente'];
                            $foto['nombreCliente'] = $row['nombreCliente'];
                            $foto['pv'] = $row['pv'];
                            $foto['pvNombre'] = $row['pvNombre'];
                            $foto['usuario'] = $row['usuario'];
                            if ($row['usuario']) {
                                $usuarioFoto = $row['usuario'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioFoto'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función fotos() de la clase Fotos: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuario'] = $row2['nombre'];
                                $foto['apellidoUsuario'] = $row2['apellido'];
                            }
                            $foto['visible'] = $row['visible'];
                            $foto['usuarioVisible'] = $row['usuarioVisible'];
                            if ($row['usuarioVisible']) {
                                $usuarioVisible = $row['usuarioVisible'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioVisible'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función firmas() de la clase Firmas: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuarioVisible'] = $row2['nombre'];
                                $foto['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $foto['ano'] = $row['ano'];
                            $foto['fecha'] = $row['fecha'];
                            $this->firmas[$i] = $foto;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->firmas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las firmas');
                    $this->error = $answer;
                }
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función firmas() de la clase firmas: ' . $e->getMessage());
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
                    $answer = $respuestas->error_400('Error 400: La firma no puede ser eliminada ya que las firmas de la OT no existen en la base de datos');
                    $this->error = $answer;
                } else {
                    $row = mysqli_fetch_assoc($result);
                    $this->table = 'firmas' . $row['ano'];
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
                                $archivo = __DIR__ . "/../../firmas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                                $nuevaRuta = __DIR__ . "/../../reciclaje/" . $row['nombre'];
                                // Mover el archivo
                                if (rename($archivo, $nuevaRuta)) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->ok('Firma eliminada exitosamente');
                                    $this->respuesta = $answer;
                                } else {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error al mover la firma a la carpeta de reciclaje');
                                    $this->error = $answer;
                                }
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error en la eliminación sql de los datos de la firma en la tabla de firmas');
                                $this->error = $answer;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la inserción sql de los datos de la firma en la tabla de reciclaje');
                            $this->error = $answer;
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - Error en la consulta sql de los datos de la firma');
                        $this->error = $answer;
                    }
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función delete() de la clase firmas: ' . $th->getMessage());
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
                    $answer = $respuestas->ok('La firma ahora es visible para el cliente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido poner la firma visible al cliente');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función visible() de la clase Firmas: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function firmasOt($ot)
    {
        try {
            $this->ot = $ot;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->firmas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'firmas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot'";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../firmas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $foto = [];
                            $foto['id'] = $row['id'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['link'] = 'http://localhost/dosxdos_app/firmas/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['ruta'] = $row['ruta'];
                            $foto['linea'] = $row['linea'];
                            $foto['ot'] = $row['ot'];
                            $foto['lineaActividad'] = $row['lineaActividad'];
                            $foto['ext'] = $row['ext'];
                            $foto['cliente'] = $row['cliente'];
                            $foto['nombreCliente'] = $row['nombreCliente'];
                            $foto['pv'] = $row['pv'];
                            $foto['pvNombre'] = $row['pvNombre'];
                            $foto['usuario'] = $row['usuario'];
                            if ($row['usuario']) {
                                $usuarioFoto = $row['usuario'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioFoto'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función fotos() de la clase Fotos: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuario'] = $row2['nombre'];
                                $foto['apellidoUsuario'] = $row2['apellido'];
                            }
                            $foto['visible'] = $row['visible'];
                            $foto['usuarioVisible'] = $row['usuarioVisible'];
                            if ($row['usuarioVisible']) {
                                $usuarioVisible = $row['usuarioVisible'];
                                $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioVisible'";
                                $result2 = parent::datos($query2);
                                if (!$result2) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500('500 - Error en la función firmas() de la clase Firmas: No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuarioVisible'] = $row2['nombre'];
                                $foto['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $foto['ano'] = $row['ano'];
                            $foto['fecha'] = $row['fecha'];
                            $this->firmas[$i] = $foto;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->firmas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las firmas');
                    $this->error = $answer;
                }
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función firmas() de la clase firmas: ' . $e->getMessage());
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

/*$_firmas = new firmas;
$json = file_get_contents('firmas.json');
$_firmas->delete($json);
echo $_firmas->respuesta;
echo $_firmas->error;*/

/*$_firmas = new firmas;
$json = file_get_contents('firmas.json');
$_firmas->post($json);
echo $_firmas->respuesta;
echo $_firmas->error;*/

/*$_firmas = new firmas;
//$json = file_get_contents('firmas.json');
$_firmas->firmas('R-0000', '20000', 'OT-20528');
echo $_firmas->respuesta;
echo $_firmas->error;*/
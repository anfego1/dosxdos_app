<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class Reciclaje extends conexion
{
    public $table = 'reciclaje';
    public $reciclados = [];
    public $id = '';
    public $nombre = '';
    public $ruta = '';
    public $linea = '';
    public $ot = '';
    public $lineaActividad = '';
    public $ext = '';
    public $cliente = '';
    public $usuario = '';
    public $usuarioElimina = '';
    public $ano = '';
    public $fecha = '';
    public $fechaElimina = '';
    public $tipo = '';
    public $accion = '';
    public $tabla = '';
    public $respuesta = '';
    public $error = '';

    public function archivosReciclados()
    {
        try {
            $query = "SELECT * FROM reciclaje ORDER BY fechaElimina DESC";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_400('No existen archivos reciclados');
                $this->respuesta = $answer;
            } else {
                $i = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $reciclado = [];
                    $reciclado['id'] = $row['id'];
                    $reciclado['nombre'] = $row['nombre'];
                    $reciclado['link'] = 'http://localhost/dosxdos_app/reciclaje/' . $row['nombre'];
                    $reciclado['nombre'] = $row['nombre'];
                    $reciclado['ruta'] = $row['ruta'];
                    $reciclado['linea'] = $row['linea'];
                    $reciclado['ot'] = $row['ot'];
                    $reciclado['lineaActividad'] = $row['lineaActividad'];
                    $reciclado['ext'] = $row['ext'];
                    $reciclado['cliente'] = $row['cliente'];
                    $reciclado['usuario'] = $row['usuario'];
                    if ($row['usuario']) {
                        $usuarioArchivo = $row['usuario'];
                        $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioArchivo'";
                        $result2 = parent::datos($query2);
                        if (!$result2) {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la función archivosReciclados() de la clase Reciclaje: No fue posible leer los datos del usuario que subió el archivo');
                            $this->error = $answer;
                            return;
                        }
                        $row2 = mysqli_fetch_assoc($result2);
                        $reciclado['nombreUsuario'] = $row2['nombre'];
                        $reciclado['apellidoUsuario'] = $row2['apellido'];
                    }
                    $reciclado['usuarioElimina'] = $row['usuarioElimina'];
                    if ($row['usuarioElimina']) {
                        $usuarioElimina = $row['usuarioElimina'];
                        $query2 = "SELECT * FROM usuarios WHERE id = '$usuarioElimina'";
                        $result2 = parent::datos($query2);
                        if (!$result2) {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la función archivosReciclados() de la clase Reciclaje: No fue posible leer los datos del usuario que subió el archivo');
                            $this->error = $answer;
                            return;
                        }
                        $row2 = mysqli_fetch_assoc($result2);
                        $reciclado['nombreUsuarioElimina'] = $row2['nombre'];
                        $reciclado['apellidoUsuarioElimina'] = $row2['apellido'];
                    }
                    $reciclado['ano'] = $row['ano'];
                    $reciclado['fecha'] = $row['fecha'];
                    $reciclado['fechaElimina'] = $row['fechaElimina'];
                    $reciclado['tipo'] = $row['tipo'];
                    $this->reciclados[$i] = $reciclado;
                    $i++;
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->reciclados);
                $this->respuesta = $answer;
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función archivosReciclados() de la clase Reciclaje: ' . $e->getMessage());
            $this->error = $answer;
        }
    }

    public function vaciar($carpeta)
    {
        // Obtener la lista de archivos en la carpeta
        $archivos = scandir($carpeta);
        // Iterar sobre los archivos y eliminarlos
        foreach ($archivos as $archivo) {
            if ($archivo != '.' && $archivo != '..') {
                $rutaArchivo = $carpeta . '/' . $archivo;
                // Verificar si es un archivo
                if (is_file($rutaArchivo)) {
                    // Eliminar el archivo
                    unlink($rutaArchivo);
                }
            }
        }
    }

    public function delete($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['accion'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->accion = $datos['accion'];
                if ($this->accion == 'delete') {
                    if (!isset($datos['id'])) {
                        $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
                    }
                    $this->id = $datos['id'];
                    $query = "SELECT * FROM $this->table WHERE id = '$this->id'";
                    $result = parent::datos($query);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $this->nombre = $row['nombre'];
                        $rutaArchivo = __DIR__ . "/../../reciclaje/" . $this->nombre;
                        $query = "DELETE FROM $this->table WHERE id = '$this->id'";
                        $result = parent::datos($query);
                        if ($result) {
                            if (unlink($rutaArchivo)) {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->ok('El archivo ha sido eliminado del servidor definitivamente');
                                $this->respuesta = $answer;
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error: el archivo fue eliminado de la base de datos, pero hubo un error al eliminarlo de la carpeta de reciclaje del servidor. Aún así, como la base de datos no lo tendrá en cuenta, no hay inconvenientes en la no visualización del archivo.');
                                $this->error = $answer;
                            }
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - Error: no se ha realizado la consulta en la base de datos del archivo');
                        $this->error = $answer;
                    }
                }
                if ($this->accion == 'vaciar') {
                    $query = "DELETE FROM $this->table";
                    $result = parent::datos($query);
                    if ($result) {
                        $carpetaEliminar = __DIR__ . "/../../reciclaje/";
                        $this->vaciar($carpetaEliminar);
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok('La carpeta de reciclaje del servidor ha sido vaciada, los arhivos han sido eliminados definitivamente del servidor');
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('500 - Error: Se ha producido un error al intentar vaciar la base de datos para posteriormente eliminar todos los archivos del servidor');
                        $this->error = $answer;
                    }
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función delete() de la clase Reciclaje: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function restaurar($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['id']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->id = $datos['id'];
                $this->usuario = $datos['usuario'];
                $fechaActual = date('Y-m-d H:i:s');
                $this->fecha = $fechaActual;
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
                    $this->ano = $row['ano'];
                    $this->tipo = $row['tipo'];
                    if ($this->tipo == 'foto') {
                        $this->tabla = 'fotos' . $this->ano;
                    } else {
                        $this->tabla = 'firmas' . $this->ano;
                    }
                    $query = "INSERT INTO $this->tabla (nombre, ruta, linea, ot, lineaActividad, ext, cliente, usuario, ano, fecha) VALUES ('$this->nombre', '$this->ruta', '$this->linea', '$this->ot', '$this->lineaActividad', '$this->ext', '$this->cliente', '$this->usuario', '$this->ano', '$this->fecha')";
                    $result = parent::datosPost($query);
                    if ($result) {
                        $query = "DELETE FROM $this->table WHERE id = '$this->id'";
                        $result = parent::datos($query);
                        if ($result) {
                            $nuevaRuta = __DIR__ . "/../../fotos/" . $this->ano . '/' . $this->ot . '/' . $this->nombre;
                            $archivo = __DIR__ . "/../../reciclaje/" . $this->nombre;
                            $pathAno =  __DIR__ . "/../../fotos/" . $this->ano;
                            $pathOt = __DIR__ . "/../../fotos/" . $this->ano . '/' . $this->ot;
                            if (!file_exists($pathAno)) {
                                mkdir($pathAno, 0777, true);
                            }
                            if (!file_exists($pathOt)) {
                                mkdir($pathOt, 0777, true);
                            }
                            // Mover el archivo
                            if (rename($archivo, $nuevaRuta)) {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->ok('El archivo ha sido restaurado exitosamente, es necesario aprobarlo nuevamente para su visualización al cliente');
                                $this->respuesta = $answer;
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_500('500 - Error al mover el archivo a la carpeta que pertenecía, el archivo no ha podido ser restaurado');
                                $this->error = $answer;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la eliminación sql de los datos del archivo en la tabla de reciclaje');
                            $this->error = $answer;
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $mensajeRespuesta = '500 - Error en la inserción sql de los datos del archivo en la tabla ' . $this->tabla;
                        $answer = $respuestas->error_500();
                        $this->error = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de los datos del archivo');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función restaurar() de la clase Reciclaje: ' . $th->getMessage());
            $this->error = $answer;
        }
    }
}

/*$_reciclaje = new reciclaje;
$json = file_get_contents('reciclaje.json');
$_reciclaje->delete($json);
echo $_reciclaje->respuesta;
echo $_reciclaje->error;*/

/*$_reciclaje = new reciclaje;
$json = file_get_contents('reciclaje.json');
$_reciclaje->post($json);
echo $_reciclaje->respuesta;
echo $_reciclaje->error;*/

/*$_reciclaje = new reciclaje;
//$json = file_get_contents('reciclaje.json');
$_reciclaje->reciclaje('R-0000', '20000', 'OT-20528');
echo $_reciclaje->respuesta;
echo $_reciclaje->error;*/

?>
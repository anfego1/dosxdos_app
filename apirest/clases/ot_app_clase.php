<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class otApp extends Conexion
{
    public $table = 'ot';
    public $id = '';
    public $nombre = '';
    public $ano = '';
    public $cliente = '';
    public $nombreCliente = '';
    public $tipo = '';
    public $descripcion = '';
    public $fechaIn = '';
    public $visible = '';
    public $usuarioVisible = '';
    public $ots = [];
    public $anos = [];
    public $accion = '';
    public $usuario = '';
    public $tablaFotos = '';
    public $tablaFirmas = '';
    public $tablaCarpetas = '';
    public $tablaLineas = '';
    public $fotos = '';
    public $firmas = '';
    public $carpetas = '';
    public $lineas = '';
    public $respuesta = '';
    public $error = '';

    public function otAppTotal()
    {
        $_respuestas = new Respuestas;
        $query = "SELECT * FROM $this->table ORDER BY fechaIn DESC";
        $result =  parent::datos($query);
        if ($result) {
            if ($result->num_rows) {
                $i = 0;
                $line = [];
                while ($row = $result->fetch_assoc()) {
                    $line['id'] = $row['id'];
                    $line['nombre'] = $row['nombre'];
                    $line['ano'] = $row['ano'];
                    $line['cliente'] = $row['cliente'];
                    $line['nombreCliente'] = $row['nombreCliente'];
                    $line['tipo'] = $row['tipo'];
                    $line['descripcion'] = $row['descripcion'];
                    $line['firma'] = $row['firma'];
                    $line['fechaIn'] = $row['fechaIn'];
                    $line['visible'] = $row['visible'];
                    $line['usuarioVisible'] = $row['usuarioVisible'];
                    $usuarioId = $row['usuarioVisible'];
                    if ($usuarioId != 0) {
                        $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                        $result2 =  parent::datos($query2);
                        if ($result2) {
                            $row2 = $result2->fetch_assoc();
                            $line['nombreUsuario'] = $row2['nombre'];
                            $line['apellidoUsuario'] = $row2['apellido'];
                        } else {
                            $line['nombreUsuario'] = 'Error en Api';
                            $line['apellidoUsuario'] = 'Error en Api';
                        }
                    } else {
                        $line['nombreUsuario'] = 'Sin usuario';
                        $line['apellidoUsuario'] = 'Sin usuario';
                    }
                    $this->nombre = $row['nombre'];
                    $this->tablaFotos = "fotos" . $row['ano'];
                    $this->tablaFirmas = "firmas" . $row['ano'];
                    $this->tablaCarpetas = "carpetas" . $row['ano'];
                    $this->tablaLineas = "lineas" . $row['ano'];
                    $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$this->nombre'";
                    $resultFotos =  parent::datos($queryFotos);
                    if ($resultFotos) {
                        $this->fotos = $resultFotos->num_rows;
                        $line['fotos'] = $this->fotos;
                    } else {
                        $line['fotos'] = 'Error en Api';
                    }
                    $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$this->nombre'";
                    $resultFirmas =  parent::datos($queryFirmas);
                    if ($resultFirmas) {
                        $this->firmas = $resultFirmas->num_rows;
                        $line['firmas'] = $this->firmas;
                    } else {
                        $line['firmas'] = 'Error en Api';
                    }
                    $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$this->nombre'";
                    $resultCarpetas =  parent::datos($queryCarpetas);
                    if ($resultCarpetas) {
                        $this->carpetas = $resultCarpetas->num_rows;
                        $line['carpetas'] = $this->carpetas;
                    } else {
                        $line['carpetas'] = 'Error en Api';
                    }
                    $queryLineas = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->nombre' AND anulada != 1";
                    $resultLineas =  parent::datos($queryLineas);
                    if ($resultLineas) {
                        $this->lineas = $resultLineas->num_rows;
                        $line['lineas'] = $this->lineas;
                    } else {
                        $line['lineas'] = 'Error en Api';
                    }
                    $this->ots[$i] = $line;
                    $i++;
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->ots);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->okF($this->ots);
                $this->respuesta = $answer;
            }
        } else {
            $answer =  $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
            $this->error = $answer;
        }
    }

    public function otAppAno($ano)
    {
        $ano = parent::sanitizar($ano);
        $_respuestas = new Respuestas;
        $query = "SELECT * FROM $this->table WHERE ano = $ano ORDER BY fechaIn DESC";
        $result =  parent::datos($query);
        if ($result) {
            if ($result->num_rows) {
                $i = 0;
                $line = [];
                while ($row = $result->fetch_assoc()) {
                    $line['id'] = $row['id'];
                    $line['nombre'] = $row['nombre'];
                    $line['ano'] = $row['ano'];
                    $line['cliente'] = $row['cliente'];
                    $line['nombreCliente'] = $row['nombreCliente'];
                    $line['tipo'] = $row['tipo'];
                    $line['descripcion'] = $row['descripcion'];
                    $line['firma'] = $row['firma'];
                    $line['fechaIn'] = $row['fechaIn'];
                    $line['visible'] = $row['visible'];
                    $line['usuarioVisible'] = $row['usuarioVisible'];
                    $usuarioId = $row['usuarioVisible'];
                    if ($usuarioId != 0) {
                        $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                        $result2 =  parent::datos($query2);
                        if ($result2) {
                            $row2 = $result2->fetch_assoc();
                            $line['nombreUsuario'] = $row2['nombre'];
                            $line['apellidoUsuario'] = $row2['apellido'];
                        } else {
                            $line['nombreUsuario'] = 'Error en Api';
                            $line['apellidoUsuario'] = 'Error en Api';
                        }
                    } else {
                        $line['nombreUsuario'] = 'Sin usuario';
                        $line['apellidoUsuario'] = 'Sin usuario';
                    }
                    $this->nombre = $row['nombre'];
                    $this->tablaFotos = "fotos" . $row['ano'];
                    $this->tablaFirmas = "firmas" . $row['ano'];
                    $this->tablaCarpetas = "carpetas" . $row['ano'];
                    $this->tablaLineas = "lineas" . $row['ano'];
                    $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$this->nombre'";
                    $resultFotos =  parent::datos($queryFotos);
                    if ($resultFotos) {
                        $this->fotos = $resultFotos->num_rows;
                        $line['fotos'] = $this->fotos;
                    } else {
                        $line['fotos'] = 'Error en Api';
                    }
                    $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$this->nombre'";
                    $resultFirmas =  parent::datos($queryFirmas);
                    if ($resultFirmas) {
                        $this->firmas = $resultFirmas->num_rows;
                        $line['firmas'] = $this->firmas;
                    } else {
                        $line['firmas'] = 'Error en Api';
                    }
                    $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$this->nombre'";
                    $resultCarpetas =  parent::datos($queryCarpetas);
                    if ($resultCarpetas) {
                        $this->carpetas = $resultCarpetas->num_rows;
                        $line['carpetas'] = $this->carpetas;
                    } else {
                        $line['carpetas'] = 'Error en Api';
                    }
                    $queryLineas = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->nombre' AND anulada != 1";
                    $resultLineas =  parent::datos($queryLineas);
                    if ($resultLineas) {
                        $this->lineas = $resultLineas->num_rows;
                        $line['lineas'] = $this->lineas;
                    } else {
                        $line['lineas'] = 'Error en Api';
                    }
                    $this->ots[$i] = $line;
                    $i++;
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->ots);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->okF($this->ots);
                $this->respuesta = $answer;
            }
        } else {
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
            $this->error = $answer;
        }
    }

    public function otApp($ot)
    {
        $ot = parent::sanitizar($ot);
        $_respuestas = new Respuestas;
        $query = "SELECT * FROM $this->table WHERE nombre = '$ot'";
        $result = parent::datos($query);
        if ($result) {
            if ($result->num_rows) {
                $row = $result->fetch_assoc();
                $this->ots['id'] = $row['id'];
                $this->ots['nombre'] = $row['nombre'];
                $this->ots['ano'] = $row['ano'];
                $this->ots['cliente'] = $row['cliente'];
                $this->ots['nombreCliente'] = $row['nombreCliente'];
                $this->ots['tipo'] = $row['tipo'];
                $this->ots['descripcion'] = $row['descripcion'];
                $this->ots['firma'] = $row['firma'];
                $this->ots['fechaIn'] = $row['fechaIn'];
                $this->ots['visible'] = $row['visible'];
                $this->ots['usuarioVisible'] = $row['usuarioVisible'];
                $usuarioId = $row['usuarioVisible'];
                if ($usuarioId != 0) {
                    $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                    $result2 =  parent::datos($query2);
                    if ($result2) {
                        $row2 = $result2->fetch_assoc();
                        $this->ots['nombreUsuario'] = $row2['nombre'];
                        $this->ots['apellidoUsuario'] = $row2['apellido'];
                    } else {
                        $this->ots['nombreUsuario'] = 'Error en Api';
                        $this->ots['apellidoUsuario'] = 'Error en Api';
                    }
                } else {
                    $this->ots['nombreUsuario'] = 'Sin usuario';
                    $this->ots['apellidoUsuario'] = 'Sin usuario';
                }
                $this->nombre = $row['nombre'];
                $this->tablaFotos = "fotos" . $row['ano'];
                $this->tablaFirmas = "firmas" . $row['ano'];
                $this->tablaCarpetas = "carpetas" . $row['ano'];
                $this->tablaLineas = "lineas" . $row['ano'];
                $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$this->nombre'";
                $resultFotos =  parent::datos($queryFotos);
                if ($resultFotos) {
                    $this->fotos = $resultFotos->num_rows;
                    $this->ots['fotos'] = $this->fotos;
                } else {
                    $this->ots['fotos'] = 'Error en Api';
                }
                $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$this->nombre'";
                $resultFirmas =  parent::datos($queryFirmas);
                if ($resultFirmas) {
                    $this->firmas = $resultFirmas->num_rows;
                    $this->ots['firmas'] = $this->firmas;
                } else {
                    $this->ots['firmas'] = 'Error en Api';
                }
                $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$this->nombre'";
                $resultCarpetas =  parent::datos($queryCarpetas);
                if ($resultCarpetas) {
                    $this->carpetas = $resultCarpetas->num_rows;
                    $this->ots['carpetas'] = $this->carpetas;
                } else {
                    $this->ots['carpetas'] = 'Error en Api';
                }
                $queryLineas = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->nombre' AND anulada != 1";
                $resultLineas =  parent::datos($queryLineas);
                if ($resultLineas) {
                    $this->lineas = $resultLineas->num_rows;
                    $this->ots['lineas'] = $this->lineas;
                } else {
                    $this->ots['lineas'] = 'Error en Api';
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->ots);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->okF($this->ots);
                $this->respuesta = $answer;
            }
        } else {
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla ot de la base de datos");
            $this->error = $answer;
        }
    }

    public function otTipos()
    {
        $_respuestas = new Respuestas;
        $query = "SELECT * FROM tiposot ORDER BY cod";
        $result =  parent::datos($query);
        if ($result) {
            if ($result->num_rows) {
                $i = 0;
                $line = [];
                while ($row = $result->fetch_assoc()) {
                    $line[$i]['cod'] = $row['cod'];
                    $line[$i]['descripcion'] = $row['descripcion'];
                    $i++;
                }
                $this->ots = $line;
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->ots);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->okF($this->ots);
                $this->respuesta = $answer;
            }
        } else {
            $answer =  $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla tiposot de la base de datos");
            $this->error = $answer;
        }
    }

    public function put($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['funcion'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado el dato obligatorio funcion");
            } else {
                if ($datos['funcion'] == 'visible') {
                    if (!isset($datos['id']) || !isset($datos['accion']) || !isset($datos['usuario'])) {
                        $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
                    } else {
                        $this->id = $datos['id'];
                        $this->accion = $datos['accion'];
                        $this->usuario = $datos['usuario'];
                        $query = "UPDATE $this->table SET visible = '$this->accion', usuarioVisible = '$this->usuario' WHERE id = $this->id";
                        $result = parent::datos($query);
                        if ($result) {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->ok('La OT ahora es visible para el cliente');
                            $this->respuesta = $answer;
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido poner la OT visible al cliente');
                            $this->error = $answer;
                        }
                    }
                } else {
                    $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un valor correcto en el dato obligatorio funcion");
                }
            }
        } catch (Exception $e) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función visible() de la clase otApp: ' . $e->getMessage());
            $this->error = $answer;
        }
    }

    public function otAnos()
    {
        $_respuestas = new Respuestas;
        $query = "SELECT ano FROM $this->table";
        $result = parent::datos($query);
        if ($result) {
            if ($result->num_rows) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $this->anos[$i] = $row['ano'];
                    $i++;
                }
                $totalAnos = array_unique($this->anos);
                $totalAnos = array_values($totalAnos);
                rsort($totalAnos);
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($totalAnos);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->okF($this->anos);
                $this->respuesta = $answer;
            }
        } else {
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla ot de la base de datos");
            $this->error = $answer;
        }
    }
}

/*$otApp = new otApp;
$otApp->otTipos();
print_r($otApp->error);
print_r($otApp->respuesta);*/

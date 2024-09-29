<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class Clientes extends Conexion
{
    public $table = '';
    public $ot = '';
    public $descripcionOt = '';
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
    public $vFotos = [];
    public $firmas = '';
    public $vFirmas = [];
    public $firma = '';
    public $carpetas = '';
    public $vCarpetas = [];
    public $linea = '';
    public $pv = '';
    public $nombrePv = '';
    public $evaluacion = '';
    public $comentario = '';
    public $usuarioId = '';
    public $clienteUsuario = '';
    public $nombreClienteUsuario = '';
    public $nombreUsuario = '';
    public $apellidoUsuario = '';
    public $fecha = '';
    public $lineas = '';
    public $lineasDeOt = [];
    public $idCliente = '';
    public $cod = '';
    public $codCliente = '';
    public $codOt = '';
    public $restricciones = [];
    public $tipos = [];
    public $tiposOtCliente = [];
    public $nombresOtCliente = [];
    public $errorBucle = false;
    public $vectorFirmasCliente = [];
    public $anoOt = '';
    public $lineaOt = '';
    public $respuesta = '';
    public $error = '';

    public function post($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (isset($datos['idCliente']) && isset($datos['cod']) && isset($datos['agregarRest'])) {
                $this->idCliente = parent::sanitizar($datos['idCliente']);
                $this->cod = parent::sanitizar($datos['cod']);
                $query = "INSERT INTO restotclientes (idCliente, tipoOt) VALUES ('$this->idCliente', '$this->cod')";
                $result = parent::datosPost($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('Restricción agregada exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('Error: la restricción no pudo ser agregada en la base de datos');
                    $this->error = $answer;
                }
            } else if (isset($datos['idCliente']) && isset($datos['cod']) && isset($datos['eliminarRest'])) {
                $this->idCliente = parent::sanitizar($datos['idCliente']);
                $this->cod = parent::sanitizar($datos['cod']);
                $query = "DELETE FROM restotclientes WHERE idCliente = $this->idCliente AND tipoOt = '$this->cod'";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('Restricción eliminada exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('Error: la restricción no pudo ser eliminada en la base de datos');
                    $this->error = $answer;
                }
            } else if (isset($datos['idCliente']) && isset($datos['firma']) && isset($datos['agregarRest'])) {
                $this->idCliente = parent::sanitizar($datos['idCliente']);
                $this->firma = parent::sanitizar($datos['firma']);
                $query = "INSERT INTO restfirmasclientes (idCliente, firma) VALUES ('$this->idCliente', '$this->firma')";
                $result = parent::datosPost($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('Restricción agregada exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('Error: la restricción no pudo ser agregada en la base de datos');
                    $this->error = $answer;
                }
            } else if (isset($datos['idCliente']) && isset($datos['firma']) && isset($datos['eliminarRest'])) {
                $this->idCliente = parent::sanitizar($datos['idCliente']);
                $this->firma = parent::sanitizar($datos['firma']);
                $query = "DELETE FROM restfirmasclientes WHERE idCliente = $this->idCliente AND firma = '$this->firma'";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('Restricción eliminada exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('Error: la restricción no pudo ser eliminada en la base de datos');
                    $this->error = $answer;
                }
            } else if (isset($datos['ot']) && isset($datos['descripcionOt']) && isset($datos['firma']) && isset($datos['ano']) && isset($datos['linea']) && isset($datos['pv']) && isset($datos['nombrePv']) && isset($datos['evaluacion']) && isset($datos['comentario']) && isset($datos['usuarioId']) && isset($datos['clienteUsuario']) && isset($datos['nombreClienteUsuario']) && isset($datos['nombreUsuario']) && isset($datos['apellidoUsuario'])) {
                $this->ot = parent::sanitizar($datos['ot']);
                $this->descripcionOt = addslashes(parent::sanitizar($datos['descripcionOt']));
                $this->firma = parent::sanitizar($datos['firma']);
                $this->ano = parent::sanitizar($datos['ano']);
                $this->linea = parent::sanitizar($datos['linea']);
                $this->pv = parent::sanitizar($datos['pv']);
                $this->nombrePv = addslashes(parent::sanitizar($datos['nombrePv']));
                $this->evaluacion = parent::sanitizar($datos['evaluacion']);
                $this->comentario = addslashes(parent::sanitizar($datos['comentario']));
                $this->usuarioId = parent::sanitizar($datos['usuarioId']);
                $this->clienteUsuario = parent::sanitizar($datos['clienteUsuario']);
                $this->nombreClienteUsuario = addslashes(parent::sanitizar($datos['nombreClienteUsuario']));
                $this->nombreUsuario = parent::sanitizar($datos['nombreUsuario']);
                $this->apellidoUsuario = parent::sanitizar($datos['apellidoUsuario']);
                $this->fecha = date('Y-m-d H:i:s');
                $this->table = 'retro';
                $query = "INSERT INTO $this->table (ot, descripcionOt, firma, ano, linea, pv, nombrePv, evaluacion, comentario, usuarioId, clienteUsuario, nombreClienteUsuario, nombreUsuario, apellidoUsuario, fecha) VALUES ('$this->ot', '$this->descripcionOt', '$this->firma', '$this->ano', '$this->linea', '$this->pv', '$this->nombrePv', '$this->evaluacion', '$this->comentario', '$this->usuarioId', '$this->clienteUsuario', '$this->nombreClienteUsuario', '$this->nombreUsuario', '$this->apellidoUsuario', '$this->fecha')";
                $result = parent::datosPost($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('¡Muchas gracias! Tu retroalimentación ha sido guardada exitosamente, la analizaremos para implementar las mejoras. Si es necesario, te contactaremos para darte una respuesta');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('Error: Tu retroalimentación no pudo ser agregada en la base de datos, por favor inténtalo nuevamente. Si el error persiste, por favor comunícate con nosotros');
                    $this->error = $answer;
                }
            } else {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función rest: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function restGet($idCliente)
    {
        try {
            $_respuestas = new respuestas;
            $this->idCliente = parent::sanitizar($idCliente);
            $query = "SELECT tipoOt FROM restotclientes WHERE idCliente = $this->idCliente ORDER BY tipoOt";
            $result = parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $this->restricciones[$i] = $row['tipoOt'];
                        $i++;
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->restricciones);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->restricciones);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500('Error: las restricciones no han sido consultadas en la base de datos');
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función restGet: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function tiposOt($idCliente)
    {
        try {
            $_respuestas = new respuestas;
            $this->idCliente = parent::sanitizar($idCliente);
            $queryRest = "SELECT tipoOt FROM restotclientes WHERE idCliente = $this->idCliente ORDER BY tipoOt";
            $resultRest = parent::datos($queryRest);
            if ($resultRest) {
                if ($resultRest->num_rows) {
                    $i = 0;
                    while ($row = $resultRest->fetch_assoc()) {
                        $this->restricciones[$i] = $row['tipoOt'];
                        $i++;
                    }
                    $queryCliente = "SELECT cod FROM usuarios WHERE id = '$this->idCliente'";
                    $resultCliente = parent::datos($queryCliente);
                    if ($resultCliente) {
                        $rowCliente = $resultCliente->fetch_assoc();
                        $this->cliente = $rowCliente['cod'];
                        $queryTiposOt = "SELECT tipo FROM ot WHERE cliente = $this->cliente ORDER BY tipo";
                        $resultTiposOt = parent::datos($queryTiposOt);
                        if ($resultTiposOt) {
                            if ($resultTiposOt->num_rows) {
                                $i = 0;
                                while ($row = $resultTiposOt->fetch_assoc()) {
                                    $this->tipos[$i] = $row['tipo'];
                                    $i++;
                                }
                                $this->tipos = array_unique($this->tipos);
                                $this->tipos = array_values($this->tipos);
                                $this->tiposOtCliente = $this->tipos;
                                foreach ($this->restricciones as $restriccion) {
                                    $clave = array_search($restriccion, $this->tiposOtCliente);
                                    if ($clave !== false) {
                                        unset($this->tiposOtCliente[$clave]);
                                    }
                                }
                                $this->tiposOtCliente = array_values($this->tiposOtCliente);
                                foreach ($this->tiposOtCliente as $cod) {
                                    $queryNombre = "SELECT cod, descripcion FROM tiposot WHERE cod = '$cod'";
                                    $resultNombre = parent::datos($queryNombre);
                                    if ($resultNombre) {
                                        if ($resultNombre->num_rows) {
                                            $rowNombre = $resultNombre->fetch_assoc();
                                            $this->nombresOtCliente[$cod] = $rowNombre['descripcion'];
                                        }
                                    } else {
                                        $respuestas = new Respuestas;
                                        $answer = $respuestas->error_400('Error: no se han encontrado los nombres de los tipos de OT que pertenezcan al cliente del usuario');
                                        $this->errorBucle = true;
                                        break;
                                    }
                                }
                                if (!$this->errorBucle) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->ok($this->nombresOtCliente);
                                    $this->respuesta = $answer;
                                } else {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_400('Error en el bucle al buscar los nombres de los tipos de OT que pertenecen al cliente del usuario');
                                    $this->error = $answer;
                                }
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_400('Error: no se han encontrado datos de OT que pertenezcan al cliente del usuario');
                                $this->error = $answer;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('Error: no ha sido posible consultar en la base de datos los tipos de ot del cliente al que pertenece el usuario');
                            $this->error = $answer;
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('Error: no ha sido posible consultar en la base de datos el código del cliente del usuario');
                        $this->error = $answer;
                    }
                } else {
                    $queryCliente = "SELECT cod FROM usuarios WHERE id = '$this->idCliente'";
                    $resultCliente = parent::datos($queryCliente);
                    if ($resultCliente) {
                        $rowCliente = $resultCliente->fetch_assoc();
                        $this->cliente = $rowCliente['cod'];
                        $queryTiposOt = "SELECT tipo FROM ot WHERE cliente = $this->cliente ORDER BY tipo";
                        $resultTiposOt = parent::datos($queryTiposOt);
                        if ($resultTiposOt) {
                            if ($resultTiposOt->num_rows) {
                                $i = 0;
                                while ($row = $resultTiposOt->fetch_assoc()) {
                                    $this->tipos[$i] = $row['tipo'];
                                    $i++;
                                }
                                $this->tipos = array_unique($this->tipos);
                                $this->tipos = array_values($this->tipos);
                                $this->tiposOtCliente = $this->tipos;
                                foreach ($this->tiposOtCliente as $cod) {
                                    $queryNombre = "SELECT cod, descripcion FROM tiposot WHERE cod = '$cod'";
                                    $resultNombre = parent::datos($queryNombre);
                                    if ($resultNombre) {
                                        if ($resultNombre->num_rows) {
                                            $rowNombre = $resultNombre->fetch_assoc();
                                            $this->nombresOtCliente[$cod] = $rowNombre['descripcion'];
                                        }
                                    } else {
                                        $respuestas = new Respuestas;
                                        $answer = $respuestas->error_400('Error: no se han encontrado los nombres de los tipos de OT que pertenezcan al cliente del usuario');
                                        $this->errorBucle = true;
                                        break;
                                    }
                                }
                                if (!$this->errorBucle) {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->ok($this->nombresOtCliente);
                                    $this->respuesta = $answer;
                                } else {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_400('Error en el bucle al buscar los nombres de los tipos de OT que pertenecen al cliente del usuario');
                                    $this->error = $answer;
                                }
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->error_400('Error: no se han encontrado datos de OT que pertenezcan al cliente del usuario');
                                $this->error = $answer;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('Error: no ha sido posible consultar en la base de datos los tipos de ot del cliente al que pertenece el usuario');
                            $this->error = $answer;
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500('Error: no ha sido posible consultar en la base de datos el código del cliente del usuario');
                        $this->error = $answer;
                    }
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500('Error: las restricciones no han sido consultadas en la base de datos');
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función tiposOt: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function firmasCliente($codCliente)
    {
        try {
            $_respuestas = new respuestas;
            $this->codCliente = parent::sanitizar($codCliente);
            $query = "SELECT firma FROM ot WHERE cliente = '$this->codCliente' ORDER BY firma";
            $result = parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        if ($row['firma']) {
                            $this->vectorFirmasCliente[$i] = $row['firma'];
                            $i++;
                        }
                    }
                    $this->vectorFirmasCliente = array_unique($this->vectorFirmasCliente);
                    $this->vectorFirmasCliente = array_values($this->vectorFirmasCliente);
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->vectorFirmasCliente);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->vectorFirmasCliente);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500('Error: Las firmas del cliente no han sido consultadas en la base de datos');
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función firmasCliente: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function restGetFirmas($idCliente)
    {
        try {
            $_respuestas = new respuestas;
            $this->idCliente = parent::sanitizar($idCliente);
            $query = "SELECT firma FROM restfirmasclientes WHERE idCliente = $this->idCliente ORDER BY firma";
            $result = parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $this->restricciones[$i] = $row['firma'];
                        $i++;
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->restricciones);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->restricciones);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500('Error: las restricciones no han sido consultadas en la base de datos');
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función restGetFirmas: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function clientesOts($idCliente, $codCliente, $codOt)
    {
        try {
            $_respuestas = new respuestas;
            $this->idCliente = parent::sanitizar($idCliente);
            $this->codCliente = parent::sanitizar($codCliente);
            $this->codOt = parent::sanitizar($codOt);
            $queryRest = "SELECT firma FROM restfirmasclientes WHERE idCliente = $this->idCliente ORDER BY firma";
            $resultRest = parent::datos($queryRest);
            if ($resultRest) {
                if ($resultRest->num_rows) {
                    $i = 0;
                    while ($row = $resultRest->fetch_assoc()) {
                        $this->restricciones[$i] = $row['firma'];
                        $i++;
                    }
                    $query = "SELECT * FROM ot WHERE cliente =  $this->codCliente AND tipo = '$this->codOt' AND visible = 1 ORDER BY fechaIn DESC";
                    $result =  parent::datos($query);
                    if ($result) {
                        $i = 0;
                        $line = [];
                        while ($row = $result->fetch_assoc()) {
                            $restriccion = array_search($row['firma'], $this->restricciones);
                            if ($restriccion === false) {
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
                                $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$this->nombre' AND visible = 1";
                                $resultFotos =  parent::datos($queryFotos);
                                if ($resultFotos) {
                                    $this->fotos = $resultFotos->num_rows;
                                    $line['fotos'] = $this->fotos;
                                } else {
                                    $line['fotos'] = 'Error en Api';
                                }
                                $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$this->nombre' AND visible = 1";
                                $resultFirmas =  parent::datos($queryFirmas);
                                if ($resultFirmas) {
                                    $this->firmas = $resultFirmas->num_rows;
                                    $line['firmas'] = $this->firmas;
                                } else {
                                    $line['firmas'] = 'Error en Api';
                                }
                                $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$this->nombre' AND visible = 1";
                                $resultCarpetas =  parent::datos($queryCarpetas);
                                if ($resultCarpetas) {
                                    $this->carpetas = $resultCarpetas->num_rows;
                                    $line['carpetas'] = $this->carpetas;
                                } else {
                                    $line['carpetas'] = 'Error en Api';
                                }
                                $queryLineas = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->nombre' AND anulada != 1 AND visible = 1";
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
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($this->ots);
                        $this->respuesta = $answer;
                    } else {
                        $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla ot de la base de datos");
                        $this->error = $answer;
                    }
                } else {
                    $i = 0;
                    while ($row = $resultRest->fetch_assoc()) {
                        $this->restricciones[$i] = $row['firma'];
                        $i++;
                    }
                    $query = "SELECT * FROM ot WHERE cliente =  $this->codCliente AND tipo = '$this->codOt' AND visible = 1 ORDER BY fechaIn DESC";
                    $result =  parent::datos($query);
                    if ($result) {
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
                            $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$this->nombre' AND visible = 1";
                            $resultFotos =  parent::datos($queryFotos);
                            if ($resultFotos) {
                                $this->fotos = $resultFotos->num_rows;
                                $line['fotos'] = $this->fotos;
                            } else {
                                $line['fotos'] = 'Error en Api';
                            }
                            $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$this->nombre' AND visible = 1";
                            $resultFirmas =  parent::datos($queryFirmas);
                            if ($resultFirmas) {
                                $this->firmas = $resultFirmas->num_rows;
                                $line['firmas'] = $this->firmas;
                            } else {
                                $line['firmas'] = 'Error en Api';
                            }
                            $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$this->nombre' AND visible = 1";
                            $resultCarpetas =  parent::datos($queryCarpetas);
                            if ($resultCarpetas) {
                                $this->carpetas = $resultCarpetas->num_rows;
                                $line['carpetas'] = $this->carpetas;
                            } else {
                                $line['carpetas'] = 'Error en Api';
                            }
                            $queryLineas = "SELECT * FROM $this->tablaLineas WHERE ot = '$this->nombre' AND anulada != 1 AND visible = 1";
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
                        $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla ot de la base de datos");
                        $this->error = $answer;
                    }
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500('Error: las restricciones no han sido consultadas en la base de datos');
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función clientesOts: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function lineasApp($ot)
    {
        try {
            $ot = parent::sanitizar($ot);
            $_respuestas = new Respuestas;
            $query = "SELECT * FROM ot WHERE nombre = '$ot'";
            $result =  parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $row = $result->fetch_assoc();
                    $this->anoOt = $row['ano'];
                    $this->table = 'lineas' . $this->anoOt;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF('Error: la OT proporcionada no existe en la base de datos');
                    $this->respuesta = $answer;
                    return;
                }
                $query = "SELECT * FROM $this->table WHERE ot = '$ot' AND anulada != 1 AND visible = 1 ORDER BY fecha DESC";
                $result =  parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $i = 0;
                        $line = [];
                        while ($row = $result->fetch_assoc()) {
                            $line['id'] = $row['id'];
                            $line['ruta'] = $row['ruta'];
                            $line['linea'] = $row['linea'];
                            $line['ot'] = $row['ot'];
                            $line['lineaOt'] = $row['lineaOt'];
                            $line['cliente'] = $row['cliente'];
                            $line['nombreCliente'] = $row['nombreCliente'];
                            $line['pv'] = $row['pv'];
                            $pv = $row['pv'];
                            $queryPv = "SELECT nombre FROM pv WHERE pv.no = '$pv'";
                            $resultPv =  parent::datos($queryPv);
                            if ($resultPv) {
                                if ($resultPv->num_rows) {
                                    $rowPv = $resultPv->fetch_assoc();
                                    $line['nombrePv'] = $rowPv['nombre'];
                                } else {
                                    $line['nombrePv'] = 'Sin datos del punto de venta';
                                }
                            } else {
                                $line['nombrePv'] = 'Error en Api';
                            }
                            $line['firma'] = $row['firma'];
                            $line['usuario'] = $row['usuario'];
                            $usuarioId = $row['usuario'];
                            if ($usuarioId != 0) {
                                $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                                $result2 =  parent::datos($query2);
                                if ($result2) {
                                    if ($result2->num_rows) {
                                        $row2 = $result2->fetch_assoc();
                                        $line['nombreUsuario'] = $row2['nombre'];
                                        $line['apellidoUsuario'] = $row2['apellido'];
                                    } else {
                                        $line['nombreUsuario'] = '';
                                        $line['apellidoUsuario'] = '';
                                    }
                                } else {
                                    $line['nombreUsuario'] = 'Error en Api';
                                    $line['apellidoUsuario'] = 'Error en Api';
                                }
                            } else {
                                $line['nombreUsuario'] = 'Sin usuario';
                                $line['apellidoUsuario'] = 'Sin usuario';
                            }
                            $line['visible'] = $row['visible'];
                            $line['usuarioVisible'] = $row['usuarioVisible'];
                            $usuarioId2 = $row['usuarioVisible'];
                            if ($usuarioId2 != 0) {
                                $query3 = "SELECT * FROM usuarios WHERE id = $usuarioId2";
                                $result3 =  parent::datos($query3);
                                if ($result3) {
                                    if ($result3->num_rows) {
                                        $row3 = $result3->fetch_assoc();
                                        $line['nombreUsuarioVisible'] = $row3['nombre'];
                                        $line['apellidoUsuarioVisible'] = $row3['apellido'];
                                    } else {
                                        $line['nombreUsuarioVisible'] = '';
                                        $line['apellidoUsuarioVisible'] = '';
                                    }
                                } else {
                                    $line['nombreUsuarioVisible'] = 'Error en Api';
                                    $line['apellidoUsuarioVisible'] = 'Error en Api';
                                }
                            } else {
                                $line['nombreUsuarioVisible'] = 'Sin usuario';
                                $line['apellidoUsuarioVisible'] = 'Sin usuario';
                            }
                            $line['fecha'] = $row['fecha'];
                            $this->lineaOt = $row['lineaOt'];
                            $this->tablaFotos = "fotos" . $this->anoOt;
                            $this->tablaFirmas = "firmas" . $this->anoOt;
                            $this->tablaCarpetas = "carpetas" . $this->anoOt;
                            $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt' AND visible = 1";
                            $resultFotos =  parent::datos($queryFotos);
                            if ($resultFotos) {
                                $this->fotos = $resultFotos->num_rows;
                                $line['fotos'] = $this->fotos;
                            } else {
                                $line['fotos'] = 'Error en Api';
                            }
                            $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt' AND visible = 1";
                            $resultFirmas =  parent::datos($queryFirmas);
                            if ($resultFirmas) {
                                $this->firmas = $resultFirmas->num_rows;
                                $line['firmas'] = $this->firmas;
                            } else {
                                $line['firmas'] = 'Error en Api';
                            }
                            $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt' AND visible = 1";
                            $resultCarpetas =  parent::datos($queryCarpetas);
                            if ($resultCarpetas) {
                                $this->carpetas = $resultCarpetas->num_rows;
                                $line['carpetas'] = $this->carpetas;
                            } else {
                                $line['carpetas'] = 'Error en Api';
                            }
                            $this->lineasDeOt[$i] = $line;
                            $i++;
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($this->lineasDeOt);
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->okF('No existen líneas o puntos de venta en la base de datos que estén aprobados para su visualización');
                        $this->respuesta = $answer;
                    }
                } else {
                    $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
                    $this->error = $answer;
                }
            } else {
                $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla ot de la base de datos");
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error al ejecutar la función lineasApp: ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function fotos($ot, $lineaOt)
    {
        try {
            $ot = parent::sanitizar($ot);
            $lineaOt = parent::sanitizar($lineaOt);
            $this->ot = $ot;
            $this->lineaOt = $lineaOt;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->vFotos);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'fotos' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot' AND lineaActividad = '$this->lineaOt' AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../fotos/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $foto = [];
                            $foto['id'] = $row['id'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['link'] = 'http://localhost/dosxdos_app/fotos/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
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
                                    $answer = $respuestas->error_500('500 - Error en la función fotos(): No fue posible leer los datos del usuario que subió el archivo');
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
                                    $answer = $respuestas->error_500('500 - Error en la función fotos(): No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuarioVisible'] = $row2['nombre'];
                                $foto['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $foto['ano'] = $row['ano'];
                            $foto['fecha'] = $row['fecha'];
                            $this->vFotos[$i] = $foto;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->vFotos);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las fotos');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función fotos(): ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function firmas($ot, $lineaOt)
    {
        try {
            $ot = parent::sanitizar($ot);
            $lineaOt = parent::sanitizar($lineaOt);
            $this->ot = $ot;
            $this->lineaOt = $lineaOt;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->vFirmas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'firmas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot' AND lineaActividad = '$this->lineaOt' AND visible = 1";
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
                                    $answer = $respuestas->error_500('500 - Error en la función firmas(): No fue posible leer los datos del usuario que subió el archivo');
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
                                    $answer = $respuestas->error_500('500 - Error en la función firmas(): No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuarioVisible'] = $row2['nombre'];
                                $foto['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $foto['ano'] = $row['ano'];
                            $foto['fecha'] = $row['fecha'];
                            $this->vFirmas[$i] = $foto;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->vFirmas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las fotos');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función firmas(): ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function carpetas($ot, $lineaOt)
    {
        try {
            $ot = parent::sanitizar($ot);
            $lineaOt = parent::sanitizar($lineaOt);
            $this->ot = $ot;
            $this->lineaOt = $lineaOt;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->vCarpetas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'carpetas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot' AND lineaActividad = '$this->lineaOt' AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../carpetas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $foto = [];
                            $foto['id'] = $row['id'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['link'] = 'http://localhost/dosxdos_app/carpetas/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
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
                                    $answer = $respuestas->error_500('500 - Error en la función carpetas(): No fue posible leer los datos del usuario que subió el archivo');
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
                                    $answer = $respuestas->error_500('500 - Error en la función carpetas(): No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuarioVisible'] = $row2['nombre'];
                                $foto['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $foto['ano'] = $row['ano'];
                            $foto['fecha'] = $row['fecha'];
                            $this->vCarpetas[$i] = $foto;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->vCarpetas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las fotos');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función carpetas(): ' . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function carpetasOt($ot)
    {
        try {
            $ot = parent::sanitizar($ot);
            $this->ot = $ot;
            $query = "SELECT * FROM ot WHERE nombre = '$this->ot'";
            $result = parent::datos($query);
            if (!$result->num_rows) {
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->vCarpetas);
                $this->respuesta = $answer;
            } else {
                $row = mysqli_fetch_assoc($result);
                $this->table = 'carpetas' . $row['ano'];
                $query = "SELECT * FROM $this->table WHERE ot = '$this->ot' AND lineaActividad = 0 AND visible = 1";
                $result = parent::datos($query);
                if ($result) {
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pathArchivo = __DIR__ . "/../../carpetas/" . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
                        if (file_exists($pathArchivo)) {
                            $foto = [];
                            $foto['id'] = $row['id'];
                            $foto['nombre'] = $row['nombre'];
                            $foto['link'] = 'http://localhost/dosxdos_app/carpetas/' . $row['ano'] . '/' . $row['ot'] . '/' . $row['nombre'];
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
                                    $answer = $respuestas->error_500('500 - Error en la función carpetasOt(): No fue posible leer los datos del usuario que subió el archivo');
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
                                    $answer = $respuestas->error_500('500 - Error en la función carpetasOt(): No fue posible leer los datos del usuario que subió el archivo');
                                    $this->error = $answer;
                                    return;
                                }
                                $row2 = mysqli_fetch_assoc($result2);
                                $foto['nombreUsuarioVisible'] = $row2['nombre'];
                                $foto['apellidoUsuarioVisible'] = $row2['apellido'];
                            }
                            $foto['ano'] = $row['ano'];
                            $foto['fecha'] = $row['fecha'];
                            $this->vCarpetas[$i] = $foto;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->vCarpetas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql de las fotos');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - Error en la función carpetasOt(): ' . $th->getMessage());
            $this->error = $answer;
        }
    }
}

/*$Clientes = new Clientes;
$json = file_get_contents('./rest.json');
$Clientes->post($json);
print_r($Clientes->error);
print_r($Clientes->respuesta);*/

/*$Clientes = new Clientes;
$Clientes->tiposOt(32);
print 'TIPOS ---';
print_r($Clientes->tipos);
print '<br>';
print 'RESTRICCIONES ---';
print_r($Clientes->restricciones);
print '<br>';
print 'TIPOS OT CLIENTE ---';
print_r($Clientes->tiposOtCliente);
print '<br>';
print 'RESULTADO ---';
print_r($Clientes->error);
print_r($Clientes->respuesta);*/

/*$Clientes = new Clientes;
$Clientes->firmasCliente(43000008);
print_r($Clientes->error);
print_r($Clientes->respuesta);*/

/*$Clientes = new Clientes;
$Clientes->lineasApp('OT-21279');
print_r($Clientes->error);
print_r($Clientes->respuesta);*/

/*$Clientes = new Clientes;
$json = file_get_contents('./retro.json');
$Clientes->post($json);
print_r($Clientes->error);
print_r($Clientes->respuesta);*/
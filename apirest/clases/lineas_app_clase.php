<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class LineasApp extends Conexion
{
    public $table = '';
    public $id = '';
    public $ruta = '';
    public $linea = '';
    public $ot = '';
    public $lineaOt = '';
    public $cliente = '';
    public $nombreCliente = '';
    public $pv = '';
    public $firma = '';
    public $usuario = '';
    public $visible = '';
    public $usuarioVisible = '';
    public $fecha = '';
    public $lineas = [];
    public $anoOt = '';
    public $accion = '';
    public $tablaFotos = '';
    public $tablaFirmas = '';
    public $tablaCarpetas = '';
    public $fotos = '';
    public $firmas = '';
    public $carpetas = '';
    public $respuesta = '';
    public $error = '';


    public function lineasApp($ot)
    {
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
                $answer = $respuestas->okF($this->lineas);
                $this->respuesta = $answer;
                return;
            }
            $query = "SELECT * FROM $this->table WHERE ot = '$ot' AND anulada != 1 ORDER BY fecha DESC";
            $result =  parent::datos($query);
            if ($result) {
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
                            $line['nombrePv'] = '';
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
                    $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt'";
                    $resultFotos =  parent::datos($queryFotos);
                    if ($resultFotos) {
                        $this->fotos = $resultFotos->num_rows;
                        $line['fotos'] = $this->fotos;
                    } else {
                        $line['fotos'] = 'Error en Api';
                    }
                    $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt'";
                    $resultFirmas =  parent::datos($queryFirmas);
                    if ($resultFirmas) {
                        $this->firmas = $resultFirmas->num_rows;
                        $line['firmas'] = $this->firmas;
                    } else {
                        $line['firmas'] = 'Error en Api';
                    }
                    $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt'";
                    $resultCarpetas =  parent::datos($queryCarpetas);
                    if ($resultCarpetas) {
                        $this->carpetas = $resultCarpetas->num_rows;
                        $line['carpetas'] = $this->carpetas;
                    } else {
                        $line['carpetas'] = 'Error en Api';
                    }
                    $this->lineas[$i] = $line;
                    $i++;
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->lineas);
                $this->respuesta = $answer;
            } else {
                $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
                $this->error = $answer;
            }
        } else {
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla ot de la base de datos");
            $this->error = $answer;
        }
    }

    public function lineaApp($ot, $lineaOt)
    {
        $ot = parent::sanitizar($ot);
        $lineaOt = parent::sanitizar($lineaOt);
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
                $answer = $respuestas->okF($this->lineas);
                $this->respuesta = $answer;
                return;
            }
            $query = "SELECT * FROM $this->table WHERE ot = '$ot' AND lineaOt = $lineaOt";
            $result =  parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $row = $result->fetch_assoc();
                    $this->lineas['id'] = $row['id'];
                    $this->lineas['anulada'] = $row['anulada'];
                    $this->lineas['ruta'] = $row['ruta'];
                    $this->lineas['linea'] = $row['linea'];
                    $this->lineas['ot'] = $row['ot'];
                    $this->lineas['lineaOt'] = $row['lineaOt'];
                    $this->lineas['cliente'] = $row['cliente'];
                    $this->lineas['nombreCliente'] = $row['nombreCliente'];
                    $this->lineas['pv'] = $row['pv'];
                    $pv = $row['pv'];
                    $queryPv = "SELECT nombre FROM pv WHERE pv.no = '$pv'";
                    $resultPv =  parent::datos($queryPv);
                    if ($resultPv) {
                        if ($resultPv->num_rows) {
                            $rowPv = $resultPv->fetch_assoc();
                            $this->lineas['nombrePv'] = $rowPv['nombre'];
                        } else {
                            $this->lineas['nombrePv'] = '';
                        }
                    } else {
                        $this->lineas['nombrePv'] = 'Error en Api';
                    }
                    $this->lineas['firma'] = $row['firma'];
                    $this->lineas['usuario'] = $row['usuario'];
                    $usuarioId = $row['usuario'];
                    if ($usuarioId != 0) {
                        $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                        $result2 =  parent::datos($query2);
                        if ($result2) {
                            if ($result2->num_rows) {
                                $row2 = $result2->fetch_assoc();
                                $this->lineas['nombreUsuario'] = $row2['nombre'];
                                $this->lineas['apellidoUsuario'] = $row2['apellido'];
                            } else {
                                $this->lineas['nombreUsuario'] = '';
                                $this->lineas['apellidoUsuario'] = '';
                            }
                        } else {
                            $this->lineas['nombreUsuario'] = 'Error en Api';
                            $this->lineas['apellidoUsuario'] = 'Error en Api';
                        }
                    } else {
                        $this->lineas['nombreUsuario'] = 'Sin usuario';
                        $this->lineas['apellidoUsuario'] = 'Sin usuario';
                    }
                    $this->lineas['visible'] = $row['visible'];
                    $this->lineas['usuarioVisible'] = $row['usuarioVisible'];
                    $usuarioId2 = $row['usuarioVisible'];
                    if ($usuarioId2 != 0) {
                        $query3 = "SELECT * FROM usuarios WHERE id = $usuarioId2";
                        $result3 =  parent::datos($query3);
                        if ($result3) {
                            if ($result3->num_rows) {
                                $row3 = $result3->fetch_assoc();
                                $this->lineas['nombreUsuarioVisible'] = $row3['nombre'];
                                $this->lineas['apellidoUsuarioVisible'] = $row3['apellido'];
                            } else {
                                $this->lineas['nombreUsuarioVisible'] = '';
                                $this->lineas['apellidoUsuarioVisible'] = '';
                            }
                        } else {
                            $this->lineas['nombreUsuarioVisible'] = 'Error en Api';
                            $this->lineas['apellidoUsuarioVisible'] = 'Error en Api';
                        }
                    } else {
                        $this->lineas['nombreUsuarioVisible'] = 'Sin usuario';
                        $this->lineas['apellidoUsuarioVisible'] = 'Sin usuario';
                    }
                    $this->lineas['fecha'] = $row['fecha'];
                    $this->lineaOt = $row['lineaOt'];
                    $this->tablaFotos = "fotos" . $this->anoOt;
                    $this->tablaFirmas = "firmas" . $this->anoOt;
                    $this->tablaCarpetas = "carpetas" . $this->anoOt;
                    $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt'";
                    $resultFotos =  parent::datos($queryFotos);
                    if ($resultFotos) {
                        $this->fotos = $resultFotos->num_rows;
                        $this->lineas['fotos'] = $this->fotos;
                    } else {
                        $this->lineas['fotos'] = 'Error en Api';
                    }
                    $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt'";
                    $resultFirmas =  parent::datos($queryFirmas);
                    if ($resultFirmas) {
                        $this->firmas = $resultFirmas->num_rows;
                        $this->lineas['firmas'] = $this->firmas;
                    } else {
                        $this->lineas['firmas'] = 'Error en Api';
                    }
                    $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$ot' AND lineaActividad = '$this->lineaOt'";
                    $resultCarpetas =  parent::datos($queryCarpetas);
                    if ($resultCarpetas) {
                        $this->carpetas = $resultCarpetas->num_rows;
                        $this->lineas['carpetas'] = $this->carpetas;
                    } else {
                        $this->lineas['carpetas'] = 'Error en Api';
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->lineas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF($this->lineas);
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
    }

    public function lineasAppTotal($ano)
    {
        $ano = parent::sanitizar($ano);
        if ($ano >= 2023) {
            $_respuestas = new Respuestas;
            $this->table = "lineas" . $ano;
            $query = "SELECT * FROM $this->table WHERE anulada != 1 ORDER BY fecha DESC";
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
                                $line['nombrePv'] = '';
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
                        $this->ot = $row['ot'];
                        $this->lineaOt = $row['lineaOt'];
                        $queryOt = "SELECT * FROM ot WHERE nombre = '$this->ot'";
                        $resultOt =  parent::datos($queryOt);
                        if ($resultOt) {
                            if ($resultOt->num_rows) {
                                $rowOt = $resultOt->fetch_assoc();
                                $this->anoOt = $rowOt['ano'];
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->okF('Error en Api al consultar la línea: ' . $this->lineaOt . 'de la OT: ' . $this->ot);
                                $this->respuesta = $answer;
                                return;
                            }
                        }
                        $this->tablaFotos = "fotos" . $this->anoOt;
                        $this->tablaFirmas = "firmas" . $this->anoOt;
                        $this->tablaCarpetas = "carpetas" . $this->anoOt;
                        $queryFotos = "SELECT * FROM $this->tablaFotos WHERE ot = '$this->ot' AND lineaActividad = '$this->lineaOt'";
                        $resultFotos =  parent::datos($queryFotos);
                        if ($resultFotos) {
                            $this->fotos = $resultFotos->num_rows;
                            $line['fotos'] = $this->fotos;
                        } else {
                            $line['fotos'] = 'Error en Api';
                        }
                        $queryFirmas = "SELECT * FROM $this->tablaFirmas WHERE ot = '$this->ot' AND lineaActividad = '$this->lineaOt'";
                        $resultFirmas =  parent::datos($queryFirmas);
                        if ($resultFirmas) {
                            $this->firmas = $resultFirmas->num_rows;
                            $line['firmas'] = $this->firmas;
                        } else {
                            $line['firmas'] = 'Error en Api';
                        }
                        $queryCarpetas = "SELECT * FROM $this->tablaCarpetas WHERE ot = '$this->ot' AND lineaActividad = '$this->lineaOt'";
                        $resultCarpetas =  parent::datos($queryCarpetas);
                        if ($resultCarpetas) {
                            $this->carpetas = $resultCarpetas->num_rows;
                            $line['carpetas'] = $this->carpetas;
                        } else {
                            $line['carpetas'] = 'Error en Api';
                        }
                        $this->lineas[$i] = $line;
                        $i++;
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->lineas);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF($this->lineas);
                    $this->respuesta = $answer;
                }
            } else {
                $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
                $this->error = $answer;
            }
        } else {
            $respuestas = new Respuestas;
            $answer = $respuestas->okF($this->lineas);
            $this->respuesta = $answer;
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
                    if (!isset($datos['tabla']) || !isset($datos['id']) || !isset($datos['accion']) || !isset($datos['usuario'])) {
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
}

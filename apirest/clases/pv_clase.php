<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class Pv extends Conexion
{
    public $table = 'pv';
    public $id = '';
    public $no = '';
    public $nombre = '';
    public $direccion = '';
    public $area = '';
    public $sector = '';
    public $clasificacion = '';
    public $zona = '';
    public $telefono = '';
    public $movil = '';
    public $fax = '';
    public $email = '';
    public $no_escaparates_totales = '';
    public $no_escaparates_medidos = '';
    public $no_cubrealarmas = '';
    public $cp = '';
    public $obsoleta = '';
    public $latitud = '';
    public $longitud = '';
    public $usuario = '';
    public $pvs = [];
    public $respuesta = '';
    public $error = '';

    public function puntosDeVenta()
    {
        try {
            $query = "SELECT * FROM $this->table";
            $result =  parent::datos($query);
            if ($result) {
                $i = 0;
                $pv = [];
                while ($row = $result->fetch_assoc()) {
                    if (!$row['eliminado']) {
                        $pv['id'] = $row['id'];
                        $pv['no'] = $row['no'];
                        $pv['nombre'] = $row['nombre'];
                        $pv['direccion'] = $row['direccion'];
                        $pv['area'] = $row['area'];
                        $pv['sector'] = $row['sector'];
                        $pv['clasificacion'] = $row['clasificacion'];
                        $pv['zona'] = $row['zona'];
                        $pv['telefono'] = $row['telefono'];
                        $pv['movil'] = $row['movil'];
                        $pv['fax'] = $row['fax'];
                        $pv['email'] = $row['email'];
                        $pv['no_escaparates_totales'] = $row['no_escaparates_totales'];
                        $pv['no_escaparates_medidos'] = $row['no_escaparates_medidos'];
                        $pv['no_cubrealarmas'] = $row['no_cubrealarmas'];
                        $pv['cp'] = $row['cp'];
                        $pv['obsoleta'] = $row['obsoleta'];
                        $pv['latitud'] = $row['latitud'];
                        $pv['longitud'] = $row['longitud'];
                        $pv['usuario'] = $row['usuario'];
                        $usuarioId = $row['usuario'];
                        if ($usuarioId != 0) {
                            $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                            $result2 =  parent::datos($query2);
                            if ($result2) {
                                $row2 = $result2->fetch_assoc();
                                $pv['nombreUsuario'] = $row2['nombre'];
                                $pv['apellidoUsuario'] = $row2['apellido'];
                            } else {
                                $pv['nombreUsuario'] = 'Error en Api';
                                $pv['apellidoUsuario'] = 'Error en Api';
                            }
                        } else {
                            $pv['nombreUsuario'] = 'Sin usuario';
                            $pv['apellidoUsuario'] = 'Sin usuario';
                        }
                        $this->pvs[$i] = $pv;
                        $i++;
                    }
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($this->pvs);
                $this->respuesta = $answer;
            } else {
                $_respuestas = new Respuestas;
                $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $_respuestas = new Respuestas;
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor: " . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function puntoDeVenta($no)
    {
        try {
            $no = parent::sanitizar($no);
            $query = "SELECT * FROM $this->table WHERE $this->table.no = $no";
            $result =  parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $row = $result->fetch_assoc();
                    if (!$row['eliminado']) {
                        $this->pvs['id'] = $row['id'];
                        $this->pvs['no'] = $row['no'];
                        $this->pvs['nombre'] = $row['nombre'];
                        $this->pvs['direccion'] = $row['direccion'];
                        $this->pvs['area'] = $row['area'];
                        $this->pvs['sector'] = $row['sector'];
                        $this->pvs['clasificacion'] = $row['clasificacion'];
                        $this->pvs['zona'] = $row['zona'];
                        $this->pvs['telefono'] = $row['telefono'];
                        $this->pvs['movil'] = $row['movil'];
                        $this->pvs['fax'] = $row['fax'];
                        $this->pvs['email'] = $row['email'];
                        $this->pvs['no_escaparates_totales'] = $row['no_escaparates_totales'];
                        $this->pvs['no_escaparates_medidos'] = $row['no_escaparates_medidos'];
                        $this->pvs['no_cubrealarmas'] = $row['no_cubrealarmas'];
                        $this->pvs['cp'] = $row['cp'];
                        $this->pvs['obsoleta'] = $row['obsoleta'];
                        $this->pvs['latitud'] = $row['latitud'];
                        $this->pvs['longitud'] = $row['longitud'];
                        $this->pvs['usuario'] = $row['usuario'];
                        $usuarioId = $row['usuario'];
                        if ($usuarioId != 0) {
                            $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                            $result2 =  parent::datos($query2);
                            if ($result2) {
                                $row2 = $result2->fetch_assoc();
                                $this->pvs['nombreUsuario'] = $row2['nombre'];
                                $this->pvs['apellidoUsuario'] = $row2['apellido'];
                            } else {
                                $this->pvs['nombreUsuario'] = 'Error en Api';
                                $this->pvs['apellidoUsuario'] = 'Error en Api';
                            }
                        } else {
                            $this->pvs['nombreUsuario'] = 'Sin usuario';
                            $this->pvs['apellidoUsuario'] = 'Sin usuario';
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($this->pvs);
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->okF('PV eliminado');
                        $this->respuesta = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF($this->pvs);
                    $this->respuesta = $answer;
                }
            } else {
                $_respuestas = new Respuestas;
                $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
                $this->error = $answer;
            }
        } catch (\Throwable $th) {
            $_respuestas = new Respuestas;
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor: " . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function put($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['id']) || !isset($datos['no']) || !isset($datos['nombre']) || !isset($datos['direccion']) || !isset($datos['area']) || !isset($datos['sector']) || !isset($datos['clasificacion']) || !isset($datos['zona']) || !isset($datos['telefono']) || !isset($datos['movil']) || !isset($datos['fax']) || !isset($datos['email']) || !isset($datos['no_escaparates_totales']) || !isset($datos['no_escaparates_medidos']) || !isset($datos['no_cubrealarmas']) || !isset($datos['cp']) || !isset($datos['obsoleta']) || !isset($datos['latitud']) || !isset($datos['longitud']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->id = parent::sanitizar($datos['id']);
                $this->no = parent::sanitizar($datos['no']);
                $this->nombre = parent::sanitizar($datos['nombre']);
                $this->direccion = parent::sanitizar($datos['direccion']);
                $this->area = parent::sanitizar($datos['area']);
                $this->sector = parent::sanitizar($datos['sector']);
                $this->clasificacion = parent::sanitizar($datos['clasificacion']);
                $this->zona = parent::sanitizar($datos['zona']);
                $this->telefono = parent::sanitizar($datos['telefono']);
                $this->movil = parent::sanitizar($datos['movil']);
                $this->fax = parent::sanitizar($datos['fax']);
                $this->email = parent::sanitizar($datos['email']);
                $this->no_escaparates_totales = parent::sanitizar($datos['no_escaparates_totales']);
                $this->no_escaparates_medidos = parent::sanitizar($datos['no_escaparates_medidos']);
                $this->no_cubrealarmas = parent::sanitizar($datos['no_cubrealarmas']);
                $this->cp = parent::sanitizar($datos['cp']);
                $this->obsoleta = parent::sanitizar($datos['obsoleta']);
                $this->latitud = parent::sanitizar($datos['latitud']);
                $this->longitud = parent::sanitizar($datos['longitud']);
                $this->usuario = parent::sanitizar($datos['usuario']);
                $query = "UPDATE $this->table SET pv.no = '$this->no', nombre = '$this->nombre', direccion = '$this->direccion', area = '$this->area', sector = '$this->sector', clasificacion = '$this->clasificacion', zona = '$this->zona', telefono = '$this->telefono', movil = '$this->movil', fax = '$this->fax', email = '$this->email', no_escaparates_totales = '$this->no_escaparates_totales', no_escaparates_medidos = '$this->no_escaparates_medidos', no_cubrealarmas = '$this->no_cubrealarmas', cp = '$this->cp', obsoleta = '$this->obsoleta', latitud = '$this->latitud', longitud = '$this->longitud', usuario = '$this->usuario' WHERE id = $this->id";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('PV actualizado exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido actualizar el PV');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $_respuestas = new Respuestas;
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor: " . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function post($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['no']) || !isset($datos['nombre']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->no = parent::sanitizar($datos['no']);
                $this->nombre = parent::sanitizar($datos['nombre']);
                $this->direccion = parent::sanitizar($datos['direccion']);
                $this->area = parent::sanitizar($datos['area']);
                $this->sector = parent::sanitizar($datos['sector']);
                $this->clasificacion = parent::sanitizar($datos['clasificacion']);
                $this->zona = parent::sanitizar($datos['zona']);
                $this->telefono = parent::sanitizar($datos['telefono']);
                $this->movil = parent::sanitizar($datos['movil']);
                $this->fax = parent::sanitizar($datos['fax']);
                $this->email = parent::sanitizar($datos['email']);
                $this->no_escaparates_totales = parent::sanitizar($datos['no_escaparates_totales']);
                $this->no_escaparates_medidos = parent::sanitizar($datos['no_escaparates_medidos']);
                $this->no_cubrealarmas = parent::sanitizar($datos['no_cubrealarmas']);
                $this->cp = parent::sanitizar($datos['cp']);
                $this->obsoleta = parent::sanitizar($datos['obsoleta']);
                $this->latitud = parent::sanitizar($datos['latitud']);
                $this->longitud = parent::sanitizar($datos['longitud']);
                $this->usuario = parent::sanitizar($datos['usuario']);
                $query = "INSERT INTO $this->table (pv.no, nombre, direccion, area, sector, clasificacion, zona, telefono, movil, fax, email, no_escaparates_totales, no_escaparates_medidos, no_cubrealarmas, cp, obsoleta, latitud, longitud, usuario) VALUES ('$this->no', '$this->nombre','$this->direccion', '$this->area','$this->sector','$this->clasificacion','$this->zona','$this->telefono','$this->movil','$this->fax','$this->email','$this->no_escaparates_totales','$this->no_escaparates_medidos','$this->no_cubrealarmas','$this->cp','$this->obsoleta','$this->latitud','$this->longitud','$this->usuario')";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('PV creado exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido crear el PV');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $_respuestas = new Respuestas;
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor: " . $th->getMessage());
            $this->error = $answer;
        }
    }

    public function delete($json)
    {
        try {
            $_respuestas = new respuestas;
            $datos = json_decode($json, true);
            if (!isset($datos['id']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->id = parent::sanitizar($datos['id']);
                $this->usuario = parent::sanitizar($datos['usuario']);
                $eliminado = 1;
                $query = "UPDATE $this->table SET eliminado = $eliminado, usuario = '$this->usuario' WHERE id = $this->id";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('PV eliminado exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido eliminar el PV');
                    $this->error = $answer;
                }
            }
        } catch (\Throwable $th) {
            $_respuestas = new Respuestas;
            $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor: " . $th->getMessage());
            $this->error = $answer;
        }
    }
}

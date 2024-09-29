<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class ClientesDm extends Conexion
{
    public $table = 'clientes';
    public $id = '';
    public $cod = '';
    public $nombre = '';
    public $telefono = '';
    public $cif = '';
    public $contacto = '';
    public $contable = '';
    public $pago = '';
    public $usuario = '';
    public $eliminado = '';
    public $datos = [];
    public $respuesta = '';
    public $error = '';

    public function clientes()
    {
        try {
            $query = "SELECT * FROM $this->table";
            $result =  parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $i = 0;
                    $dato = [];
                    while ($row = $result->fetch_assoc()) {
                        if (!$row['eliminado']) {
                            $dato['id'] = $row['id'];
                            $dato['cod'] = $row['cod'];
                            $dato['nombre'] = $row['nombre'];
                            $dato['telefono'] = $row['telefono'];
                            $dato['cif'] = $row['cif'];
                            $dato['contacto'] = $row['contacto'];
                            $dato['contable'] = $row['contable'];
                            $dato['pago'] = $row['pago'];
                            $dato['usuario'] = $row['usuario'];
                            $usuarioId = $row['usuario'];
                            if ($usuarioId != 0) {
                                $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                                $result2 =  parent::datos($query2);
                                if ($result2) {
                                    $row2 = $result2->fetch_assoc();
                                    $dato['nombreUsuario'] = $row2['nombre'];
                                    $dato['apellidoUsuario'] = $row2['apellido'];
                                } else {
                                    $dato['nombreUsuario'] = 'Error en Api';
                                    $dato['apellidoUsuario'] = 'Error en Api';
                                }
                            } else {
                                $dato['nombreUsuario'] = 'Sin usuario';
                                $dato['apellidoUsuario'] = 'Sin usuario';
                            }
                            $this->datos[$i] = $dato;
                            $i++;
                        }
                    }
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($this->datos);
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF('No existen clientes');
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

    public function cliente($cod)
    {
        try {
            $cod = parent::sanitizar($cod);
            $query = "SELECT * FROM $this->table WHERE cod = $cod";
            $result =  parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $row = $result->fetch_assoc();
                    if (!$row['eliminado']) {
                        $this->datos['id'] = $row['id'];
                        $this->datos['cod'] = $row['cod'];
                        $this->datos['nombre'] = $row['nombre'];
                        $this->datos['telefono'] = $row['telefono'];
                        $this->datos['cif'] = $row['cif'];
                        $this->datos['contacto'] = $row['contacto'];
                        $this->datos['contable'] = $row['contable'];
                        $this->datos['pago'] = $row['pago'];
                        $this->datos['usuario'] = $row['usuario'];
                        $usuarioId = $row['usuario'];
                        if ($usuarioId != 0) {
                            $query2 = "SELECT * FROM usuarios WHERE id = $usuarioId";
                            $result2 =  parent::datos($query2);
                            if ($result2) {
                                $row2 = $result2->fetch_assoc();
                                $this->datos['nombreUsuario'] = $row2['nombre'];
                                $this->datos['apellidoUsuario'] = $row2['apellido'];
                            } else {
                                $this->datos['nombreUsuario'] = 'Error en Api';
                                $this->datos['apellidoUsuario'] = 'Error en Api';
                            }
                        } else {
                            $this->datos['nombreUsuario'] = 'Sin usuario';
                            $this->datos['apellidoUsuario'] = 'Sin usuario';
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($this->datos);
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->okF('Cliente eliminado');
                        $this->respuesta = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF('El cliente no existe');
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
            if (!isset($datos['cod'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->cod = parent::sanitizar($datos['cod']);
                $query = "SELECT * FROM $this->table WHERE cod = $this->cod";
                $result =  parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $row = $result->fetch_assoc();
                        $this->id = $row['id'];
                        $this->nombre = $row['nombre'];
                        $this->telefono = $row['telefono'];
                        $this->cif = $row['cif'];
                        $this->contacto = $row['contacto'];
                        $this->contable = $row['contable'];
                        $this->pago = $row['pago'];
                        $this->usuario = $row['usuario'];
                        $this->eliminado = $row['eliminado'];
                        if (isset($datos['cod'])) {
                            $this->cod = parent::sanitizar($datos['cod']);
                        }
                        if (isset($datos['nombre'])) {
                            $this->nombre = parent::sanitizar($datos['nombre']);
                        }
                        if (isset($datos['telefono'])) {
                            $this->telefono = parent::sanitizar($datos['telefono']);
                        }
                        if (isset($datos['cif'])) {
                            $this->cif = parent::sanitizar($datos['cif']);
                        }
                        if (isset($datos['contacto'])) {
                            $this->contacto = parent::sanitizar($datos['contacto']);
                        }
                        if (isset($datos['contable'])) {
                            $this->contable = parent::sanitizar($datos['contable']);
                        }
                        if (isset($datos['pago'])) {
                            $this->pago = parent::sanitizar($datos['pago']);
                        }
                        if (isset($datos['usuario'])) {
                            $this->usuario = parent::sanitizar($datos['usuario']);
                        }
                        if (isset($datos['eliminado'])) {
                            $this->eliminado = parent::sanitizar($datos['eliminado']);
                        }
                        $query = "UPDATE $this->table SET cod = '$this->cod', nombre = '$this->nombre', telefono = '$this->telefono', cif = '$this->cif', contacto = '$this->contacto', contable = '$this->contable', pago = '$this->pago', usuario = '$this->usuario', eliminado = '$this->eliminado' WHERE id = $this->id";
                        $result = parent::datos($query);
                        if ($result) {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->ok('Cliente actualizado exitosamente');
                            $this->respuesta = $answer;
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido actualizar el cliente');
                            $this->error = $answer;
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->okF('El cliente no existe');
                        $this->respuesta = $answer;
                    }
                } else {
                    $_respuestas = new Respuestas;
                    $answer = $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
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
            if (!isset($datos['cod']) || !isset($datos['nombre']) || !isset($datos['usuario'])) {
                $this->error = $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $this->id = parent::sanitizar($datos['id']);
                $this->cod = parent::sanitizar($datos['cod']);
                $this->nombre = parent::sanitizar($datos['nombre']);
                $this->telefono = parent::sanitizar($datos['telefono']);
                $this->cif = parent::sanitizar($datos['cif']);
                $this->contacto = parent::sanitizar($datos['contacto']);
                $this->contable = parent::sanitizar($datos['contable']);
                $this->pago = parent::sanitizar($datos['pago']);
                $this->usuario = parent::sanitizar($datos['usuario']);
                $query = "INSERT INTO $this->table (cod, nombre, telefono, cif, contacto, contable, pago, usuario) VALUES ('$this->cod', '$this->nombre','$this->telefono', '$this->cif','$this->contacto','$this->contable','$this->pago','$this->usuario')";
                $result = parent::datos($query);
                if ($result) {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok('Cliente creado exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido crear el cliente');
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
                    $answer = $respuestas->ok('Cliente eliminado exitosamente');
                    $this->respuesta = $answer;
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido eliminar el cliente');
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

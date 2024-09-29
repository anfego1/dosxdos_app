<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class usuarios extends conexion
{
    private $table = "usuarios";
    public $usuarioId = "";
    public $usuario = "";
    public $cod = "";
    public $contrasena = "";
    public $contrasenaActual = "";
    public $clase = "";
    public $correo = "";
    public $movil = "";
    public $nombre = "";
    public $apellido = "";
    public $imagen = "";
    public $activo = "";
    public $eliminado = "";

    public function usuarios()
    {
        $_respuestas = new Respuestas;
        $query = "SELECT id, cod, clase, correo, movil, nombre, apellido, imagen, activo, eliminado FROM $this->table";
        $result =  parent::datos($query);
        if ($result) {
            $datos = [];
            foreach ($result as $key => $value) {
                $datos[$key] = $value;
            }
            return $_respuestas->ok(parent::utf8($datos));
        } else {
            return $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar la base de datos");
        }
    }

    public function usuario($id)
    {
        $_respuestas = new Respuestas;
        $query = "SELECT id, cod, clase, correo, movil, nombre, apellido, imagen, activo, eliminado FROM $this->table WHERE id = $id";
        $result =  parent::datos($query);
        if ($result) {
            $datos = [];
            foreach ($result as $key => $value) {
                $datos[$key] = $value;
            }
            return $_respuestas->ok(parent::utf8($datos));
        } else {
            return $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar la base de datos");
        }
    }

    public function clientes()
    {
        $_respuestas = new Respuestas;
        $query = "SELECT * FROM $this->table WHERE clase = 'cliente' AND activo = 1 AND eliminado = 0";
        $result =  parent::datos($query);
        if ($result) {
            $datos = [];
            if ($result->num_rows) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $datos[$i]['id'] = $row['id'];
                    $datos[$i]['usuario'] = $row['usuario'];
                    $datos[$i]['cod'] = $row['cod'];
                    $cod = $row['cod'];
                    $queryNombreCliente = "SELECT * FROM clientes WHERE cod = $cod";
                    $resultNombreCliente =  parent::datos($queryNombreCliente);
                    if ($resultNombreCliente) {
                        if ($resultNombreCliente->num_rows) {
                            $rowNombreCliente = $resultNombreCliente->fetch_assoc();
                            $datos[$i]['nombreCliente'] = $rowNombreCliente['nombre'];
                        } else {
                            $datos[$i]['nombreCliente'] = '';
                        }
                    } else {
                        $datos[$i]['nombreCliente'] = 'Error en Api';
                    }
                    $datos[$i]['clase'] = $row['clase'];
                    $datos[$i]['correo'] = $row['correo'];
                    $datos[$i]['movil'] = $row['movil'];
                    $datos[$i]['nombre'] = $row['nombre'];
                    $datos[$i]['apellido'] = $row['apellido'];
                    $datos[$i]['imagen'] = $row['imagen'];
                    $i++;
                }
                return $_respuestas->ok($datos);
            } else {
                return $_respuestas->okF($datos);
            }
        } else {
            return $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar la base de datos para seleccionar los usuarios clientes");
        }
    }

    public function cliente($id)
    {
        $_respuestas = new Respuestas;
        $query = "SELECT * FROM $this->table WHERE id = $id";
        $result =  parent::datos($query);
        if ($result) {
            $datos = [];
            if ($result->num_rows) {
                $row = $result->fetch_assoc();
                $clase = $row['clase'];
                if ($clase == 'cliente') {
                    $datos['id'] = $row['id'];
                    $datos['usuario'] = $row['usuario'];
                    $datos['cod'] = $row['cod'];
                    $cod = $row['cod'];
                    $queryNombreCliente = "SELECT * FROM clientes WHERE cod = $cod";
                    $resultNombreCliente =  parent::datos($queryNombreCliente);
                    if ($resultNombreCliente) {
                        if ($resultNombreCliente->num_rows) {
                            $rowNombreCliente = $resultNombreCliente->fetch_assoc();
                            $datos['nombreCliente'] = $rowNombreCliente['nombre'];
                        } else {
                            $datos['nombreCliente'] = '';
                        }
                    } else {
                        $datos['nombreCliente'] = 'Error en Api';
                    }
                    $datos['clase'] = $row['clase'];
                    $datos['correo'] = $row['correo'];
                    $datos['movil'] = $row['movil'];
                    $datos['nombre'] = $row['nombre'];
                    $datos['apellido'] = $row['apellido'];
                    $datos['imagen'] = $row['imagen'];
                    $datos['activo'] = $row['activo'];
                    $datos['eliminado'] = $row['eliminado'];
                    return $_respuestas->ok($datos);
                } else {
                    return $_respuestas->okF($datos);
                }
            } else {
                return $_respuestas->okF($datos);
            }
        } else {
            return $_respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar la base de datos para seleccionar los usuarios clientes");
        }
    }

    public function post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (isset($datos['login'])) {
            if (!isset($datos['usuario']) || !isset($datos['contrasena'])) {
                return $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
            } else {
                $usuario = parent::sanitizar($datos['usuario']);
                $contrasena = parent::sanitizar($datos['contrasena']);
                $query = "SELECT * FROM $this->table WHERE usuario = '$usuario'";
                $result = parent::datos($query);
                if ($result) {
                    if ($result->num_rows) {
                        $datosR = [];
                        while ($row = mysqli_fetch_assoc($result)) {
                            $datosR['id'] = $row['id'];
                            $datosR['usuario'] = $row['usuario'];
                            $datosR['cod'] = $row['cod'];
                            $contrasenaR = $row['contrasena'];
                            $datosR['clase'] = $row['clase'];
                            $datosR['correo'] = $row['correo'];
                            $datosR['movil'] = $row['movil'];
                            $datosR['nombre'] = $row['nombre'];
                            $datosR['apellido'] = $row['apellido'];
                            $datosR['imagen'] = $row['imagen'];
                            $datosR['activo'] = $row['activo'];
                            $datosR['eliminado'] = $row['eliminado'];
                        }
                        if ($contrasenaR == $contrasena) {
                            return $_respuestas->ok(parent::utf8($datosR));
                        } else {
                            return $_respuestas->error_401();
                        }
                    } else {
                        return $_respuestas->error_401("No autorizado en la api intermedia, es probable que el usuario ingresado no exista ");
                    }
                } else {
                    return $_respuestas->error_401("Error en la consulta SQL de la api intermedia");
                }
            }
        }

        if (!isset($datos['usuario']) || !isset($datos['cod']) || !isset($datos['contrasena']) || !isset($datos['clase']) || !isset($datos['correo']) || !isset($datos['nombre'])) {
            return $_respuestas->error_400("Error en el formato de los datos que has enviado - O no has especificado un dato obligatorio");
        } else {
            $this->usuario = $datos['usuario'];
            $this->cod = $datos['cod'];
            $this->contrasena = $datos['contrasena'];
            $this->clase = $datos['clase'];
            $this->correo = $datos['correo'];
            $this->nombre = $datos['nombre'];
            if (isset($datos['movil'])) {
                $this->apellido = $datos['movil'];
            }
            if (isset($datos['apellido'])) {
                $this->imagen = $datos['apellido'];
            }
            if (isset($datos['imagen'])) {
                $this->imagen = $datos['imagen'];
            }
            if (!isset($datos['imagen'])) {
                $this->imagen = 0;
            }
            if (isset($datos['activo'])) {
                $this->imagen = $datos['activo'];
            }
            $result = $this->postUsuario();
            if ($result) {
                return $_respuestas->ok("El usuario con id $result ha sido creado exitosamente");
            } else {
                return $_respuestas->error_500("Error en la base de datos al intentar generar el nuevo registro - Verifica si el usuario ya existe");
            }
        }
    }

    private function postUsuario()
    {
        $query = "INSERT INTO $this->table (usuario, cod, contrasena, clase, correo, movil, nombre, apellido, imagen, activo) VALUES ('$this->usuario', '$this->cod','$this->contrasena', '$this->clase','$this->correo','$this->movil','$this->nombre','$this->apellido','$this->imagen')";
        $result = parent::datosPost($query);
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function put($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (!isset($datos['usuarioId']) || !isset($datos['contrasenaActual'])) {
            return $_respuestas->error_400("Formato incorrecto de los datos o no se especificó un dato obligatorio");
        } else {
            $this->usuarioId = parent::sanitizar($datos['usuarioId']);
            $this->contrasenaActual = $datos['contrasenaActual'];
            $query = "SELECT * FROM $this->table WHERE id = $this->usuarioId";
            $result = parent::datos($query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $this->usuario = $row['usuario'];
                    $this->cod = $row['cod'];
                    $this->contrasena = $row['contrasena'];
                    $this->clase = $row['clase'];
                    $this->correo = $row['correo'];
                    $this->movil = $row['movil'];
                    $this->nombre = $row['nombre'];
                    $this->apellido = $row['apellido'];
                    $this->imagen = $row['imagen'];
                    $this->activo = $row['activo'];
                }
            } else {
                return $_respuestas->error_500("Error en la Api intermedia - No se ha podido consultar los datos originales del usuario para poder mantener los datos que no serán actualizados");
            }

            if ($this->contrasena == $this->contrasenaActual) {
                if (isset($datos['usuario'])) {
                    $this->usuario = $datos['usuario'];
                }
                if (isset($datos['cod'])) {
                    $this->cod = $datos['cod'];
                }
                if (isset($datos['contrasena'])) {
                    $this->contrasena = $datos['contrasena'];
                }
                if (isset($datos['clase'])) {
                    $this->clase = $datos['clase'];
                }
                if (isset($datos['correo'])) {
                    $this->correo = $datos['correo'];
                }
                if (isset($datos['movil'])) {
                    $this->movil = $datos['movil'];
                }
                if (isset($datos['nombre'])) {
                    $this->nombre = $datos['nombre'];
                }
                if (isset($datos['apellido'])) {
                    $this->apellido = $datos['apellido'];
                }
                
                if (isset($datos['activo'])) {
                    $this->activo = $datos['activo'];
                }

                $result = $this->putUsuario();
                if ($result) {
                    if (isset($datos['imagen'])) {
                        try {
                            $imagenAnterior = __DIR__ . "/../../" . $this->imagen;
                            $this->imagen = $datos['imagen'];
                            $partes = explode(";base64,", $this->imagen);
                            $extension = explode('/', mime_content_type($this->imagen))[1];
                            $file_base64 = base64_decode($partes[1]);
                            $nombre = "img_usuarios/" . uniqid() . uniqid() . "." . $extension;
                            $filePath = __DIR__ . "/../../";
                            $file = $filePath . $nombre;
                            $query = "UPDATE $this->table SET imagen=\"$nombre\" WHERE id=\"$this->usuarioId\"";
                            $result = parent::datosPost($query);
                            unlink($imagenAnterior);
                            file_put_contents($file, $file_base64);
                            return $_respuestas->ok("El usuario con id $this->usuarioId ha sido actualizado exitosamente");
                        } catch (\Throwable $th) {
                            return $_respuestas->error_500("Error al realizar el cambio del archivo de imagen del usuario");
                        }
                    } else {
                        return $_respuestas->ok("El usuario con id $this->usuarioId ha sido actualizado exitosamente");
                    } 
                } else {
                    return $_respuestas->error_500("Error en la base de datos al intentar actualizar el registro");
                }
            } else {
                return $_respuestas->error_401("Error: La contraseña actual no coincide con la contraseña guardada en nuestra base de datos");
            }
        }
    }

    private function putUsuario()
    {
        $query = "UPDATE $this->table SET usuario = '$this->usuario', cod = '$this->cod', contrasena = '$this->contrasena', clase = '$this->clase', correo = '$this->correo', movil = '$this->movil', nombre = '$this->nombre', apellido = '$this->apellido', imagen = '$this->imagen', activo = '$this->activo' WHERE id = $this->usuarioId";
        $result = parent::datos($query);
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function delete($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (!isset($datos['usuarioId'])) {
            return $_respuestas->error_400("No se especificó el id del usuario que se debe eliminar");
        } else {
            $this->usuarioId = $datos['usuarioId'];
            $result = $this->deleteUsuario();
            if ($result) {
                return $_respuestas->ok("El usuario con id $this->usuarioId ha sido eliminado exitosamente");
            } else {
                return $_respuestas->error_500("Error en la base de datos al intentar eliminar el registro");
            }
        }
    }

    private function deleteUsuario()
    {
        $query = "DELETE FROM $this->table WHERE id = $this->usuarioId";
        $result = parent::datos($query);
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }
}

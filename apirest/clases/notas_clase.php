<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class Notas extends Conexion
{
    public $table = 'appnotas';
    public $id = '';
    public $ot = '';
    public $linea = '';
    public $nota = [];
    public $respuesta = '';
    public $error = '';

    public function nota($ot, $linea)
    {
        $respuestas = new Respuestas;
        $ot = parent::sanitizar($ot);
        $linea = parent::sanitizar($linea);
        $query = "SELECT * FROM $this->table WHERE ot = '$ot' AND linea = '$linea'";
        $result =  parent::datos($query);
        if ($result) {
            if ($result->num_rows) {
                $row = $result->fetch_assoc();
                $this->nota["id"] = $row['id'];
                $this->nota["ot"] = $row['ot'];
                $this->nota["linea"] = $row['linea'];
                $this->nota["nota"] = $row['nota'];

                $answer = $respuestas->ok($this->nota);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->okF($this->nota);
                $this->respuesta = $answer;
            }
        } else {
            $answer = $respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
            $this->error = $answer;
        }
    }

    public function addNota($ot, $linea, $nota)
    {
        $respuestas = new Respuestas;
        try {
            $ot = parent::sanitizar($ot);
            $linea = parent::sanitizar($linea);
            $nota = parent::sanitizar($nota);
            $query = "SELECT * FROM $this->table WHERE ot = '$ot' AND linea = '$linea'";
            $result =  parent::datos($query);
            if ($result) {
                if ($result->num_rows) {
                    $row = $result->fetch_assoc();
                    $this->nota["id"] = $row['id'];
                    $notaId = $row['id'];
                    $this->nota["ot"] = $row['ot'];
                    $this->nota["linea"] = $row['linea'];
                    $this->nota["nota"] = $row['nota'];
                        $query = "UPDATE $this->table SET nota = '$nota' WHERE id = $notaId";
                        $result = parent::datos($query);
                        if ($result) {
                            $answer = $respuestas->ok('La Nota se ha actualizado exitosamente');
                            $this->respuesta = $answer;
                        } else {
                            $answer = $respuestas->error_500('500 - Error en la consulta sql, no se ha podido actualizar la nota');
                            $this->error = $answer;
                        }
                } else {
                    $query = "INSERT INTO $this->table (ot, linea, nota) VALUES (\"$ot\", \"$linea\", \"$nota\")";
                    $result = parent::datosPost($query);
                    if ($result) {
                        $answer = $respuestas->ok('La Nota se ha creado exitosamente');
                        $this->respuesta = $answer;
                    } else {
                        $answer = $respuestas->error_500('500 - Error, la nota no se ha podido crear');
                        $this->error = $answer;
                    }
                }
            } else {
                $answer = $respuestas->error_500("Error en la Api intermedia - Error interno en el servidor al conectar o consultar con la tabla " . $this->table .  " de la base de datos");
                $this->error = $answer;
            }
        } catch (Exception $e) {
            $answer = $respuestas->error_500('500 - Error en la función addNota() de la clase Notas: ' . $e->getMessage());
            $this->error = $answer;
        }
    }
}

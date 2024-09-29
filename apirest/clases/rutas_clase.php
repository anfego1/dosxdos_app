<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";
require_once "crm_clase.php";

class Rutas extends Conexion
{
    public $respuesta = '';
    public $error = '';

    public function rutas()
    {
        try {
            $crm = new Crm;
            $rutasCrm = [];
            $crm->get("rutas");
            if ($crm->estado) {
                $rutasCrm = $crm->respuesta[1]['data'];
                $iRutas = 0;
                foreach ($rutasCrm as $rute) {
                    $rutasCrm[$iRutas]['No.'] = $rutasCrm[$iRutas]['Name'];
                    $rutasCrm[$iRutas]['Descripcion'] = $rutasCrm[$iRutas]['Name'];
                    $rutasCrm[$iRutas]['RutaActiva'] = true;
                    $iRutas++;
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($rutasCrm);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    public function rutasActivas()
    {
        try {
            $crm = new Crm;
            $rutasCrm = [];
            $crm->get("rutas");
            if ($crm->estado) {
                $rutasCrm = $crm->respuesta[1]['data'];
                $iRutas = 0;
                foreach ($rutasCrm as $rute) {
                    $rutasCrm[$iRutas]['No.'] = $rutasCrm[$iRutas]['Name'];
                    $rutasCrm[$iRutas]['Descripcion'] = $rutasCrm[$iRutas]['Name'];
                    $rutasCrm[$iRutas]['RutaActiva'] = true;
                    $iRutas++;
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($rutasCrm);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    public function rutasInactivas()
    {
        $rutasCrm = [];
        $respuestas = new Respuestas;
        $answer = $respuestas->ok($rutasCrm);
        $this->respuesta = $answer;
    }

    public function ruta($numeroRuta)
    {
        try {
            $crm = new Crm;
            $camposRuta = "Name";
            $query = "SELECT $camposRuta FROM Rutas WHERE Name=\"$numeroRuta\"";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $ruta = $crm->respuesta[1]['data'][0];
                    if ($ruta) {
                        $ruta['No.'] = $ruta['Name'];
                        $ruta['Descripcion'] = $ruta['Name'];
                        $ruta['RutaActiva'] = true;
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($ruta);
                        $this->respuesta = $answer;
                    }
                } else {
                    $ruta = [];
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($ruta);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    public function montadores($ruta)
    {
        try {
            $crm = new Crm;
            $camposRuta = "Name";
            $query = "SELECT $camposRuta FROM Rutas WHERE Name=\"$ruta\"";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $ruta = $crm->respuesta[1]['data'][0];
                    if ($ruta) {
                        $ruta['No.'] = $ruta['Name'];
                        $ruta['Descripcion'] = $ruta['Name'];
                        $ruta['RutaActiva'] = true;
                        $link = "/crm/v5/Rutas/" . $ruta['id'] . "/Montadores4?fields=Montadores_relacionados";
                        $crm->get("link", $link);
                        if ($crm->estado) {
                            $montadoresVector = [];
                            if ($crm->respuesta[1]) {
                                $data = $crm->respuesta[1]['data'];
                                $iMontadoresData = 0;
                                foreach ($data as $montadorData) {
                                    $idMontador = $montadorData['Montadores_relacionados']['id'];
                                    $nombreMontador = $montadorData['Montadores_relacionados']['name'];
                                    $montadoresVector[$iMontadoresData]['id'] = $idMontador;
                                    $montadoresVector[$iMontadoresData]['name'] = $nombreMontador;
                                    $montadoresVector[$iMontadoresData]['Proyecto'] = $ruta['Name'];
                                    $montadoresVector[$iMontadoresData]['LineaRuta'] = 0;
                                    $montadoresVector[$iMontadoresData]['Nombre'] = $nombreMontador;
                                    $montadoresVector[$iMontadoresData]['Alias'] = $nombreMontador;
                                    $montadorVector = [];
                                    $camposMontador = "Name,C_digo_del_montador,Apellido_del_montador,Email,Tel_fono_del_montador";
                                    $query = "SELECT $camposMontador FROM Montadores WHERE id = $idMontador";
                                    $crm->query($query);
                                    if ($crm->estado) {
                                        $montadorVector = $crm->respuesta[1]['data'][0];
                                    } else {
                                        $respuestas = new Respuestas;
                                        $answer = $respuestas->error_500($crm->respuestaError);
                                        $this->error = $answer;
                                        break;
                                        return;
                                    }
                                    $montadoresVector[$iMontadoresData]['Montador'] = $montadorVector['C_digo_del_montador'];
                                    $iMontadoresData++;
                                }
                                $respuestas = new Respuestas;
                                $answer = $respuestas->ok($montadoresVector);
                                $this->respuesta = $answer;
                            } else {
                                $respuestas = new Respuestas;
                                $answer = $respuestas->ok($montadoresVector);
                                $this->respuesta = $answer;
                            }
                        } else {
                            $respuestas = new Respuestas;
                            $answer = $respuestas->error_500($crm->respuestaError);
                            $this->error = $answer;
                            return;
                        }
                    } else {
                        $ruta = [];
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($ruta);
                        $this->respuesta = $answer;
                    }
                } else {
                    $montadoresVector = [];
                    $respuestas = new Respuestas;
                    $answer = $respuestas->ok($montadoresVector);
                    $this->respuesta = $answer;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    public function totalMontadores()
    {
        try {
            $crm = new Crm;
            $rutasCrm = [];
            $crm->get("rutas");
            if ($crm->estado) {
                $rutasCrm = $crm->respuesta[1]['data'];
                $totalMontadoresVector = [];
                foreach ($rutasCrm as $rute) {
                    $ruteName = $rute['Name'];
                    $ruteId = $rute['id'];
                    $link = "/crm/v5/Rutas/" . $ruteId . "/Montadores4?fields=Montadores_relacionados";
                    $crm->get("link", $link);
                    if ($crm->estado) {
                        if ($crm->respuesta[1]) {
                            $data = $crm->respuesta[1]['data'];
                            $montadoresVector = [];
                            $iMontadoresData = 0;
                            $numMontadores = count($data);
                            if ($numMontadores == 1) {
                                $idMontador = $data[0]['Montadores_relacionados']['id'];
                                $nombreMontador = $data[0]['Montadores_relacionados']['name'];
                                $montadoresVector[0] = $ruteName;
                                $montadoresVector[1] = 0;
                                $montadorVector = [];
                                $camposMontador = "Name,C_digo_del_montador,Apellido_del_montador,Email,Tel_fono_del_montador";
                                $query = "SELECT $camposMontador FROM Montadores WHERE id = $idMontador";
                                $crm->query($query);
                                if ($crm->estado) {
                                    $montadorVector = $crm->respuesta[1]['data'][0];
                                } else {
                                    $respuestas = new Respuestas;
                                    $answer = $respuestas->error_500($crm->respuestaError);
                                    $this->error = $answer;
                                    break;
                                    return;
                                }
                                $montadoresVector[2] = $montadorVector['C_digo_del_montador'];
                                $montadoresVector[3] = $nombreMontador;
                                $montadoresVector[4] = "NO APLICA";
                                $montadoresVector[5] = "No";
                                $montadoresVector[6] = $nombreMontador;
                                $totalMontadoresVector[$ruteName] = $montadoresVector;
                            } else if ($numMontadores > 1) {
                                foreach ($data as $montadorData) {
                                    $idMontador = $montadorData['Montadores_relacionados']['id'];
                                    $nombreMontador = $montadorData['Montadores_relacionados']['name'];
                                    $montadoresVector[$iMontadoresData]['id'] = $idMontador;
                                    $montadoresVector[$iMontadoresData]['name'] = $nombreMontador;
                                    $montadoresVector[$iMontadoresData]['Proyecto'] = $ruteName;
                                    $montadoresVector[$iMontadoresData]['LineaRuta'] = 0;
                                    $montadoresVector[$iMontadoresData]['Nombre'] = $nombreMontador;
                                    $montadoresVector[$iMontadoresData]['Alias'] = $nombreMontador;
                                    $montadorVector = [];
                                    $camposMontador = "Name,C_digo_del_montador,Apellido_del_montador,Email,Tel_fono_del_montador";
                                    $query = "SELECT $camposMontador FROM Montadores WHERE id = $idMontador";
                                    $crm->query($query);
                                    if ($crm->estado) {
                                        $montadorVector = $crm->respuesta[1]['data'][0];
                                    } else {
                                        $respuestas = new Respuestas;
                                        $answer = $respuestas->error_500($crm->respuestaError);
                                        $this->error = $answer;
                                        break;
                                        return;
                                    }
                                    $montadoresVector[$iMontadoresData]['Montador'] = $montadorVector['C_digo_del_montador'];
                                    $iMontadoresData++;
                                }
                                $totalMontadoresVector[$ruteName] = $montadoresVector;
                            }
                        } else {
                            $totalMontadoresVector[$ruteName] = [];
                        }
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500($crm->respuestaError);
                        $this->error = $answer;
                        break;
                        return;
                    }
                }
                $respuestas = new Respuestas;
                $answer = $respuestas->ok($totalMontadoresVector);
                $this->respuesta = $answer;
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }

    public function montador($codMontador)
    {
        try {
            $crm = new Crm;
            $montadorVector = [];
            $camposMontador = "Name,C_digo_del_montador,Apellido_del_montador,Email,Tel_fono_del_montador";
            $query = "SELECT $camposMontador FROM Montadores WHERE C_digo_del_montador = \"$codMontador\"";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $montadorVector = $crm->respuesta[1]['data'][0];
                    $MontadorId = $montadorVector['id'];
                    $link = "/crm/v5/Montadores/" . $MontadorId . "/Rutas14?fields=Rutas";
                    $crm->get("link", $link);
                    if ($crm->estado) {
                        $rutasDelMontador = [];
                        if ($crm->respuesta[1]) {
                            $rutasDelMontadorData = $crm->respuesta[1]['data'];
                            $iRutasDelMontador = 0;
                            foreach ($rutasDelMontadorData as $rutaDelMontador) {
                                $rutasDelMontador[$iRutasDelMontador]['id'] = $rutasDelMontadorData[$iRutasDelMontador]['Rutas']['id'];
                                $rutasDelMontador[$iRutasDelMontador]['name'] = $rutasDelMontadorData[$iRutasDelMontador]['Rutas']['name'];
                                $rutasDelMontador[$iRutasDelMontador]['No.'] = $rutasDelMontadorData[$iRutasDelMontador]['Rutas']['name'];
                                $rutasDelMontador[$iRutasDelMontador]['Descripcion'] = $rutasDelMontadorData[$iRutasDelMontador]['Rutas']['name'];
                                $rutasDelMontador[$iRutasDelMontador]['RutaActiva'] = true;
                                $iRutasDelMontador++;
                            }
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($rutasDelMontador);
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->error_500($crm->respuestaError);
                        $this->error = $answer;
                        return;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->okF("No existen montadores con el código proporcionado");
                    $this->error = $answer;
                    return;
                }
            } else {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($crm->respuestaError);
                $this->error = $answer;
                return;
            }
        } catch (\Throwable $th) {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500($th);
            $this->error = $answer;
            return;
        }
    }
}

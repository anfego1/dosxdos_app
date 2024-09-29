<?php

require_once "conexion_clase.php";
require_once "respuestas_clase.php";

class Ot extends Conexion
{
    public $url = "http://172.16.16.156:8047/DynamicsNAV110App/WS/dos.dos/Codeunit/WebPageFunctionsWS";
    // "http://central.iidos.com:8047/DynamicsNAV110App/WS/PRUEBA%20230724/Codeunit/WebPageFunctionsWS"
    public $ot = [];
    public $lineas = [];
    public $respuesta = '';
    public $error = '';

    public function leerOt($CodOt)
    {
        if (extension_loaded('soap')) {
            // Request parameters :
            $NavUsername = "USERAPP1";
            $NavAccessKey = "WkksQz7t4tRrnJJN9V+QhlOUMV0UD7lvPrFu6MNYHwc=";
            $CodeunitMethod = "LeerOTs";
            $params = array(
                'codigoOT' => $CodOt,
                'outJsonOTs' => ''
            );
            // SOAP request header
            $options = array(
                'authentication' => SOAP_AUTHENTICATION_BASIC,
                'login' => $NavUsername,
                'password' => $NavAccessKey,
                'trace' => 1,
                'exception' => 0,
            );
            try {
                $client = new SoapClient(trim($this->url), $options);
                if ($client) {
                    $soap_response = $client->__soapCall($CodeunitMethod, array('parameters' => $params));
                    $respuesta_string = $soap_response->outJsonOTs;
                    if ($respuesta_string) {
                        $respuesta = json_decode($respuesta_string, true);
                        foreach ($respuesta as $Ots) {
                            foreach ($Ots as $Ot) {
                                $this->ot["no"] = $Ot["No."];
                                $this->ot["tipoProyecto"] = $Ot["TipoProyecto"];
                                $this->ot["descripcion"] = $Ot["Descripcion"];
                                $this->ot["comunidad"] = $Ot["Comunidad"];
                                $this->ot["estado"] = $Ot["Estado"];
                                $this->ot["facturaNombre"] = $Ot["FacturaNombre"];
                                $this->ot["facturaCliente"] = $Ot["FacturaCliente"];
                                $this->ot["fechaCreacion"] = $Ot["FechaCreacion"];
                                $this->ot["firma"] = $Ot["Firma"];
                                $this->ot["mes"] = $Ot["Mes"];
                                $this->ot["observaciones"] = $Ot["Observaciones"];
                                $this->ot["solicitadoPor"] = $Ot["SolicitadoPor"];
                                $this->ot["tipoOT"] = $Ot["TipoOT"];
                                $this->ot["subtipoOT"] = $Ot["SubtipoOT"];
                                $this->ot["usuarioCreador"] = $Ot["UsuarioCreador"];
                            }
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($this->ot);
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->okF($this->ot);
                        $this->respuesta = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error al crear el objeto SoapClient en la api intermedia');
                    $this->error = $answer;
                }
            } catch (SoapFault $soapFault) {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($soapFault);
                $this->error = $answer;
            }
        } else {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - No existe o no está configurada la extensión Soap en el servidor donde está alojada la api intermedia');
            $this->error = $answer;
        }
    }

    public function leerLineasOt($CodOt)
    {
        if (extension_loaded('soap')) {
            // Request parameters :
            $NavUsername = "USERAPP1";
            $NavAccessKey = "WkksQz7t4tRrnJJN9V+QhlOUMV0UD7lvPrFu6MNYHwc=";
            $CodeunitMethod = "LeerActividadesOT";
            $params = array(
                'codigoOT' => $CodOt,
                'outJsonLineasOT' => ''
            );
            // SOAP request header
            $options = array(
                'authentication' => SOAP_AUTHENTICATION_BASIC,
                'login' => $NavUsername,
                'password' => $NavAccessKey,
                'trace' => 1,
                'exception' => 0,
            );
            try {
                $client = new SoapClient(trim($this->url), $options);
                if ($client) {
                    $soap_response = $client->__soapCall($CodeunitMethod, array('parameters' => $params));
                    $respuesta_string = $soap_response->outJsonLineasOT;
                    if ($respuesta_string) {
                        $respuesta = json_decode($respuesta_string, true);
                        $i = 0;
                        if (isset($respuesta['LineasOT']['LineaOT']['No.'])) {
                            foreach ($respuesta as $LineasOT) {
                                foreach ($LineasOT as $ArrLineaOT) {
                                    $this->lineas[$i]["ot"] = $ArrLineaOT["No."];
                                    $this->lineas[$i]["linea"] = $ArrLineaOT["No.Linea"];
                                    $this->lineas[$i]["pv"] = $ArrLineaOT["No.PuntoVenta"];
                                    $this->lineas[$i]["observaciones"] = $ArrLineaOT["Observaciones"];
                                    $this->lineas[$i]["anulada"] = $ArrLineaOT["Anulada"];
                                    $i++;
                                }
                            }
                        } else {
                            foreach ($respuesta as $LineasOT) {
                                foreach ($LineasOT as $ArrLineaOT) {
                                    foreach ($ArrLineaOT as $LineaOT) {
                                        $this->lineas[$i]["ot"] = $LineaOT["No."];
                                        $this->lineas[$i]["linea"] = $LineaOT["No.Linea"];
                                        $this->lineas[$i]["pv"] = $LineaOT["No.PuntoVenta"];
                                        $this->lineas[$i]["observaciones"] = $LineaOT["Observaciones"];
                                        $this->lineas[$i]["anulada"] = $LineaOT["Anulada"];
                                        $i++;
                                    }
                                }
                            }
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
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error al crear el objeto SoapClient en la api intermedia');
                    $this->error = $answer;
                }
            } catch (SoapFault $soapFault) {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($soapFault);
                $this->error = $answer;
            }
        } else {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - No existe o no está configurada la extensión Soap en el servidor donde está alojada la api intermedia');
            $this->error = $answer;
        }
    }

    public function leerOts()
    {
        if (extension_loaded('soap')) {
            // Request parameters :
            $NavUsername = "USERAPP1";
            $NavAccessKey = "WkksQz7t4tRrnJJN9V+QhlOUMV0UD7lvPrFu6MNYHwc=";
            $CodeunitMethod = "LeerOTs";
            $params = array(
                'codigoOT' => '',
                'outJsonOTs' => ''
            );
            $context = stream_context_create([
                'http' => [
                    'user_agent' => 'PHPSoapClient',
                    'timeout' => 28800,
                ],
            ]);
            // SOAP request header
            $options = array(
                'authentication' => SOAP_AUTHENTICATION_BASIC,
                'login' => $NavUsername,
                'password' => $NavAccessKey,
                'trace' => 1,
                'exception' => 0,
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
            );
            try {
                $client = new SoapClient(trim($this->url), $options);
                if ($client) {
                    $soap_response = $client->__soapCall($CodeunitMethod, array('parameters' => $params));
                    $respuesta_string = $soap_response->outJsonOTs;
                    if ($respuesta_string) {
                        $respuesta = json_decode($respuesta_string, true);
                        $ots = $respuesta["OTs"]["OT"];
                        $i = 0;
                        foreach ($ots as $Ot) {
                            $this->ot[$i]["no"] = $Ot["No."];
                            $this->ot[$i]["tipoProyecto"] = $Ot["TipoProyecto"];
                            $this->ot[$i]["descripcion"] = $Ot["Descripcion"];
                            $this->ot[$i]["comunidad"] = $Ot["Comunidad"];
                            $this->ot[$i]["estado"] = $Ot["Estado"];
                            $this->ot[$i]["facturaNombre"] = $Ot["FacturaNombre"];
                            $this->ot[$i]["facturaCliente"] = $Ot["FacturaCliente"];
                            $this->ot[$i]["fechaCreacion"] = $Ot["FechaCreacion"];
                            $this->ot[$i]["firma"] = $Ot["Firma"];
                            $this->ot[$i]["mes"] = $Ot["Mes"];
                            $this->ot[$i]["observaciones"] = $Ot["Observaciones"];
                            $this->ot[$i]["solicitadoPor"] = $Ot["SolicitadoPor"];
                            $this->ot[$i]["tipoOT"] = $Ot["TipoOT"];
                            $this->ot[$i]["subtipoOT"] = $Ot["SubtipoOT"];
                            $this->ot[$i]["usuarioCreador"] = $Ot["UsuarioCreador"];
                            $i++;
                        }
                        $respuestas = new Respuestas;
                        $answer = $respuestas->ok($this->ot);
                        $this->respuesta = $answer;
                    } else {
                        $respuestas = new Respuestas;
                        $answer = $respuestas->okF($this->ot);
                        $this->respuesta = $answer;
                    }
                } else {
                    $respuestas = new Respuestas;
                    $answer = $respuestas->error_500('500 - Error al crear el objeto SoapClient en la api intermedia');
                    $this->error = $answer;
                }
            } catch (SoapFault $soapFault) {
                $respuestas = new Respuestas;
                $answer = $respuestas->error_500($soapFault);
                $this->error = $answer;
            }
        } else {
            $respuestas = new Respuestas;
            $answer = $respuestas->error_500('500 - No existe o no está configurada la extensión Soap en el servidor donde está alojada la api intermedia');
            $this->error = $answer;
        }
    }
}

//OT-22989
//OT-22526
/*$ot = new Ot;
$ot->leerLineasOt('OT-22989');
print_r($ot->respuesta);*/

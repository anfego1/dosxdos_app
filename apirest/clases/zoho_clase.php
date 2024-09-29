<?php

require_once 'respuestas_clase.php';
require_once "conexion_clase.php";

class Zoho extends conexion
{
    private $codigo;
    private $cliente;
    private $tokens;
    private $dominioApi;
    private $dominioCliente;
    private $masDatos = false;
    public $datos = [];
    public $respuesta;
    private $respuestaFinal;

    function __construct()
    {
        try {
            $this->cliente = file_get_contents('./clases/cliente_zoho.json');
            //$this->cliente = file_get_contents('cliente_zoho.json');
            $this->cliente = json_decode($this->cliente, true);
            $this->tokens = file_get_contents('./clases/tokens_zoho.json');
            //$this->tokens = file_get_contents('tokens_zoho.json');
            $this->tokens = json_decode($this->tokens, true);
            $this->codigo = file_get_contents('./clases/code_zoho.json');
            //$this->codigo = file_get_contents('code_zoho.json');
            $this->codigo = json_decode($this->codigo, true);
            $this->dominioApi = $this->tokens['api_domain'];
            $this->dominioCliente = $this->codigo['accounts-server'];
            $this->respuestaFinal = new Respuestas;
        } catch (\Throwable $th) {
            $this->respuesta = "Error al construir la clase de Zoho: " . $th->getMessage();
        }
    }

    private function renovarToken()
    {
        try {
            $urlNuevoToken = $this->dominioCliente . '/oauth/v2/token';
            $fields = array(
                'refresh_token' => $this->tokens['refresh_token'],
                'client_id' => $this->cliente['client_id'],
                'client_secret' => $this->cliente['client_secret'],
                'grant_type' => 'refresh_token'
            );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $urlNuevoToken);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            //print($response);
            if (!$response) {
                $this->respuesta = 'Error de seguridad en la API de Zoho al renovar el token; ahora es necesario renovar la autorización de la API de Zoho';
                return false;
            } else {
                $this->respuesta = json_decode($response, true);
                if (isset($this->respuesta['access_token'])) {
                    $this->tokens['access_token'] = $this->respuesta['access_token'];
                    $this->tokens['scope'] = $this->respuesta['scope'];
                    $this->tokens['api_domain'] = $this->respuesta['api_domain'];
                    $this->tokens['token_type'] = $this->respuesta['token_type'];
                    $this->tokens['expires_in'] = $this->respuesta['expires_in'];
                    $jsonTokens = json_encode($this->tokens);
                    if (file_put_contents('./clases/tokens_zoho.json', $jsonTokens) === false) {
                        $this->respuesta = "Error al escribir el token de acceso.";
                        return false;
                    } else {
                        $this->respuesta = "Token escrito correctamente";
                        return true;
                    }
                } else {
                    if (isset($this->respuesta['error'])) {
                        $this->respuesta = "Error en la api de Zoho al solicitar el nuevo token: " . $this->respuesta['error'];
                    } else {
                        $this->respuesta = "Error en la api de Zoho al solicitar el nuevo token";
                    }
                    return false;
                }
            }
        } catch (\Throwable $th) {
            $this->respuesta = "Error en la función renovarToken: " . $th->getMessage();
            return false;
        }
    }

    public function get($link)
    {
        try {
            $url = $this->dominioApi . $link;
            $accessToken = $this->tokens['access_token'];
            $authorizationHeader = "Authorization: Zoho-oauthtoken $accessToken";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorizationHeader));
            $response = curl_exec($curl);
            curl_close($curl);
            $this->respuesta = json_decode($response, true);
            if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                $this->respuesta = "Token de acceso inválido en la api de Zoho";
                $renovar = $this->renovarToken();
                if ($renovar) {
                    return $this->get($link);
                } else {
                    $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                    return $respuestaFinal;
                }
            } else if ($this->respuesta === false) {
                $this->respuesta = "Error en la comunicación con la API de Zoho: " . curl_error($curl);
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else if (isset($this->respuesta['status']) && $this->respuesta['status'] == 'error') {
                $this->respuesta = "Error en la API de Zoho: " . $this->respuesta['code'] . " " . $this->respuesta['message'];
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else {
                if (isset($this->respuesta['info']['more_records'])) {
                    if ($this->respuesta['info']['more_records']) {
                        $datos = $this->respuesta['data'];
                        $this->masDatos = true;
                        $page = 2;
                        while ($this->masDatos) {
                            $pagina = '&page=' . $page;
                            $url = $this->dominioApi . $link . $pagina;
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $url);
                            curl_setopt($curl, CURLOPT_HTTPGET, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorizationHeader));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $this->respuesta = json_decode($response, true);
                            if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                                $this->respuesta = "Token de acceso inválido en la api de Zoho";
                                $renovar = $this->renovarToken();
                                if ($renovar) {
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $url);
                                    curl_setopt($curl, CURLOPT_HTTPGET, true);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorizationHeader));
                                    $response = curl_exec($curl);
                                    curl_close($curl);
                                    $this->respuesta = json_decode($response, true);
                                    if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                                        $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                                    }
                                } else {
                                    $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                                    return $respuestaFinal;
                                }
                            } else if ($this->respuesta === false) {
                                $this->respuesta = "Error en la comunicación con la API de Zoho: " . curl_error($curl);
                                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                                return $respuestaFinal;
                            } else if (isset($this->respuesta['status']) && $this->respuesta['status'] == 'error') {
                                $this->respuesta = "Error en la API de Zoho: " . $this->respuesta['code'] . " " . $this->respuesta['message'];
                                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                                return $respuestaFinal;
                            } else {
                                $datos = array_merge($datos, $this->respuesta['data']);
                                if ($this->respuesta['info']['more_records']) {
                                    $this->masDatos = true;
                                    $page++;
                                } else {
                                    $this->masDatos = false;
                                }
                            }
                        }
                        $respuestaFinal = $this->respuestaFinal->ok($datos);
                        return $respuestaFinal;
                    } else {
                        $respuestaFinal = $this->respuestaFinal->ok($this->respuesta);
                        return $respuestaFinal;
                    }
                } else {
                    $respuestaFinal = $this->respuestaFinal->ok($this->respuesta);
                    return $respuestaFinal;
                }
            }
        } catch (\Throwable $th) {
            $this->respuesta = "Error en la función get: " . $th->getMessage();
            $respuestaFinal = $this->respuestaFinal->error_500($this->respuesta);
            return $respuestaFinal;
        }
    }

    public function put($link, $json)
    {
        try {
            $url = $this->dominioApi . $link;
            $accessToken = $this->tokens['access_token'];
            $authorizationHeader = "Authorization: Zoho-oauthtoken $accessToken";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                $authorizationHeader,
                "Content-Type: application/json",
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            $response = curl_exec($curl);
            curl_close($curl);
            $this->respuesta = json_decode($response, true);
            if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                $this->respuesta = "Token de acceso inválido en la api de Zoho";
                $renovar = $this->renovarToken();
                if ($renovar) {
                    return $this->put($link, $json);
                } else {
                    $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                    return $respuestaFinal;
                }
            } else if ($this->respuesta === false) {
                $this->respuesta = "Error en la comunicación con la API de Zoho: " . curl_error($curl);
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else if (isset($this->respuesta['status']) && $this->respuesta['status'] == 'error') {
                $this->respuesta = "Error en la API de Zoho: " . $this->respuesta['code'] . " " . $this->respuesta['message'];
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else {
                $respuestaFinal = $this->respuestaFinal->ok($this->respuesta);
                return $respuestaFinal;
            }
        } catch (\Throwable $th) {
            $this->respuesta = "Error en la función put: " . $th->getMessage();
            $respuestaFinal = $this->respuestaFinal->error_500($this->respuesta);
            return $respuestaFinal;
        }
    }

    public function post($link, $json)
    {
        try {
            $url = $this->dominioApi . $link;
            $accessToken = $this->tokens['access_token'];
            $authorizationHeader = "Authorization: Zoho-oauthtoken $accessToken";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                $authorizationHeader,
                "Content-Type: application/json",
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            $response = curl_exec($curl);
            curl_close($curl);
            $this->respuesta = json_decode($response, true);
            if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                $this->respuesta = "Token de acceso inválido en la api de Zoho";
                $renovar = $this->renovarToken();
                if ($renovar) {
                    return $this->post($link, $json);
                } else {
                    $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                    return $respuestaFinal;
                }
            } else if ($this->respuesta === false) {
                $this->respuesta = "Error en la comunicación con la API de Zoho: " . curl_error($curl);
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else if (isset($this->respuesta['status']) && $this->respuesta['status'] == 'error') {
                $this->respuesta = "Error en la API de Zoho: " . $this->respuesta['code'] . " " . $this->respuesta['message'];
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else {
                $respuestaFinal = $this->respuestaFinal->ok($this->respuesta);
                return $respuestaFinal;
            }
        } catch (\Throwable $th) {
            $this->respuesta = "Error en la función post: " . $th->getMessage();
            $respuestaFinal = $this->respuestaFinal->error_500($this->respuesta);
            return $respuestaFinal;
        }
    }

    public function delete($link)
    {
        try {
            $url = $this->dominioApi . $link;
            $accessToken = $this->tokens['access_token'];
            $authorizationHeader = "Authorization: Zoho-oauthtoken $accessToken";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorizationHeader));
            $response = curl_exec($curl);
            curl_close($curl);
            $this->respuesta = json_decode($response, true);
            if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                $this->respuesta = "Token de acceso inválido en la api de Zoho";
                $renovar = $this->renovarToken();
                if ($renovar) {
                    return $this->get($link);
                } else {
                    $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                    return $respuestaFinal;
                }
            } else if ($this->respuesta === false) {
                $this->respuesta = "Error en la comunicación con la API de Zoho: " . curl_error($curl);
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else if (isset($this->respuesta['status']) && $this->respuesta['status'] == 'error') {
                $this->respuesta = "Error en la API de Zoho: " . $this->respuesta['code'] . " " . $this->respuesta['message'];
                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                return $respuestaFinal;
            } else {
                if (isset($this->respuesta['info']['more_records'])) {
                    if ($this->respuesta['info']['more_records']) {
                        $datos = $this->respuesta['data'];
                        $this->masDatos = true;
                        $page = 2;
                        while ($this->masDatos) {
                            $pagina = '&page=' . $page;
                            $url = $this->dominioApi . $link . $pagina;
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $url);
                            curl_setopt($curl, CURLOPT_HTTPGET, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorizationHeader));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $this->respuesta = json_decode($response, true);
                            if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                                $this->respuesta = "Token de acceso inválido en la api de Zoho";
                                $renovar = $this->renovarToken();
                                if ($renovar) {
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $url);
                                    curl_setopt($curl, CURLOPT_HTTPGET, true);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorizationHeader));
                                    $response = curl_exec($curl);
                                    curl_close($curl);
                                    $this->respuesta = json_decode($response, true);
                                    if (isset($this->respuesta['code']) && $this->respuesta['code'] == 'INVALID_TOKEN') {
                                        $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                                    }
                                } else {
                                    $respuestaFinal = $this->respuestaFinal->error_401($this->respuesta);
                                    return $respuestaFinal;
                                }
                            } else if ($this->respuesta === false) {
                                $this->respuesta = "Error en la comunicación con la API de Zoho: " . curl_error($curl);
                                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                                return $respuestaFinal;
                            } else if (isset($this->respuesta['status']) && $this->respuesta['status'] == 'error') {
                                $this->respuesta = "Error en la API de Zoho: " . $this->respuesta['code'] . " " . $this->respuesta['message'];
                                $respuestaFinal = $this->respuestaFinal->okF($this->respuesta);
                                return $respuestaFinal;
                            } else {
                                $datos = array_merge($datos, $this->respuesta['data']);
                                if ($this->respuesta['info']['more_records']) {
                                    $this->masDatos = true;
                                    $page++;
                                } else {
                                    $this->masDatos = false;
                                }
                            }
                        }
                        $respuestaFinal = $this->respuestaFinal->ok($datos);
                        return $respuestaFinal;
                    } else {
                        $respuestaFinal = $this->respuestaFinal->ok($this->respuesta);
                        return $respuestaFinal;
                    }
                } else {
                    $respuestaFinal = $this->respuestaFinal->ok($this->respuesta);
                    return $respuestaFinal;
                }
            }
        } catch (\Throwable $th) {
            $this->respuesta = "Error en la función get: " . $th->getMessage();
            $respuestaFinal = $this->respuestaFinal->error_500($this->respuesta);
            return $respuestaFinal;
        }
    }
}
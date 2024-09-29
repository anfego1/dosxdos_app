<?php

class Respuestas
{
    public  $response = [];

    public function ok($valor = '200 - Solicitud exitosa')
    {
        $this->response[0] = true;
        $this->response[1] = $valor;
        $this->response[2] = 200;
        return $this->response;
    }

    public function okF($valor = '200 - Solicitud exitosa')
    {
        $this->response[0] = false;
        $this->response[1] = $valor;
        $this->response[2] = 200;
        return $this->response;
    }

    public function error_400($valor = "400 - Datos incompletos o incorrectos en la solicitud a la api intermedia")
    {
        $this->response[0] = false;
        $this->response[1] = $valor;
        $this->response[2] = 400;
        return $this->response;
    }

    public function error_401($valor = "401 - No autorizado en la api intermedia")
    {
        $this->response[0] = false;
        $this->response[1] = $valor;
        $this->response[2] = 401;
        return $this->response;
    }

    public function error_405($valor = "405 - Método no permitido")
    {
        $this->response[0] = false;
        $this->response[1] = $valor;
        $this->response[2] = 405;
        return $this->response;
    }

    public function error_500($valor = "500 - Error interno del servidor")
    {
        $this->response[0] = false;
        $this->response[1] = $valor;
        $this->response[2] = 500;
        return $this->response;
    }
}

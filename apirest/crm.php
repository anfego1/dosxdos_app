<?php

require_once 'config.php';
require_once 'clases/crm_clase.php';
require_once 'clases/respuestas_clase.php';

$crm = new Crm;
$respuestas = new respuestas;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["modulos"])) {
        $crm->get('modulos');
        if ($crm->estado) {
            $cod = $crm->respuesta[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuesta);
            print $jsonRespuesta;
        } else {
            $cod = $crm->respuestaError[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    } else if (isset($_GET["empresas"])) {
        $crm->get('empresas');
        if ($crm->estado) {
            $cod = $crm->respuesta[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuesta);
            print $jsonRespuesta;
        } else {
            $cod = $crm->respuestaError[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    } else if (isset($_GET["ots"])) {
        $crm->get('ots');
        if ($crm->estado) {
            $cod = $crm->respuesta[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuesta);
            print $jsonRespuesta;
        } else {
            $cod = $crm->respuestaError[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    } else if (isset($_GET["eliminarLineas"])) {
        $crm->get('ots');
        if ($crm->estado) {
            $cod = $crm->respuesta[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuesta);
            print $jsonRespuesta;
        } else {
            $cod = $crm->respuestaError[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    } else if (isset($_GET["otEliminada"]) && isset($_GET["idOt"])) {
        $query = "SELECT Product_Name FROM Products WHERE OT_relacionada = " . $_GET["idOt"];
        $crm->query($query);
        if ($crm->estado) {
            $lineas = $crm->respuesta[1]['data'];
            $ids = '';
            foreach ($lineas as $linea) {
                $ids .= $linea['id'] . ',';
            }
            $ids = substr($ids, 0, -1);
            $crm->eliminar('lineas', $ids);
            if ($crm->estado) {
                $cod = $crm->respuesta[2];
                http_response_code($cod);
                $jsonRespuesta = json_encode($crm->respuesta);
                print $jsonRespuesta;
            } else {
                $cod = $crm->respuestaError[2];
                http_response_code($cod);
                $jsonRespuesta = json_encode($crm->respuestaError);
                print $jsonRespuesta;
            }
        } else {
            $cod = $crm->respuestaError[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    } else {
        $datos = $respuestas->error_400();
        http_response_code(400);
        print json_encode($datos);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $postBody = file_get_contents("php://input");
    $body = json_decode($postBody, true);
    if (isset($body['actualizarOt'])) {
        $datosJson = [];
        $datosJson['data'][0]['id'] = $body["crmId"];
        $datosJson['data'][0]['Fecha_de_previsi_n'] = $body["montaje"];
        $datosJson['data'][0]['Departamentos_relacionados'] = $body["Departamentos_relacionados"];
        $json = json_encode($datosJson);
        $datos = $crm->actualizar('actualizarOt', $json);
        if ($crm->estado) {
            $cod = $crm->respuesta[2];
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuesta);
            print $jsonRespuesta;
        } else {
            $cod = $crm->respuestaError[2];
            //$crm->respuestaError[3] = $json;
            http_response_code($cod);
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    } else if (isset($body['actualizarIncidencia'])) {
        $link = '/bigin/v2/Pipelines';
        $datosJson = [];
        $datosJson['data'][0]['id'] = $body["tratoId"];
        $datosJson['data'][0]['Pipeline']['id'] = 688829000000405985;
        $datosJson['data'][0]['OTs'] = $body["ot"];
        $datosJson['data'][0]['Firma'] = $body["firma"];
        $datosJson['data'][0]['Account_Name'] = $body["nombreCliente"];
        $datosJson['data'][0]['C_digo_de_Empresa'] = $body["cliente"];
        $datosJson['data'][0]['Descripci_n_OT'] = $body["descripcion"];
        $datosJson['data'][0]['Closing_Date'] = $body["fechaCierre"];
        $datosJson['data'][0]['Sub_Pipeline'] = 'Incidencias (OTs)';
        $datosJson['data'][0]['Stage'] = 'Nuevas incidencias';
        $datosJson['data'][0]['Tipo_de_OT'][] = $body["tipoOt"];
        $datosJson['data'][0]['Raz_n_de_la_incidencia'] = $body["nombre"];
        // Obtiene la fecha actual
        $fechaActual = new DateTime();
        // Obtiene los componentes de la fecha y hora
        $año = $fechaActual->format('Y'); // Año
        $mes = $fechaActual->format('m'); // Mes
        $dia = $fechaActual->format('d'); // Día
        $hora = $fechaActual->format('H'); // Hora en formato de 24 horas
        $minuto = $fechaActual->format('i'); // Minuto
        // Concatena los componentes en un string
        $codigoFecha = $año . $mes . $dia . $hora . $minuto;
        // Genera un código aleatorio de 6 dígitos
        $codigoAleatorio = sprintf(mt_rand(0, 99));
        // Combina ambos códigos
        $codigoFinal = $codigoFecha . 0 . $codigoAleatorio;
        $datosJson['data'][0]['Ticket'] = $codigoFinal;
        $datosJson['data'][0]['Deal_Name'] = $body["ot"] . " - " . $body["nombreCliente"] . " - " . $body["firma"] . " - " . $codigoFinal;
        if (isset($body["descripcionIncidencia"])) {
            $datosJson['data'][0]['Description'] = $body["descripcionIncidencia"];
        }
        $datosJson['data'][0]['Prioridad'] = $body["prioridad"];
        $json = json_encode($datosJson);
        $datos = $zoho->put($link, $json);
        if (isset($datos[1]['data'][0]['status']) && isset($datos[1]['data'][0]['code']) && $datos[1]['data'][0]['status'] == 'error' && $datos[1]['data'][0]['code'] == 'RECORD_IN_BLUEPRINT') {
            $datos = $zoho->put($link, $json);
        }
        if ($datos[1]) {
            if (isset($datos[1]['data'][0]['status']) && $datos[1]['data'][0]['status'] == 'error') {
                $datos[0] = false;
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            } else {
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            }
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else {
        $datos = $_respuestas->error_400();
        http_response_code(400);
        echo json_encode($datos);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $body = json_decode($postBody, true);
    if (isset($body['actualizarComunicacion'])) {
        $link = '/bigin/v2/Pipelines';
        $datosJson = [];
        $datosJson['data'][0]['Owner']['id'] = 688829000000388001;
        $datosJson['data'][0]['Deal_Name'] = $body["ot"] . " " . $body["descripcion"];
        $datosJson['data'][0]['Pipeline']['id'] = 688829000000032043;
        $datosJson['data'][0]['OTs'] = $body["ot"];
        $datosJson['data'][0]['Firma'] = $body["firma"];
        $datosJson['data'][0]['Account_Name'] = $body["nombreCliente"];
        $datosJson['data'][0]['C_digo_de_Empresa'] = $body["cliente"];
        $datosJson['data'][0]['Description'] = $body["descripcion"];
        $datosJson['data'][0]['Closing_Date'] = $body["fechaCierre"];
        $datosJson['data'][0]['Sub_Pipeline'] = 'Clientes (OTs)';
        $datosJson['data'][0]['Stage'] = 'Nuevas oportunidades';
        $datosJson['data'][0]['Tipo_de_OT'][] = $body["tipoOt"];
        $json = json_encode($datosJson);
        $datos = $zoho->post($link, $json);
        if (isset($datos[1]['data'][0]['status']) && isset($datos[1]['data'][0]['code']) && $datos[1]['data'][0]['status'] == 'error' && $datos[1]['data'][0]['code'] == 'RECORD_IN_BLUEPRINT') {
            $datos = $zoho->put($link, $json);
        }
        if ($datos[1]) {
            if (isset($datos[1]['data'][0]['status']) && $datos[1]['data'][0]['status'] == 'error') {
                $datos[0] = false;
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            } else {
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            }
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($body['actualizarIncidencia'])) {
        $link = '/bigin/v2/Pipelines';
        $datosJson = [];
        $datosJson['data'][0]['Owner']['id'] = 688829000000388001;
        $datosJson['data'][0]['Pipeline']['id'] = 688829000000405985;
        $datosJson['data'][0]['OTs'] = $body["ot"];
        $datosJson['data'][0]['Firma'] = $body["firma"];
        $datosJson['data'][0]['Account_Name'] = $body["nombreCliente"];
        $datosJson['data'][0]['C_digo_de_Empresa'] = $body["cliente"];
        $datosJson['data'][0]['Descripci_n_OT'] = $body["descripcion"];
        $datosJson['data'][0]['Closing_Date'] = $body["fechaCierre"];
        $datosJson['data'][0]['Sub_Pipeline'] = 'Incidencias (OTs)';
        $datosJson['data'][0]['Stage'] = 'Nuevas incidencias';
        $datosJson['data'][0]['Tipo_de_OT'][] = $body["tipoOt"];
        $datosJson['data'][0]['Raz_n_de_la_incidencia'] = $body["nombre"];
        // Obtiene la fecha actual
        $fechaActual = new DateTime();
        // Obtiene los componentes de la fecha y hora
        $año = $fechaActual->format('Y'); // Año
        $mes = $fechaActual->format('m'); // Mes
        $dia = $fechaActual->format('d'); // Día
        $hora = $fechaActual->format('H'); // Hora en formato de 24 horas
        $minuto = $fechaActual->format('i'); // Minuto
        // Concatena los componentes en un string
        $codigoFecha = $año . $mes . $dia . $hora . $minuto;
        // Genera un código aleatorio de 6 dígitos
        $codigoAleatorio = sprintf(mt_rand(0, 99));
        // Combina ambos códigos
        $codigoFinal = $codigoFecha . 0 . $codigoAleatorio;
        $datosJson['data'][0]['Ticket'] = $codigoFinal;
        $datosJson['data'][0]['Deal_Name'] = $body["ot"] . " - " . $body["nombreCliente"] . " - " . $body["firma"] . " - " . $codigoFinal;
        if (isset($body["descripcionIncidencia"])) {
            $datosJson['data'][0]['Description'] = $body["descripcionIncidencia"];
        }
        $datosJson['data'][0]['Prioridad'] = $body["prioridad"];
        $json = json_encode($datosJson);
        $datos = $zoho->post($link, $json);
        if (isset($datos[1]['data'][0]['status']) && isset($datos[1]['data'][0]['code']) && $datos[1]['data'][0]['status'] == 'error' && $datos[1]['data'][0]['code'] == 'RECORD_IN_BLUEPRINT') {
            $datos = $zoho->post($link, $json);
        }
        if ($datos[1]) {
            if (isset($datos[1]['data'][0]['status']) && $datos[1]['data'][0]['status'] == 'error') {
                $datos[0] = false;
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            } else {
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            }
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($body['crmAgregarOt'])) {
        $link = '/bigin/v2/Pipelines';
        $datosJson = [];
        $datosJson['data'][0]['Owner']['id'] = 688829000000388001;
        $datosJson['data'][0]['Pipeline']['id'] = 688829000000405985;
        $datosJson['data'][0]['OTs'] = $body["ot"];
        $datosJson['data'][0]['Firma'] = $body["firma"];
        $datosJson['data'][0]['Account_Name'] = $body["nombreCliente"];
        $datosJson['data'][0]['C_digo_de_Empresa'] = $body["cliente"];
        $datosJson['data'][0]['Descripci_n_OT'] = $body["descripcion"];
        $datosJson['data'][0]['Closing_Date'] = $body["fechaCierre"];
        $datosJson['data'][0]['Sub_Pipeline'] = 'Incidencias (OTs)';
        $datosJson['data'][0]['Stage'] = 'Nuevas incidencias';
        $datosJson['data'][0]['Tipo_de_OT'][] = $body["tipoOt"];
        $datosJson['data'][0]['Raz_n_de_la_incidencia'] = $body["nombre"];
        // Obtiene la fecha actual
        $fechaActual = new DateTime();
        // Obtiene los componentes de la fecha y hora
        $año = $fechaActual->format('Y'); // Año
        $mes = $fechaActual->format('m'); // Mes
        $dia = $fechaActual->format('d'); // Día
        $hora = $fechaActual->format('H'); // Hora en formato de 24 horas
        $minuto = $fechaActual->format('i'); // Minuto
        // Concatena los componentes en un string
        $codigoFecha = $año . $mes . $dia . $hora . $minuto;
        // Genera un código aleatorio de 6 dígitos
        $codigoAleatorio = sprintf(mt_rand(0, 99));
        // Combina ambos códigos
        $codigoFinal = $codigoFecha . 0 . $codigoAleatorio;
        $datosJson['data'][0]['Ticket'] = $codigoFinal;
        $datosJson['data'][0]['Deal_Name'] = $body["ot"] . " - " . $body["nombreCliente"] . " - " . $body["firma"] . " - " . $codigoFinal;
        if (isset($body["descripcionIncidencia"])) {
            $datosJson['data'][0]['Description'] = $body["descripcionIncidencia"];
        }
        $datosJson['data'][0]['Prioridad'] = $body["prioridad"];
        $json = json_encode($datosJson);
        $datos = $zoho->post($link, $json);
        if (isset($datos[1]['data'][0]['status']) && isset($datos[1]['data'][0]['code']) && $datos[1]['data'][0]['status'] == 'error' && $datos[1]['data'][0]['code'] == 'RECORD_IN_BLUEPRINT') {
            $datos = $zoho->post($link, $json);
        }
        if ($datos[1]) {
            if (isset($datos[1]['data'][0]['status']) && $datos[1]['data'][0]['status'] == 'error') {
                $datos[0] = false;
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            } else {
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            }
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else {
        $datos = $_respuestas->error_400();
        http_response_code(400);
        echo json_encode($datos);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if (isset($_GET["otEliminada"]) && isset($_GET["idOt"])) {
        $query = "SELECT Product_Name FROM Products WHERE OT_relacionada = " . $_GET["idOt"];
        $crm->query($query);
        if ($crm->estado) {
            $lineas = $crm->respuesta[1]['data'];
            $ids = '';
            foreach ($lineas as $linea) {
                $ids .= $linea['id'] . ',';
            }
            $ids = substr($ids, 0, -1);
            $crm->eliminar('lineas', $ids);
            if ($crm->estado) {
                $jsonRespuesta = json_encode($crm->respuesta);
                print $jsonRespuesta;
            } else {
                $jsonRespuesta = json_encode($crm->respuestaError);
                print $jsonRespuesta;
            }
        } else {
            $jsonRespuesta = json_encode($crm->respuestaError);
            print $jsonRespuesta;
        }
    }
} else {
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
}

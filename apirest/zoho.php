<?php

require_once 'config.php';
require_once 'clases/respuestas_clase.php';
require_once 'clases/zoho_clase.php';

$_respuestas = new respuestas;
$zoho = new Zoho;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["modulos"])) {
        $link = '/bigin/v2/settings/modules';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["campos"]) && isset($_GET["contactos"])) {
        $link = '/bigin/v2/settings/fields?module=Contacts';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["contactos"])) {
        if ($_GET["contactos"]) {
            $link = '/bigin/v2/Contacts?fields=' . $_GET["contactos"];
            $datos = $zoho->get($link);
            if ($datos[1]) {
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            } else {
                $datos = $_respuestas->okF('Error en la Api de Zoho');
                http_response_code(200);
                echo json_encode($datos);
            }
        } else {
            $linkCampos = '/bigin/v2/settings/fields?module=Contacts';
            $datosCampos = $zoho->get($linkCampos);
            if ($datosCampos[1]) {
                $campos = [];
                $i = 0;
                foreach ($datosCampos[1] as $fields) {
                    foreach ($fields as $field) {
                        $campos[$i] = $field['api_name'];
                        $i++;
                    }
                }
                $camposString = '';
                $i = 0;
                foreach ($campos as $campo) {
                    if ($i == 0) {
                        $camposString .= $campo;
                    } else {
                        $camposString .= "," . $campo;
                    }
                    $i++;
                }
                $link = '/bigin/v2/Contacts?fields=' . $camposString;
                $datos = $zoho->get($link);
                if ($datos[1]) {
                    $cod = $datos[2];
                    http_response_code($cod);
                    $response = json_encode($datos);
                    echo $response;
                } else {
                    $datos = $_respuestas->okF('Error en la Api de Zoho');
                    http_response_code(200);
                    echo json_encode($datos);
                }
            } else {
                $datos = $_respuestas->okF('Error en la Api de Zoho');
                http_response_code(200);
                echo json_encode($datos);
            }
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["campos"]) && isset($_GET["empresas"])) {
        $link = '/bigin/v2/settings/fields?module=Accounts';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["empresas"])) {
        if ($_GET["empresas"]) {
            $link = '/bigin/v2/Accounts?fields=' . $_GET["empresas"];
            $datos = $zoho->get($link);
            if ($datos[1]) {
                $cod = $datos[2];
                http_response_code($cod);
                $response = json_encode($datos);
                echo $response;
            } else {
                $datos = $_respuestas->okF('Error en la Api de Zoho');
                http_response_code(200);
                echo json_encode($datos);
            }
        } else {
            $linkCampos = '/bigin/v2/settings/fields?module=Accounts';
            $datosCampos = $zoho->get($linkCampos);
            if ($datosCampos[1]) {
                $campos = [];
                $i = 0;
                foreach ($datosCampos[1] as $fields) {
                    foreach ($fields as $field) {
                        $campos[$i] = $field['api_name'];
                        $i++;
                    }
                }
                $camposString = '';
                $i = 0;
                foreach ($campos as $campo) {
                    if ($i == 0) {
                        $camposString .= $campo;
                    } else {
                        $camposString .= "," . $campo;
                    }
                    $i++;
                }
                $link = '/bigin/v2/Accounts?fields=' . $camposString;
                $datos = $zoho->get($link);
                if ($datos[1]) {
                    $cod = $datos[2];
                    http_response_code($cod);
                    $response = json_encode($datos);
                    echo $response;
                } else {
                    $datos = $_respuestas->okF('Error en la Api de Zoho');
                    http_response_code(200);
                    echo json_encode($datos);
                }
            } else {
                $datos = $_respuestas->okF('Error en la Api de Zoho');
                http_response_code(200);
                echo json_encode($datos);
            }
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["campos"]) && isset($_GET["tuberias"])) {
        $link = '/bigin/v2/settings/fields?module=Pipelines';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["layouts"]) && isset($_GET["tuberias"])) {
        $link = '/bigin/v2/settings/layouts?module=Pipelines';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["comunicacion"])) {
        //$link = '/bigin/v2/Pipelines?pipeline_id=688829000000437259&fields=Pipeline_Image,Deal_Name,Sub_Pipeline,Stage,Closing_Date,Pipeline,Tipo_de_OT,Associated_Products,Mensaje_Information,Account_Name,Contact_Name,Tag,Additional_Information,Tipo_de_mensaje,Description_Information,DealHistory,Stage_Duration_Calendar_Days';
        $link = '/bigin/v2/Pipelines?pipeline_id=688829000000437259&fields=Deal_Name,Created_Time';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["tratos"])) {
        $link = '/bigin/v2/Pipelines?pipeline_id=688829000000032043&fields=Deal_Name,Sub_Pipeline,Stage,Closing_Date,Pipeline,Tipo_de_OT,Associated_Products,Mensaje_Information,Account_Name,Contact_Name,Tag,Additional_Information,Tipo_de_mensaje,Description_Information,DealHistory,Stage_Duration_Calendar_Days';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["actualizarTrato"])) {
        $link = '/bigin/v2/Pipelines';
        $datosJson = [];
        $datosJson['data'][0]['id'] = $_GET["actualizarTrato"];
        $datosJson['data'][0]['Deal_Name'] = "OT-99916";
        $datosJson['data'][0]['Pipeline']['id'] = 688829000000032043;
        $datosJson['data'][0]['OTs'] = "OT-99916";
        $datosJson['data'][0]['Firma'] = "DIOR";
        $datosJson['data'][0]['Account_Name'] = "L'OREAL ESPAÑA, S.A.";
        $datosJson['data'][0]['C_digo_de_Empresa'] = 43000008;
        $datosJson['data'][0]['Description'] = "ESTA ES UNA DESCRIPCIÓN DE PRUEBA";
        $datosJson['data'][0]['Closing_Date'] = '2024-04-15';
        $datosJson['data'][0]['Sub_Pipeline'] = 'Clientes';
        $datosJson['data'][0]['Stage'] = 'Clasificación';
        $datosJson['data'][0]['Tipo_de_OT'][] = 'ECOM';
        $json = json_encode($datosJson);
        $datos = $zoho->put($link, $json);
        //print_r($datos);
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
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["asunto"])) {
        $link = "/bigin/v2/coql";
        $nombreTrato = urldecode($_GET["asunto"]);
        $query = "SELECT Pipeline, Deal_Name, Created_Time FROM Pipelines WHERE Deal_Name = '$nombreTrato'";
        $datosJson = [];
        $datosJson['select_query'] = $query;
        $json = json_encode($datosJson, JSON_FORCE_OBJECT);
        $datos = $zoho->post($link, $json);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'bigin' && isset($_GET["usuarios"])) {
        $link = '/bigin/v2/users';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["modulos"])) {
        $link = '/crm/v5/settings/modules';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["campos"]) && isset($_GET["ots"])) {
        $link = '/crm/v5/settings/fields?module=ORDENES_DE_TRABAJO';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["clientes"])) {
        $link = '/crm/v5/Accounts?fields=Account_Name';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["layout"]) && isset($_GET["lines"])) {
        $link = '/crm/v5/settings/layouts?module=Deals';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["pipelines"])) {
        $link = '/crm/v5/settings/pipeline?layout_id=6267041000000091023';
        $datos = $zoho->get($link);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
        } else {
            $datos = $_respuestas->okF('Error en la Api de Zoho');
            http_response_code(200);
            echo json_encode($datos);
        }
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["insertarOt"])) {
        $link = '/crm/v5/ORDENES_DE_TRABAJO';
        $datosJson = [];
        $datosJson['data'][0]['Prefijo'] = 'OT';
        $datosJson['data'][0]['Name'] = "99916";
        //$datosJson['data'][0]['Pipeline']['id'] = 688829000000032043;
        $datosJson['data'][0]['CLIENTE_OT'] = 6267041000000465110;
        $datosJson['data'][0]['ESTADO_ORDEN'] = "Nuevo Registro";
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
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["insertarLinea"])) {
        $link = '/crm/v5/Deals';
        $datosJson = [];
        $datosJson['data'][0]['Deal_Name'] = '1-99916-OT';
        $datosJson['data'][0]['Account_Name'] = 6267041000000465110;
        //$datosJson['data'][0]['Pipeline']['id'] = 688829000000032043;
        $datosJson['data'][0]['ORDEN_DE_TRABAJO'] = 6267041000000633001;
        $datosJson['data'][0]['Pipeline'] = 'Líneas - Estados';
        $datosJson['data'][0]['Stage'] = "Nuevo Registro";
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
    } else if (isset($_GET["app"]) && $_GET["app"] == 'crm' && isset($_GET["pdv"])) {
        $link = "/crm/v5/coql";
        $pdv = $_GET["pdv"];
        $query = "SELECT N_tel_fono, Clasificaci_n, Email, Secondary_Email, C_digo_postal, Direcci_n, N, Name, Sector, Zona, rea FROM Puntos_de_venta WHERE N = '$pdv'";
        $datosJson = [];
        $datosJson['select_query'] = $query;
        $json = json_encode($datosJson, JSON_FORCE_OBJECT);
        $datos = $zoho->post($link, $json);
        if ($datos[1]) {
            $cod = $datos[2];
            http_response_code($cod);
            $response = json_encode($datos);
            echo $response;
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
    $postBody = file_get_contents("php://input");
    $body = json_decode($postBody, true);
    if (isset($body['actualizarComunicacion'])) {
        $link = '/bigin/v2/Pipelines';
        $datosJson = [];
        $datosJson['data'][0]['id'] = $body["tratoId"];
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
} else {
    $datosArray = $_respuestas->error_405();
    http_response_code(405);
    echo json_encode($datosArray);
}

/*else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postBody = file_get_contents("php://input");
    $datosArray = $zoho->post($postBody);
    if (!$datosArray[0]) {
        http_response_code($datosArray[2]);
    } else {
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $postBody = file_get_contents("php://input");
    $datosArray = $zoho->delete($postBody);
    if (!$datosArray[0]) {
        http_response_code($datosArray[2]);
    } else {
        http_response_code(200);
    }
    echo json_encode($datosArray);
}*/

/*$_zoho = new Zoho;
$json = file_get_contents('./incidencia.json');
$body = json_decode($json, true);
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
$datosJson['data'][0]['Raz_n_de_la_incidencia'] = [$body["nombre"], 'Otro'];
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
echo $codigoFinal;
$datosJson['data'][0]['Ticket'] = $codigoFinal;
$datosJson['data'][0]['Deal_Name'] = $body["ot"] . " - " . $body["nombreCliente"] . $body["firma"] . $codigoFinal;
if (isset($body["descripcionIncidencia"])) {
    $datosJson['data'][0]['Description'] = $body["descripcionIncidencia"];
}
$datosJson['data'][0]['Prioridad'] = $body["prioridad"];
$json = json_encode($datosJson);
$datos = $_zoho->put($link, $json);
print_r($_zoho->error);
print_r($_zoho->respuesta);*/

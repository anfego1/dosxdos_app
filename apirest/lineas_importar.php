<?php

ini_set('curl.cainfo', '/dev/null');
set_time_limit(0);
ini_set('default_socket_timeout', 28800);
date_default_timezone_set('Atlantic/Canary');

require_once "clases/conexion_clase.php";
require_once "clases/crm_clase.php";
$conexion = new Conexion;
$crm = new Crm;

if (isset($_POST['codOt']) && (isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] == UPLOAD_ERR_OK)) {
    // Verificar que el archivo sea un CSV
    $mime_type = mime_content_type($_FILES['archivo_csv']['tmp_name']);
    if ($mime_type == 'text/csv' || $mime_type == 'text/plain') {
        // Ruta temporal del archivo subido
        $archivo_csv = $_FILES['archivo_csv']['tmp_name'];

        // Inicializar un array vacío
        $datos = [];
        $ot = [];
        $lineas_crm = [];
        $mensaje = '';

        // Abrir el archivo en modo lectura
        if (($handle = fopen($archivo_csv, 'r')) !== FALSE) {
            // Saltar las dos primeras filas
            fgetcsv($handle, 0, ';'); // Primera fila
            fgetcsv($handle, 0, ';'); // Segunda fila

            // Leer la tercera línea (encabezados)
            $encabezados = fgetcsv($handle, 0, ';');
            // Verificar que se hayan leído los encabezados correctamente
            if ($encabezados) {
                // Leer cada línea del archivo
                while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
                    // Verificar que la cantidad de datos coincida con la cantidad de encabezados
                    if (count($data) == count($encabezados)) {
                        // Combinamos los encabezados con los datos
                        $fila = array_combine($encabezados, $data);
                        // Agregar la fila resultante al array $datos
                        if ($fila) { // Asegurarse de que la combinación fue exitosa
                            $datos[] = $fila;
                        }
                    } else {
                        // Manejo de error: número de columnas no coincide
                        echo "Error: Número de columnas no coincide en la fila: " . implode(',', $data) . "\n";
                    }
                }
            }
            // Cerrar el archivo
            fclose($handle);
        }
        /*
        // Mostrar el array resultante (para propósitos de prueba)
        echo '<pre>';
        print_r($datos);
        echo '</pre>';
        */

        //CONSULTA PDV
        $i = 0;
        foreach ($datos as $dato) {
            $pdv = $dato['No. Punto de Venta'];
            $query = "SELECT Name, Direcci_n, Zona FROM Puntos_de_venta WHERE N = '$pdv'";
            $response = $crm->query($query);
            if ($crm->estado) {
                /*
                echo 'BIEN!';
                echo '<pre>';
                print_r($crm->respuesta);
                echo '</pre>';
                */
                if ($crm->respuesta[1]) {
                    $datos[$i]['PuntoDeVentaId'] = $crm->respuesta[1]['data'][0]['id'];
                    $datos[$i]['PuntoDeVentaNombre'] = $crm->respuesta[1]['data'][0]['Name'];
                    $datos[$i]['PuntoDeVentaDireccion'] = $crm->respuesta[1]['data'][0]['Direcci_n'];
                    $datos[$i]['PuntoDeVentaZona'] = $crm->respuesta[1]['data'][0]['Zona'];
                    $i++;
                } else {
                    $datos[$i]['PuntoDeVentaId'] = null;
                    $datos[$i]['PuntoDeVentaNombre'] = null;
                    $datos[$i]['PuntoDeVentaDireccion'] = null;
                    $datos[$i]['PuntoDeVentaZona'] = null;
                    $i++;
                }
            } else {
                echo 'ERROR';
                echo '<pre>';
                print_r($crm->respuestaError);
                echo '</pre>';
            }
        }

        //CONSULTA OT
        $codOt = $_POST['codOt'];
        $query = "SELECT Deal_Name, Empresa, Prefijo, Notion_ID FROM Deals WHERE C_digo = '$codOt'";
        $response = $crm->query($query);
        if ($crm->estado) {
            /*
                echo 'BIEN!';
                echo '<pre>';
                print_r($crm->respuesta);
                echo '</pre>';
                */
            if ($crm->respuesta[1]) {
                $ot['nombreOt'] = $crm->respuesta[1]['data'][0]['Deal_Name'];
                $ot['idOt'] = $crm->respuesta[1]['data'][0]['id'];
                $ot['idEmpresa'] = $crm->respuesta[1]['data'][0]['Empresa']['id'];
                $ot['prefijoOt'] = $crm->respuesta[1]['data'][0]['Prefijo'];
                $ot['notionId'] = $crm->respuesta[1]['data'][0]['Notion_ID'];
                $i++;
            } else {
                $ot['nombreOt'] = null;
                $ot['idOt'] = null;
                $ot['idEmpresa'] = null;
                $ot['prefijoOt'] = null;
                $ot['notionId'] = null;
                $i++;
            }
        } else {
            echo 'ERROR';
            echo '<pre>';
            print_r($crm->respuestaError);
            echo '</pre>';
        }
        $i = 0;
        foreach ($datos as $dato) {
            $datos[$i]['nombreOt'] = $ot['nombreOt'];
            $datos[$i]['idOt'] = $ot['idOt'];
            $datos[$i]['idEmpresa'] = $ot['idEmpresa'];
            $datos[$i]['prefijoOt'] = $ot['prefijoOt'];
            $datos[$i]['notionId'] = $ot['notionId'];
            $i++;
        }
        function generarCadenaAleatoriaSegura($longitud = 10)
        {
            // Calcular la cantidad de bytes necesarios
            $numBytes = ceil($longitud / 2);
            // Generar bytes aleatorios
            $bytesAleatorios = random_bytes($numBytes);
            // Convertir los bytes a una cadena hexadecimal
            $cadenaAleatoria = bin2hex($bytesAleatorios);
            // Recortar la cadena a la longitud deseada
            return substr($cadenaAleatoria, 0, $longitud);
        }
        $i = 0;
        foreach ($datos as $dato) {
            if ($dato['PuntoDeVentaId']) {
                $lineas_crm[$i]['Punto de venta'] = $dato['PuntoDeVentaId'];
                $aleatorio = generarCadenaAleatoriaSegura(10);
                $lineas_crm[$i]['Nombre de Línea de OT'] = $dato['PuntoDeVentaNombre'] . ', ' . $ot['prefijoOt'] . ', ' . $codOt . ', ' . $dato['PuntoDeVentaZona'] . ', ' .  $dato['PuntoDeVentaDireccion']  . ', ' . $aleatorio;
                $lineas_crm[$i]['Nombre de Empresa'] = $ot['idEmpresa'];
                $lineas_crm[$i]['OT relacionada'] = $ot['idOt'];
                $lineas_crm[$i]['Fase'] = 'Nuevas';
                $lineas_crm[$i]['Notion ID'] = $ot['notionId'];
                $lineas_crm[$i]['Fecha de creación'] = date('Y-m-d');
                $lineas_crm[$i]['Hora de creación'] = date('G');
                $lineas_crm[$i]['Minutos de creación'] = intval(date('i'));

                $lineas_crm[$i]['Navision - Línea'] = $dato['No. Linea'];
                $lineas_crm[$i]['Tipo de Trabajo'] = $dato['Tipo de Trabajo'];
                if (!$dato['Fecha Prevista'] || $dato['Fecha Prevista'] == '0000-00-00') {
                    $lineas_crm[$i]['Fecha de previsión de Línea'] = '1970-01-01';
                } else {
                    $lineas_crm[$i]['Fecha de previsión de Línea'] = $dato['Fecha Prevista'];
                }
                $lineas_crm[$i]['Observaciones internas'] = $dato['Observaciones'];
                $lineas_crm[$i]['Confirmada'] = $dato['Confirmada'];
                $lineas_crm[$i]['Número de referencia'] = $dato['No. Referencia'];
                $lineas_crm[$i]['Comunidad'] = $dato['Comunidad'];
                if (!$dato['Fecha Actuación'] || $dato['Fecha Actuación'] == '0000-00-00') {
                    $lineas_crm[$i]['Fecha actuación'] = '1970-01-01';
                } else {
                    $lineas_crm[$i]['Fecha actuación'] = $dato['Fecha Actuación'];
                }
                $lineas_crm[$i]['Estado Navision'] = $dato['Estado'];
                $lineas_crm[$i]['Ruta'] = $dato['No. Ruta'];
                $lineas_crm[$i]['Facturada Navision'] = $dato['Facturado'];
                $lineas_crm[$i]['Anulada Navision'] = $dato['Anulada'];
                $lineas_crm[$i]['Incidencia Navision'] = $dato['Incidencia'];
                $lineas_crm[$i]['Descripción Incidencia Navision'] = $dato['Descripción Incidencia'];
                $lineas_crm[$i]['Ubicación'] = $dato['Ubicación'];
                $lineas_crm[$i]['Tipo (material)'] = $dato['Tipo'];
                $lineas_crm[$i]['Descuento montaje'] = $dato['Dto. Montaje'];
                $lineas_crm[$i]['Descuento realizado'] = $dato['Dto. Realizado'];
                $lineas_crm[$i]['Alto (medida)'] = $dato['Alto'];
                $lineas_crm[$i]['Ancho (medida)'] = $dato['Ancho'];
                $lineas_crm[$i]['Quitar'] = $dato['Quitar'];
                $lineas_crm[$i]['Poner'] = $dato['Poner'];
                $lineas_crm[$i]['Desmontado'] = $dato['Desmontado'];
                $lineas_crm[$i]['Actualizado'] = $dato['Actualizado'];
                $lineas_crm[$i]['Código estándar'] = $dato['Cód. Estándar'];
                $lineas_crm[$i]['M2'] = $dato['M2'];
                if (!$dato['Fecha Entrada'] || $dato['Fecha Entrada'] == '0000-00-00') {
                    $lineas_crm[$i]['Fecha entrada'] = '1970-01-01';
                } else {
                    $lineas_crm[$i]['Fecha entrada'] = $dato['Fecha Entrada'];
                }
                $lineas_crm[$i]['Obsoleta Navision'] = $dato['Obsoleta'];
                $lineas_crm[$i]['Descripción Tipo Trabajo'] = $dato['Descripción'];
                $lineas_crm[$i]['Observaciones montador'] = $dato['Observaciones del técnico'];
                $lineas_crm[$i]['Horas actuación'] = $dato['Horas actuación'];
                $lineas_crm[$i]['Días actuación'] = $dato['Días actuación'];
                $lineas_crm[$i]['Minutos actuación'] = $dato['Minutos actuación'];
                $lineas_crm[$i]['Revisado en la ruta'] = $dato['Revisado en la ruta'];
                $lineas_crm[$i]['Sin cargo'] = $dato['Sin cargo'];
                $i++;
            } else {
                $mensaje .= "/ EL PUNTO DE VENTA " . $pdv . " NO EXISTE EN EL CRM - POR FAVOR CREARLO Y VOLVER A GENERAR EL ARCHIVO DE IMPORTACIÓN DE LÍNEAS / ";
                echo $mensaje;
                //die();
            }
        }

        // Define la ubicación y el nombre del archivo CSV
        $csvFilePath = './datos_lineas/datos_lineas_crm.csv';

        // Abre el archivo CSV en modo de escritura
        $csvFile = fopen($csvFilePath, 'w');

        // Verifica si el array $ots_crm no está vacío
        if (!empty($lineas_crm)) {
            // Obtiene las claves del primer elemento del array
            $cabeceras = array_keys($lineas_crm[0]);

            // Escribe las cabeceras en el archivo CSV
            fputcsv($csvFile, $cabeceras);

            // Escribe los datos en el archivo CSV
            foreach ($lineas_crm as $fila) {
                fputcsv($csvFile, $fila);
            }
        }

        // Cierra el archivo CSV
        fclose($csvFile);
?>
        <a href="https://dosxdos.app.iidos.com/apirest/datos_lineas/datos_lineas_crm.csv" download="datos_lineas_crm.csv">DESCARGAR ARCHIVO</a>
    <?php
        /*
        print 'LINEAS_CRM = ';
        print count($lineas_crm);
        print '-----';
        echo '<pre>';
        print_r($lineas_crm);
        echo '</pre>';
        */
    } else {
        echo "El archivo subido no es un archivo CSV válido.";
    }
} else {
    ?>
    <form action="lineas_importar.php" method="post" style="display: flex; flex-direction: column; width: 250px;" enctype="multipart/form-data">
        <input type="text" placeholder="CÓDIGO DE OT DEL CRM" name="codOt">
        <input type="file" name="archivo_csv" style="margin-top: 10px;">
        <button type="submit" style="margin-top: 10px;">ENVIAR</button>
    </form>
<?php
}

?>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

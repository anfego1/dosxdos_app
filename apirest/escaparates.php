<?php

ini_set('curl.cainfo', '/dev/null');
set_time_limit(0);
ini_set('default_socket_timeout', 28800);
date_default_timezone_set('Atlantic/Canary');

require_once "clases/crm_clase.php";

// Función para convertir archivo CSV en un array
function csvToArray($filename, $delimiter = ';')
{
    if (!file_exists($filename) || !is_readable($filename)) {
        return false;
    }

    $content = file_get_contents($filename);
    // Eliminar el BOM si está presente
    if (substr($content, 0, 3) == "\xef\xbb\xbf") {
        $content = substr($content, 3);
        file_put_contents($filename, $content);
    }

    $data = [];
    if (($handle = fopen($filename, 'r')) !== false) {
        // Lee la primera línea como encabezados
        $header = fgetcsv($handle, 1000, $delimiter);
        if ($header) {
            // Lee las líneas restantes
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $data[] = array_combine($header, $row); // Combina el encabezado con cada fila
            }
        }
        fclose($handle);
    }

    return $data;
}

// Ejemplo de uso
$filename = 'escaparates_csv.csv'; // Reemplaza con la ruta de tu archivo
$arrayCsv = csvToArray($filename);

// Nuevo array con los primeros 388 elementos
$escaparates = [];

for ($i = 0; $i <= 390 && $i < count($arrayCsv); $i++) {
    $escaparates[] = $arrayCsv[$i];
}
$totalArrayEscaparates = count($escaparates) + 1;
print "<p>TOTAL ÍNDICES ESCAPARATES = " . $totalArrayEscaparates . "</p>";
@ob_flush();
flush();
$escaparatesTotal = [];

try {
    $crm = new Crm;
    $campos = "Name";
    $i = 0;
    foreach ($escaparates as $escaparate) {
        if ($i <= $totalArrayEscaparates) {
            $escaparatesTotal[$i] = $escaparate;
            $pv = $escaparate['pv'];
            $query = "SELECT $campos FROM Puntos_de_venta WHERE N=\"$pv\"";
            $crm->query($query);
            if ($crm->estado) {
                if ($crm->respuesta[1]) {
                    $data = $crm->respuesta[1]['data'];
                    $escaparatesTotal[$i]['idPv'] = $data[0]['id'];
                    print "<p>" . $i . " - ESCAPARATE " . $escaparatesTotal[$i]['Nombre'] . "GUARDADO</p>";
                    @ob_flush();
                    flush();
                } else {
                    print "ERROR EN LA RESPUESTA DEL CRM";
                    print_r($crm->respuesta);
                    die();
                }
            } else {
                print "ERROR EN LA RESPUESTA DEL CRM";
                print_r($crm->estado);
                die();
            }
            /*
        print "<pre>";
        print_r($escaparate['pv']); // Muestra el contenido del array
        print "</pre>";
        */
        }
        $i++;
    }
} catch (\Throwable $th) {
    print "ERROR EN LA RESPUESTA DEL CRM";
    print_r($th);
    die();
}

// Define la ubicación y el nombre del archivo CSV
$csvFilePath = './escaparates_total_importacion.csv';

// Abre el archivo CSV en modo de escritura
$csvFile = fopen($csvFilePath, 'w');

// Verifica si el array $ots_crm no está vacío
if (!empty($escaparatesTotal)) {
    // Obtiene las claves del primer elemento del array
    $cabeceras = array_keys($escaparatesTotal[0]);

    // Escribe las cabeceras en el archivo CSV
    fputcsv($csvFile, $cabeceras);

    // Escribe los datos en el archivo CSV
    foreach ($escaparatesTotal as $fila) {
        fputcsv($csvFile, $fila);
    }
}

// Cierra el archivo CSV
fclose($csvFile);

print count($escaparatesTotal);
print "<pre>";
print_r($escaparatesTotal); // Muestra el contenido del array
print "</pre>";

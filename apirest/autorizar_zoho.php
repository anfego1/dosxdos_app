<?php

if (!isset($_GET['code'])) {
    $code = file_get_contents('./clases/code_zoho.json');
    $code = json_decode($code, true);
    $codigo = $code['code'];
    $servidor = $code['accounts-server'];
    $localidad = $code['location'];
    $cliente = file_get_contents('./clases/cliente_zoho.json');
    $cliente = json_decode($cliente, true);
    $url = $servidor . "/oauth/v2/token";
    //print $url;
    $fields = array(
        'client_id' => $cliente['client_id'],
        'client_secret' => $cliente['client_secret'],
        'code' => $codigo,
        'redirect_uri' => $cliente['redirect_uri'],
        'grant_type' => 'authorization_code'
    );
    // Crear una solicitud cURL
    $curl = curl_init();
    // Configurar la solicitud POST
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Ejecutar la solicitud
    $response = curl_exec($curl);
    //print $response;
    // Verificar si hubo un error en la solicitud
    if ($response === false) {
        echo 'Curl error: ' . curl_error($curl);
    } else {
        $respuesta = json_decode($response, true);
        if (isset($respuesta['error'])) {
            echo "Error en la respuesta de la api de Zoho: " . $respuesta['error']; 
        } else {
            // Escribir los tokens
            if (file_put_contents('./clases/tokens_zoho.json', $response) === false) {
                echo "Error al escribir los tokens.";
            } else {
                echo "Tokens escritos correctamente";
            }
        }
    }
    // Cerrar la solicitud
    curl_close($curl);
} else {
    $codigo = $_GET['code'];
    $localidad = $_GET['location'];
    $servidor = $_GET['accounts-server'];
    $vector = [];
    $vector['code'] = $codigo;
    $vector['location'] = $localidad;
    $vector['accounts-server'] = $servidor;
    $json = json_encode($vector);
    if (file_put_contents('./clases/code_zoho.json', $json) === false) {
        echo "Error al escribir el código.";
    } else {
        echo "Código escrito correctamente";
        ?>
            <a href="autorizar_zoho.php"><button>ESCRIBIR TOKENS</button></a>
        <?php
    }
}
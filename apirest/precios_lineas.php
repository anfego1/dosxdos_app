<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('curl.cainfo', '/dev/null');
set_time_limit(0);
ini_set('default_socket_timeout', 28800);
date_default_timezone_set('Atlantic/Canary');

require_once 'clases/crm_clase.php';

try {
    $crm = new Crm;
    $lineas;

    /*
    $idOt = 707987000001725513;
    $codOt = 30295;
    $cliente = 'ANTONIO PUIG, S.A.';
    */

    $idOt = $_GET['idOt'];
    $codOt = $_GET['codOt'];
    $cliente = $_GET['cliente'];

    $numLineas = 0;
    echo '<p style="color:green">CÁLCULO PARA LA OT ' . $codOt . ' INICIADO...</p>';
    @ob_flush();
    flush();
    /*LINEAS*/
    $camposLineas = "Codigo_de_l_nea,Punto_de_venta,Incluir,Ancho_medida,Alto_medida,Logo,Toma_de_medidas,Material,Acabados1,Impuesto_Cliente,Alto_total,Ancho_total";
    $query = "SELECT $camposLineas FROM Products WHERE OT_relacionada=$idOt";
    $crm->query($query);
    if ($crm->estado) {
        $lineas = $crm->respuesta[1]['data'];
        $numLineas = count($lineas);
        echo '<p style="color:green">CALCULANDO ' . $numLineas . ' LÍNEAS...</p>';
        /*
        echo "<pre>";
        print_r($lineas);
        echo "</pre>";
        */
        @ob_flush();
        flush();
        $descPorcRealización;
        $descPorcMontaje;
        $impuesto;
        /*CLIENTE*/
        $camposCliente = "Descuento_montaje,Descuento_realizaci_n,Grupo_registro_IVA_neg";
        $query = "SELECT $camposCliente FROM Accounts WHERE Account_Name=\"$cliente\"";
        $crm->query($query);
        if ($crm->estado) {
            $descPorcRealización = $crm->respuesta[1]['data'][0]['Descuento_realizaci_n'];
            $descPorcMontaje = $crm->respuesta[1]['data'][0]['Descuento_montaje'];
            $impuesto = $crm->respuesta[1]['data'][0]['Grupo_registro_IVA_neg'];
            echo '<p style="color:orange">descPorcRealización: ' . $descPorcRealización . '</p>';
            echo '<p style="color:orange">descPorcMontaje: ' . $descPorcMontaje . '</p>';
            echo '<p style="color:orange">impuesto: ' . $impuesto . '</p>';
            @ob_flush();
            flush();
        } else {
            echo '<p style="color:red">ERROR AL CONSULTAR LOS DATOS DEL CLIENTE!!!</p>';
            print_r($crm->respuestaError);
            die();
        }
        /*PRECIOS MONTAJE IMÁGENES*/
        $preciosMontajeImagenes = [];
        $crm->get("preciosMontajeImagenes");
        if ($crm->estado) {
            echo '<p style="color:orange">preciosMontajeImagenes:</p>';
            $preciosMontajeImagenes = $crm->respuesta[1]['data'];
            print_r($preciosMontajeImagenes);
            @ob_flush();
            flush();
        } else {
            echo '<p style="color:red">ERROR!!! AL CONSULTAR EL MÓDULO DE PRECIOS DE MONTAJE DE LAS IMÁGENES EN EL CRM</p>';
            print_r($crm->respuestaError);
            die();
        }
        /*PRECIOS LOGOS*/
        $preciosLogos = [];
        $crm->get("preciosLogos");
        if ($crm->estado) {
            echo '<p style="color:orange">preciosLogos:</p>';
            $preciosLogos = $crm->respuesta[1]['data'];
            print_r($preciosLogos);
            @ob_flush();
            flush();
        } else {
            echo '<p style="color:red">ERROR!!! AL CONSULTAR EL MÓDULO DE PRECIOS DE LOS LOGOS EN EL CRM</p>';
            print_r($crm->respuestaError);
            die();
        }
        /*PRECIOS MONTAJE LOGOS*/
        $preciosLogosMontaje = [];
        $crm->get("preciosLogosMontaje");
        if ($crm->estado) {
            echo '<p style="color:orange">preciosLogosMontaje:</p>';
            $preciosLogosMontaje = $crm->respuesta[1]['data'];
            print_r($preciosLogosMontaje);
            @ob_flush();
            flush();
        } else {
            echo '<p style="color:red">ERROR!!! AL CONSULTAR EL MÓDULO DE PRECIOS DE MONTAJE DE LOGOS EN EL CRM</p>';
            print_r($crm->respuestaError);
            die();
        }
        /*PRECIO TOMA DE MEDIDAS*/
        $precioTomaDeMedidas;
        $camposPreciosMedidas = "Precio";
        $query = "SELECT $camposPreciosMedidas FROM Precios_Materiales WHERE Material=\"TOMA DE MEDIDAS\"";
        $crm->query($query);
        if ($crm->estado) {
            $precioTomaDeMedidas = $crm->respuesta[1]['data'][0]['Precio'];
            echo '<p style="color:orange">precioTomaDeMedidas: ' . $precioTomaDeMedidas . '</p>';
            @ob_flush();
            flush();
        } else {
            echo '<p style="color:red">ERROR!!! AL CONSULTAR EL PRECIO DE LA TOMA DE MEDIDAS, EN LA LÍNEA: ' . $codLinea . '</p>';
            print_r($crm->respuestaError);
            die();
        }
        $numVisuales = 0;
        $numLogos = 0;
        $numTomasDeMedida = 0;
        $lineasNoIncluidas = [];
        $lineasIncluidas = [];
        $totalM2 = 0;
        $realizacion = 0;
        $totalDescRealizacion = 0;
        $totalRealizacion = 0;
        $acabados = 0;
        $montaje = 0;
        $totalDescMontaje = 0;
        $totalMontaje = 0;
        $totalSinImpuesto = 0;
        $totalConImpuesto = 0;
        $totalPreciosLineas = 0;
        $pvsLogos = [];
        $logos = 0;
        $montajeLogos = 0;
        $totalDescMontajeLogos = 0;
        $totalMontajeLogos = 0;
        $tomaDeMedidas = 0;
        foreach ($lineas as $linea) {
            $id = $linea['id'];
            $codLinea = $linea['Codigo_de_l_nea'];
            $pv = $linea['Punto_de_venta']['id'];
            $incluir = $linea['Incluir'];
            $ancho = floatval($linea['Ancho_medida']);
            $alto = floatval($linea['Alto_medida']);
            $anchoTotal = $linea['Ancho_total'];
            $altoTotal = $linea['Alto_total'];
            if ($anchoTotal && $altoTotal) {
                $ancho = floatval($anchoTotal);
                $alto = floatval($altoTotal);
            }
            $logo = $linea['Logo'];
            $tomaMedidas = $linea['Toma_de_medidas'];
            $material = $linea['Material'];
            $acabado = $linea['Acabados1'];
            $impuestoString = $linea['Impuesto_Cliente'];
            $precioLinea = 0;
            $m2 = 0;
            $realizacionLinea;
            if ($incluir) {
                array_push($lineasIncluidas, $codLinea);
                if ($tomaMedidas) {
                    /*TOMA DE MEDIDA*/
                    $numTomasDeMedida++;
                    echo '<p style="color:green">CALCULANDO LÍNEA ' . $codLinea . ' ...</p>';
                    echo '<p style="color:gray">Es una toma de medidas</p>';
                    @ob_flush();
                    flush();
                    $precioLinea = $precioTomaDeMedidas;
                    $precioLinea = number_format($precioLinea, 2);
                    $tomaDeMedidas = $tomaDeMedidas + $precioLinea;
                    $totalPreciosLineas = $totalPreciosLineas + $precioLinea;
                    echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                    @ob_flush();
                    flush();
                    /*ACTUALIZAR LÍNEA */
                    echo '<p style="color:gray">Actualizando en el CRM la línea: ' . $codLinea . '...</p>';
                    @ob_flush();
                    flush();
                    $LineaVector = [];
                    $LineaVector['data'][0]['id'] = $id;
                    $LineaVector['data'][0]['Unit_Price'] = $precioLinea;
                    $LineaJson = json_encode($LineaVector);
                    $crm->actualizar("actualizarLinea", $LineaJson);
                    if ($crm->estado) {
                        echo '<p style="color:gray">Línea actualizada en el CRM: ' . $codLinea . '</p>';
                        @ob_flush();
                        flush();
                    } else {
                        echo '<p style="color:red">ERROR!!! NO SE HA ACTUALIZADO LA LÍNEA: ' . $codLinea . ' EN EL CRM</p>';
                        print_r($crm->respuestaError);
                        die();
                    }
                } else if ($logo) {
                    /*LOGO*/
                    if ($alto && $ancho) {
                        $numLogos++;
                        echo '<p style="color:green">CALCULANDO LÍNEA ' . $codLinea . ' ...</p>';
                        echo '<p style="color:gray">Es un logo</p>';
                        @ob_flush();
                        flush();
                        array_push($pvsLogos, $pv);
                        $m2 = (($ancho / 100) * ($alto / 100));
                        $m2 = number_format($m2, 2);
                        if ($m2 == 0.00) {
                            $m2 = 0.01;
                        }
                        $totalM2 = $totalM2 + $m2;
                        echo '<p style="color:gray">m2: ' . $m2 . '</p>';
                        @ob_flush();
                        flush();
                        $nPv;
                        $areaPv;
                        $realizacionLineaLogo;
                        $montajeLineaLogo;
                        $porcDescuentoMontajeLogo = 0;
                        $descuentoMontajeLineaLogo;
                        $camposPv = "N,rea";
                        $query = "SELECT $camposPv FROM Puntos_de_venta WHERE id=\"$pv\"";
                        $crm->query($query);
                        if ($crm->estado) {
                            $nPv = $crm->respuesta[1]['data'][0]['N'];
                            $areaPv = $crm->respuesta[1]['data'][0]['rea'];
                            echo '<p style="color:gray">nPv: ' . $nPv . '</p>';
                            echo '<p style="color:gray">areaPv: ' . $areaPv . '</p>';
                            @ob_flush();
                            flush();
                            if (!$areaPv) {
                                echo '<p style="color:red">ERROR!!! EL PUNTO DE VENTA ' . $nPv . ' NO TIENE EL CAMPO DE ÁREA DEFINIDO. POR FAVOR ACTUALIZA LOS DATOS DE UBICACIÓN DEL PUNTO DE VENTA, ACTUALIZA POR FAVOR TODOS LOS CAMPOS: ÁREA, SÉCTOR, ZONA, DIRECCIÓN</p>';
                                die();
                            }
                        } else {
                            echo '<p style="color:red">ERROR!!! AL CONSULTAR LOS DATOS DEL PUNTO DE VENTA CON ID ' . $pv . ' EN EL CRM!!!</p>';
                            print_r($crm->respuestaError);
                            die();
                        }
                        $numPv = 0;
                        foreach ($pvsLogos as $pvLogo) {
                            if ($pvLogo == $pv) {
                                $numPv++;
                            }
                        }
                        if (($numPv > 5) && (($areaPv == "GC") || ($areaPv == "LP"))) {
                            $porcDescuentoMontajeLogo = 25;
                            echo '<p style="color:gray">numPv: ' . $numPv . '</p>';
                            echo '<p style="color:gray">porcDescuentoMontajeLogo: ' . $porcDescuentoMontajeLogo . '%</p>';
                            @ob_flush();
                            flush();
                        } else if (($numPv > 7) && ($areaPv == "TF")) {
                            $porcDescuentoMontajeLogo = 20;
                            echo '<p style="color:gray">numPv: ' . $numPv . '</p>';
                            echo '<p style="color:gray">porcDescuentoMontajeLogo: ' . $porcDescuentoMontajeLogo . '%</p>';
                            @ob_flush();
                            flush();
                        } else if (($numPv > 10) && (($areaPv == "FT") || ($areaPv == "LZ"))) {
                            $porcDescuentoMontajeLogo = 20;
                            echo '<p style="color:gray">numPv: ' . $numPv . '</p>';
                            echo '<p style="color:gray">porcDescuentoMontajeLogo: ' . $porcDescuentoMontajeLogo . '%</p>';
                            @ob_flush();
                            flush();
                        }
                        /*REALIZACIÓN LOGO*/
                        $validarPrecioLogo = false;
                        $precioDelLogo;
                        foreach ($preciosLogos as $precio) {
                            if (($m2 >= $precio['Rango1']) && ($m2 <= $precio['Rango2'])) {
                                $validarPrecioLogo = true;
                                $precioDelLogo = $precio['Precio'];
                                echo '<p style="color:gray">precioDelLogo: ' . $precioDelLogo . '</p>';
                                @ob_flush();
                                flush();
                            }
                        }
                        if ($validarPrecioLogo) {
                            $realizacionLineaLogo = $m2 * $precioDelLogo;
                            $realizacionLineaLogo = number_format($realizacionLineaLogo, 2);
                            $logos = $logos + $realizacionLineaLogo;
                            $precioLinea = $realizacionLineaLogo;
                            echo '<p style="color:gray">realizacionLineaLogo: ' . $realizacionLineaLogo . '</p>';
                            echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                            @ob_flush();
                            flush();
                            /*ACABADO*/
                            if ($acabado) {
                                $camposPreciosAcabados = "Precio";
                                $query = "SELECT $camposPreciosAcabados FROM Precios_Acabados WHERE Acabado	=\"$acabado\"";
                                $crm->query($query);
                                if ($crm->estado) {
                                    $precioAcabado = $crm->respuesta[1]['data'][0]['Precio'];
                                    echo '<p style="color:gray">acabado: ' . $acabado . '</p>';
                                    echo '<p style="color:gray">precioAcabado: ' . $precioAcabado . '</p>';
                                    @ob_flush();
                                    flush();
                                    $valorAcabado = $m2 * $precioAcabado;
                                    $valorAcabado = number_format($valorAcabado, 2);
                                    $acabados = $acabados + $valorAcabado;
                                    $precioLinea = $precioLinea + $valorAcabado;
                                    echo '<p style="color:gray">valorAcabado: ' . $valorAcabado . '</p>';
                                    echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                    @ob_flush();
                                    flush();
                                } else {
                                    if ($crm->respuestaError[1] == "Error en la API de Zoho: INVALID_QUERY value given seems to be invalid") {
                                        echo '<p style="color:orange">INFO: Línea ' . $codLinea . ' no tiene acabado...</p>';
                                        @ob_flush();
                                        flush();
                                    } else {
                                        echo '<p style="color:red">ERROR!!! AL CONSULTAR EL ACABADO DE LA LÍNEA ' . $codLinea . '</p>';
                                        print_r($crm->respuestaError);
                                        die();
                                    }
                                }
                            }
                            /* MONTAJE LOGO */
                            $validarPrecioMontajeLogo = false;
                            $precioDelMontaje;
                            foreach ($preciosLogosMontaje as $precio) {
                                if (($m2 >= $precio['Rango1']) && ($m2 <= $precio['Rango2'])) {
                                    $validarPrecioMontajeLogo = true;
                                    $precioDelMontaje = $precio['Precio'];
                                }
                            }
                            if ($validarPrecioMontajeLogo) {
                                $montajeLogos = $montajeLogos + $precioDelMontaje;
                                $descuentoMontajeLineaLogo = 0;
                                $precioLinea = $precioLinea + $precioDelMontaje;
                                echo '<p style="color:gray">precioDelMontaje: ' . $precioDelMontaje . '</p>';
                                echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                @ob_flush();
                                flush();
                                if ($porcDescuentoMontajeLogo) {
                                    $descuentoMontajeLineaLogo = ($precioDelMontaje * $porcDescuentoMontajeLogo) / 100;
                                    $descuentoMontajeLineaLogo = number_format($descuentoMontajeLineaLogo, 2);
                                    $totalDescMontajeLogos = $totalDescMontajeLogos + $descuentoMontajeLineaLogo;
                                    $precioLinea = $precioLinea - $descuentoMontajeLineaLogo;
                                    echo '<p style="color:gray">descuentoMontajeLineaLogo: ' . $descuentoMontajeLineaLogo . '</p>';
                                    echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                    @ob_flush();
                                    flush();
                                }
                            } else {
                                echo '<p style="color:red">ERROR!!! LOS METROS CUADRADOS SON MAYORES O MENORES A LOS RANGOS ESTABLECIDOS EN LOS PRECIOS DE MONTAJE DE LOGOS, EN LA LÍNEA: ' . $codLinea . '</p>';
                                print_r($crm->respuestaError);
                                die();
                            }
                        } else {
                            echo '<p style="color:red">ERROR!!! LOS METROS CUADRADOS SON MAYORES O MENORES A LOS RANGOS ESTABLECIDOS EN LOS PRECIOS DE LA REALIZACIÓN DE LOGOS, EN LA LÍNEA: ' . $codLinea . '</p>';
                            print_r($crm->respuestaError);
                            die();
                        }
                        $precioLinea = number_format($precioLinea, 2);
                        $totalPreciosLineas = $totalPreciosLineas + $precioLinea;
                        echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                        @ob_flush();
                        flush();
                        /*ACTUALIZAR LÍNEA */
                        echo '<p style="color:gray">Actualizando en el CRM la línea: ' . $codLinea . '...</p>';
                        @ob_flush();
                        flush();
                        $LineaVector = [];
                        $LineaVector['data'][0]['id'] = $id;
                        $LineaVector['data'][0]['Metros_cuadrados'] = $m2;
                        $LineaVector['data'][0]['Realizaci_n'] = $realizacionLineaLogo;
                        $LineaVector['data'][0]['Porcentaje_Descuento_Realizaci_n'] = 0;
                        $LineaVector['data'][0]['Descuento_Realizaci_n'] = 0;
                        $LineaVector['data'][0]['Montaje'] = $precioDelMontaje;
                        $LineaVector['data'][0]['Porcentaje_Descuento_Montaje'] = $porcDescuentoMontajeLogo;
                        $LineaVector['data'][0]['Descuento_de_Montaje'] = $descuentoMontajeLineaLogo;
                        $LineaVector['data'][0]['Unit_Price'] = $precioLinea;
                        $LineaJson = json_encode($LineaVector);
                        $crm->actualizar("actualizarLinea", $LineaJson);
                        if ($crm->estado) {
                            echo '<p style="color:gray">Línea actualizada en el CRM: ' . $codLinea . '</p>';
                            @ob_flush();
                            flush();
                        } else {
                            echo '<p style="color:red">ERROR!!! NO SE HA ACTUALIZADO LA LÍNEA: ' . $codLinea . ' EN EL CRM</p>';
                            print_r($crm->respuestaError);
                            die();
                        }
                    } else {
                        echo '<p style="color:red">ERROR!!! LA LÍNEA ' . $codLinea . ' NO TIENE DEFINIDO EL ALTO O EL ANCHO</p>';
                        @ob_flush();
                        flush();
                        die();
                    }
                } else {
                    /*VISUAL*/
                    if ($alto && $ancho) {
                        /*REALIZACIÓN*/
                        $numVisuales++;
                        echo '<p style="color:green">CALCULANDO LÍNEA ' . $codLinea . ' ...</p>';
                        echo '<p style="color:gray">Es una visual</p>';
                        @ob_flush();
                        flush();
                        $m2 = (($ancho / 100) * ($alto / 100));
                        $m2 = number_format($m2, 2);
                        if ($m2 == 0.00) {
                            $m2 = 0.01;
                        }
                        $totalM2 = $totalM2 + $m2;
                        echo '<p style="color:gray">m2: ' . $m2 . '</p>';
                        @ob_flush();
                        flush();
                        if ($material) {
                            /*MATERIAL*/
                            echo '<p style="color:gray">material: ' . $material . '</p>';
                            @ob_flush();
                            flush();
                            $precioMaterial;
                            $camposPreciosMaterial = "Precio";
                            $query = "SELECT $camposPreciosMaterial FROM Precios_Materiales WHERE Material=\"$material\"";
                            $crm->query($query);
                            if ($crm->estado) {
                                $precioMaterial = $crm->respuesta[1]['data'][0]['Precio'];
                                echo '<p style="color:gray">precioMaterial: ' . $precioMaterial . '</p>';
                                @ob_flush();
                                flush();
                                $realizacionLinea = $m2 * $precioMaterial;
                                $realizacionLinea = number_format($realizacionLinea, 2);
                                $realizacion = $realizacion + $realizacionLinea;
                                $precioLinea = $realizacionLinea;
                                echo '<p style="color:gray">realizacionLinea: ' . $realizacionLinea . '</p>';
                                echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                @ob_flush();
                                flush();
                                if ($descPorcRealización) {
                                    $descuentoRealizacionLinea = ($realizacionLinea * $descPorcRealización) / 100;
                                    $descuentoRealizacionLinea = number_format($descuentoRealizacionLinea, 2);
                                    $totalDescRealizacion = $totalDescRealizacion + $descuentoRealizacionLinea;
                                    $precioLinea = $precioLinea - $descuentoRealizacionLinea;
                                    echo '<p style="color:gray">descuentoRealizacionLinea: ' . $descuentoRealizacionLinea . '</p>';
                                    echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                    @ob_flush();
                                    flush();
                                }
                                /*ACABADO*/
                                if ($acabado) {
                                    $camposPreciosAcabados = "Precio";
                                    $query = "SELECT $camposPreciosAcabados FROM Precios_Acabados WHERE Acabado	=\"$acabado\"";
                                    $crm->query($query);
                                    if ($crm->estado) {
                                        $precioAcabado = $crm->respuesta[1]['data'][0]['Precio'];
                                        echo '<p style="color:gray">acabado: ' . $acabado . '</p>';
                                        echo '<p style="color:gray">precioAcabado: ' . $precioAcabado . '</p>';
                                        @ob_flush();
                                        flush();
                                        $valorAcabado = $m2 * $precioAcabado;
                                        $valorAcabado = number_format($valorAcabado, 2);
                                        $acabados = $acabados + $valorAcabado;
                                        $precioLinea = $precioLinea + $valorAcabado;
                                        echo '<p style="color:gray">valorAcabado: ' . $valorAcabado . '</p>';
                                        echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                        @ob_flush();
                                        flush();
                                    } else {
                                        if ($crm->respuestaError[1] == "Error en la API de Zoho: INVALID_QUERY value given seems to be invalid") {
                                            echo '<p style="color:orange">INFO: Línea ' . $codLinea . ' no tiene acabado...</p>';
                                            @ob_flush();
                                            flush();
                                        } else {
                                            echo '<p style="color:red">ERROR!!! AL CONSULTAR EL ACABADO DE LA LÍNEA ' . $codLinea . '</p>';
                                            print_r($crm->respuestaError);
                                            die();
                                        }
                                    }
                                }
                                /*MONTAJE*/
                                $validarPrecioMOntaje = false;
                                $precioDelMontaje;
                                foreach ($preciosMontajeImagenes as $precio) {
                                    if (($m2 >= $precio['Rango1']) && ($m2 <= $precio['Rango2'])) {
                                        $validarPrecioMOntaje = true;
                                        $precioDelMontaje = $precio['Precio'];
                                    }
                                }
                                if ($validarPrecioMOntaje) {
                                    $montaje = $montaje + $precioDelMontaje;
                                    $precioLinea = $precioLinea + $precioDelMontaje;
                                    echo '<p style="color:gray">precioDelMontaje: ' . $precioDelMontaje . '</p>';
                                    echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                    @ob_flush();
                                    flush();
                                    if ($descPorcMontaje) {
                                        $descuentoMontajeLinea = ($precioDelMontaje * $descPorcMontaje) / 100;
                                        $descuentoMontajeLinea = number_format($descuentoMontajeLinea, 2);
                                        $totalDescMontaje = $totalDescMontaje + $descuentoMontajeLinea;
                                        $precioLinea = $precioLinea - $descuentoMontajeLinea;
                                        echo '<p style="color:gray">descuentoMontajeLinea: ' . $descuentoMontajeLinea . '</p>';
                                        echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                                        @ob_flush();
                                        flush();
                                    }
                                } else {
                                    echo '<p style="color:red">ERROR!!! LOS METROS CUADRADOS SON MAYORES O MENORES A LOS RANGOS ESTABLECIDOS EN LOS PRECIOS DE MONTAJE DE IMÁGENES, EN LA LÍNEA: ' . $codLinea . '</p>';
                                    print_r($crm->respuestaError);
                                    die();
                                }
                            } else {
                                echo '<p style="color:red">ERROR!!! AL CONSULTAR EL PRECIO DEL MATERIAL DE LA LÍNEA ' . $codLinea . '</p>';
                                print_r($crm->respuestaError);
                                die();
                            }
                            $precioLinea = number_format($precioLinea, 2);
                            $totalPreciosLineas = $totalPreciosLineas + $precioLinea;
                            echo '<p style="color:gray">precioLinea: ' . $precioLinea . '</p>';
                            @ob_flush();
                            flush();
                            /*ACTUALIZAR LÍNEA */
                            echo '<p style="color:gray">Actualizando en el CRM la línea: ' . $codLinea . '...</p>';
                            @ob_flush();
                            flush();
                            $LineaVector = [];
                            $LineaVector['data'][0]['id'] = $id;
                            $LineaVector['data'][0]['Metros_cuadrados'] = $m2;
                            $LineaVector['data'][0]['Realizaci_n'] = $realizacionLinea;
                            if ($descPorcRealización) {
                                $LineaVector['data'][0]['Porcentaje_Descuento_Realizaci_n'] = $descPorcRealización;
                                $LineaVector['data'][0]['Descuento_Realizaci_n'] = $descuentoRealizacionLinea;
                            }
                            $LineaVector['data'][0]['Montaje'] = $precioDelMontaje;
                            if ($descPorcMontaje) {
                                $LineaVector['data'][0]['Porcentaje_Descuento_Montaje'] = $descPorcMontaje;
                                $LineaVector['data'][0]['Descuento_de_Montaje'] = $descuentoMontajeLinea;
                            }
                            $LineaVector['data'][0]['Unit_Price'] = $precioLinea;
                            $LineaJson = json_encode($LineaVector);
                            $crm->actualizar("actualizarLinea", $LineaJson);
                            if ($crm->estado) {
                                echo '<p style="color:gray">Línea actualizada en el CRM: ' . $codLinea . '</p>';
                                @ob_flush();
                                flush();
                            } else {
                                echo '<p style="color:red">ERROR!!! NO SE HA ACTUALIZADO LA LÍNEA: ' . $codLinea . ' EN EL CRM</p>';
                                print_r($crm->respuestaError);
                                die();
                            }
                        } else {
                            echo '<p style="color:red">ERROR!!! LA LÍNEA ' . $codLinea . ' NO TIENE DEFINIDO EL MATERIAL</p>';
                            @ob_flush();
                            flush();
                            die();
                        }
                    } else {
                        echo '<p style="color:red">ERROR!!! LA LÍNEA ' . $codLinea . ' NO TIENE DEFINIDO EL ALTO O EL ANCHO</p>';
                        @ob_flush();
                        flush();
                        die();
                    }
                }
            } else {
                array_push($lineasNoIncluidas, $codLinea);
                echo '<p style="color:orange">LÍNEA ' . $codLinea . ' NO ESTÁ INCLUIDA EN EL CÁLCULO...</p>';
                @ob_flush();
                flush();
            }
        }
        /*RESULTADOS*/
        echo '<h1 style="color:green">RESULTADOS OT ' . $codOt . '</h1>';
        if (count($lineasNoIncluidas)) {
            echo '<p style="color:orange">LÍNEAS NO INCLUIDAS:</p>';
            print_r($lineasNoIncluidas);
        }
        echo '<p style="color:green">LÍNEAS INCLUIDAS:</p>';
        print_r($lineasIncluidas);
        /*TOTALES*/
        $totalRealizacion = $realizacion - $totalDescRealizacion;
        $totalMontaje = $montaje - $totalDescMontaje;
        $totalMontajeLogos = $montajeLogos - $totalDescMontajeLogos;
        $totalSinImpuesto = $totalRealizacion + $totalMontaje + $acabados + $logos + $totalMontajeLogos + $tomaDeMedidas;
        if ($impuesto = "IGIC") {
            $impuestoAplicado = ($totalSinImpuesto * 7) / 100;
            $totalConImpuesto = $totalSinImpuesto + $impuestoAplicado;
        } else if ($impuesto = "IVA") {
            $impuestoAplicado = ($totalSinImpuesto * 21) / 100;
            $totalConImpuesto = $totalSinImpuesto + $impuestoAplicado;
        } else {
            $totalConImpuesto = $totalSinImpuesto;
        }
        /*NOTAS*/
        if ($numVisuales && $numLogos) {
            echo '<p>EXISTE UNA MEZCLA DE VISUALES Y LOGOS EN LAS LÍNEAS</p>';
            $totalRealizacionVL = $totalRealizacion + $acabados + $logos;
            $totalMontajeVL = $totalMontaje + $totalMontajeLogos;
            echo '<p style="color:blue">Metros cuadrados: ' . number_format($totalM2, 2) . '</p>';
            echo '<p style="color:blue">Realización Visuales: ' . number_format($realizacion, 2) . '€</p>';
            if ($descPorcRealización) {
                echo '<p style="color:blue">Descuento pactado en Realización Visuales: ' . $descPorcRealización . '%</p>';
                echo '<p style="color:blue">Descuento aplicado en Realización Visuales: ' . number_format($totalDescRealizacion, 2) . '€</p>';
                echo '<p style="color:blue">Total Realización Visuales: ' . number_format($totalRealizacion, 2) . '€</p>';
            }
            echo '<p style="color:blue">Montaje Visuales: ' . number_format($montaje, 2) . '€</p>';
            if ($descPorcMontaje) {
                echo '<p style="color:blue">Descuento pactado en Montaje Visuales: ' . $descPorcMontaje . '%</p>';
                echo '<p style="color:blue">Descuento aplicado en Montaje Visuales: ' . number_format($totalDescMontaje, 2) . '€</p>';
                echo '<p style="color:blue">Total Montaje Visuales: ' . number_format($totalMontaje, 2) . '€</p>';
            }
            echo '<p style="color:blue">Logos - Realización: ' . number_format($logos, 2) . '€</p>';
            echo '<p style="color:blue">Logos - Montaje: ' . number_format($montajeLogos, 2) . '€</p>';
            if ($totalDescMontajeLogos) {
                echo '<p style="color:blue">Descripción del descuento en montaje de Logos: Gran Canaria – A partir de la Sexta unidad de rótulo se aplica un 25% de descuento; Tenerife – A partir de la Octava unidad de rótulo se aplica un 20% de descuento; Lanzarote y Fuerteventura – A partir de la Décimo Primer unidad de rótulo se aplica un 20% de descuento.</p>';
                echo '<p style="color:blue">Descuento aplicado en Montaje de Logos: ' . number_format($totalDescMontajeLogos, 2) . '€</p>';
                echo '<p style="color:blue">Total Montaje de Logos: ' . number_format($totalMontajeLogos, 2) . '€</p>';
            }
            if ($acabados) {
                echo '<p style="color:blue">Acabados Visuales: ' . number_format($acabados, 2) . '€</p>';
            }
            echo '<p style="color:blue">TOTAL REALIZACIÓN (Visuales + Acabados + Logos): ' . number_format($totalRealizacionVL, 2) . '€</p>';
            echo '<p style="color:blue">TOTAL MONTAJE (Visuales + Logos): ' . number_format($totalMontajeVL, 2) . '€</p>';
            if ($tomaDeMedidas) {
                echo '<p style="color:blue">TOMA DE MEDIDAS: ' . number_format($tomaDeMedidas, 2) . '€</p>';
            }
            echo '<p style="color:blue">TOTAL PRESUPUESTO SIN IMPUESTO: ' . number_format($totalSinImpuesto, 2) . '€</p>';
            echo '<p style="color:blue">IMPUESTO: ' . $impuesto . '</p>';
            echo '<p style="color:blue">TOTAL PRESUPUESTO CON IMPUESTO: ' . number_format($totalConImpuesto, 2) . '€</p>';
            echo '<p style="color:orange">Total Precios Líneas: ' . number_format($totalPreciosLineas, 2) . '€</p>';
        } else if ($numVisuales) {
            echo '<p>LAS LÍNEAS SON VISUALES</p>';
            echo '<p style="color:blue">Metros cuadrados: ' . number_format($totalM2, 2) . '</p>';
            echo '<p style="color:blue">Realización: ' . number_format($realizacion, 2) . '€</p>';
            if ($descPorcRealización) {
                echo '<p style="color:blue">Descuento pactado en Realización: ' . $descPorcRealización . '%</p>';
                echo '<p style="color:blue">Descuento aplicado en Realización: ' . number_format($totalDescRealizacion, 2) . '€</p>';
                echo '<p style="color:blue">Total Realización: ' . number_format($totalRealizacion, 2) . '€</p>';
            }
            echo '<p style="color:blue">Montaje: ' . number_format($montaje, 2) . '€</p>';
            if ($descPorcMontaje) {
                echo '<p style="color:blue">Descuento pactado en Montaje: ' . $descPorcMontaje . '%</p>';
                echo '<p style="color:blue">Descuento aplicado en Montaje: ' . number_format($totalDescMontaje, 2) . '€</p>';
                echo '<p style="color:blue">Total Montaje: ' . number_format($totalMontaje, 2) . '€</p>';
            }
            if ($acabados) {
                echo '<p style="color:blue">Acabados: ' . number_format($acabados, 2) . '€</p>';
            }
            if ($tomaDeMedidas) {
                echo '<p style="color:blue">Toma de medidas: ' . number_format($tomaDeMedidas, 2) . '€</p>';
            }
            echo '<p style="color:blue">TOTAL PRESUPUESTO SIN IMPUESTO: ' . number_format($totalSinImpuesto, 2) . '€</p>';
            echo '<p style="color:blue">IMPUESTO: ' . $impuesto . '</p>';
            echo '<p style="color:blue">TOTAL PRESUPUESTO CON IMPUESTO: ' . number_format($totalConImpuesto, 2) . '€</p>';
            echo '<p style="color:orange">Total Precios Líneas: ' . number_format($totalPreciosLineas, 2) . '€</p>';
        } else if ($numLogos) {
            echo '<p>LAS LÍNEAS SON LOGOS</p>';
            echo '<p style="color:blue">Metros cuadrados: ' . number_format($totalM2, 2) . '</p>';
            echo '<p style="color:blue">Realización: ' . number_format($logos, 2) . '€</p>';
            echo '<p style="color:blue">Montaje: ' . number_format($montajeLogos, 2) . '€</p>';
            if ($totalDescMontajeLogos) {
                echo '<p style="color:blue">Descripción del descuento en montaje de Logos: Gran Canaria – A partir de la Sexta unidad de rótulo se aplica un 25% de descuento; Tenerife – A partir de la Octava unidad de rótulo se aplica un 20% de descuento; Lanzarote y Fuerteventura – A partir de la Décimo Primer unidad de rótulo se aplica un 20% de descuento.</p>';
                echo '<p style="color:blue">Descuento aplicado en Montaje: ' . number_format($totalDescMontajeLogos, 2) . '€</p>';
                echo '<p style="color:blue">Total Montaje: ' . number_format($totalMontajeLogos, 2) . '€</p>';
            }
            if ($acabados) {
                echo '<p style="color:blue">Acabados: ' . number_format($acabados, 2) . '€</p>';
            }
            if ($tomaDeMedidas) {
                echo '<p style="color:blue">Toma de medidas: ' . number_format($tomaDeMedidas, 2) . '€</p>';
            }
            echo '<p style="color:blue">TOTAL PRESUPUESTO SIN IMPUESTO: ' . number_format($totalSinImpuesto, 2) . '€</p>';
            echo '<p style="color:blue">IMPUESTO: ' . $impuesto . '</p>';
            echo '<p style="color:blue">TOTAL PRESUPUESTO CON IMPUESTO: ' . number_format($totalConImpuesto, 2) . '€</p>';
            echo '<p style="color:orange">Total Precios Líneas: ' . number_format($totalPreciosLineas, 2) . '€</p>';
        } else if ($numTomasDeMedida) {
            echo '<p style="color:blue">Toma de medidas: ' . number_format($tomaDeMedidas, 2) . '€</p>';
            echo '<p style="color:blue">TOTAL PRESUPUESTO SIN IMPUESTO: ' . number_format($totalSinImpuesto, 2) . '€</p>';
            echo '<p style="color:blue">IMPUESTO: ' . $impuesto . '</p>';
            echo '<p style="color:blue">TOTAL PRESUPUESTO CON IMPUESTO: ' . number_format($totalConImpuesto, 2) . '€</p>';
            echo '<p style="color:orange">Total Precios Líneas: ' . number_format($totalPreciosLineas, 2) . '€</p>';
        }
        @ob_flush();
        flush();
    } else {
        echo '<p style="color:red">ERROR!!!</p>';
        print_r($crm->respuestaError);
        die();
    }
} catch (\Throwable $th) {
    echo '<p style="color:red">ERROR!!!</p>';
    var_dump($th);
}

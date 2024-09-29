<?php

require_once 'clases/respuestas_clase.php';
require_once 'clases/zoho_clase.php';

/*
require_once 'respuestas_clase.php';
require_once 'zoho_clase.php';
*/

class Crm extends Zoho
{
    private $respuestas;
    private $urlGet;
    private $urlQuery = "/crm/v5/coql";
    private $camposEmpresas = "Alias,C_d_vendedor,Rating,CIF_NIF1,Shipping_City,Billing_City,Contacto1,Contacto_relacionado,Correo_electr_nico,Created_By,C_d_forma_pago,C_digo_t_rminos_pago,Shipping_Code,Billing_Code,SIC_Code,C_digo_vendedor,Description,Shipping_Street,Billing_Street,Employees,Parent_Account,Shipping_State,Billing_State,Tag,Fax,Grupo_contable_cliente,Grupo_contable,Grupo_registro_IVA_neg,Record_Image,Annual_Revenue,Modified_By,Navision_nr,Account_Name,N1,Account_Number,Shipping_Country,Billing_Country,Ownership,Owner,Industry,Account_Site,Website,Ticker_Symbol,Phone,Phone_2,Tipo_de_empresa,Account_Type";
    private $camposOts = "Comentario,Completo,Created_By,C_digo,Departamentos_relacionados,Description,Empresa,Tag,Stage,Closing_Date,Fecha_de_previsi_n_de_OT,Firma,Fotos_de_la_OT,Lead_Source,Amount,Expected_Revenue,Associated_Products,Modified_By,Reason_For_Loss__s,Motivo_de_p_rdida2,Navision,Necesita_Presupuesto,Contact_Name,Account_Name,Deal_Name,Notion_ID,N_de_l_neas_relacionadas,N_mero_de_operaciones,Observaciones,PDV,PDV_relacionados,Prefijo,Related_quote,Subtipo_de_la_OT,Type,Tipo_de_cliente,Tipo_de_OT,Usuario_creador_Navision";
    private $camposMontajeImagenes = "Rango1,Rango2,Precio";
    private $camposPreciosLogos = "Rango1,Rango2,Precio";
    private $camposPreciosLogosMontaje = "Rango1,Rango2,Precio";
    private $camposRutas = "Name";
    public $estado = true;
    public $respuesta;
    public $respuestaError;
    public $mensajeError;

    function __construct()
    {
        try {
            $this->respuestas = new Respuestas;
        } catch (\Throwable $th) {
            $this->estado = false;
            $this->mensajeError = $th->getMessage();
            $this->respuestaError = [];
            $this->respuestaError[0] = false;
            $this->respuestaError[1] = "Error al crear el objeto Crm: " . $this->mensajeError;
            $this->respuestaError[2] = 500;
        }
    }

    public function query($query)
    {
        try {
            $zoho = new Zoho;
            $datosJson = [];
            $datosJson['select_query'] = $query;
            $json = json_encode($datosJson, JSON_FORCE_OBJECT);
            $datos = $zoho->post($this->urlQuery, $json);
            if ($datos[0]) {
                $this->respuesta = $datos;
            } else {
                $this->estado = false;
                $this->mensajeError = $datos[1];
                $this->respuestaError = $datos;
            }
        } catch (\Throwable $th) {
            $this->estado = false;
            $this->mensajeError = $th->getMessage();
            $this->respuestaError = [];
            $this->respuestaError[0] = false;
            $this->respuestaError[1] = "Error en el objeto CRM, en la función query: " . $this->mensajeError;
            $this->respuestaError[2] = 500;
        }
    }

    public function get($variable, $link = "")
    {
        try {
            $zoho = new Zoho;
            switch ($variable) {
                case 'modulos':
                    $this->urlGet = "/crm/v5/settings/modules";
                    break;

                case 'empresas':
                    $this->urlGet = "/crm/v5/Accounts?fields=" . $this->camposEmpresas;
                    break;

                case 'ots':
                    $this->urlGet = "/crm/v5/Deals?fields=" . $this->camposOts;
                    break;

                case 'preciosMontajeImagenes':
                    $this->urlGet = "/crm/v5/Precio_Montaje_Im_genes?fields=" . $this->camposMontajeImagenes;
                    break;

                case 'preciosLogos':
                    $this->urlGet = "/crm/v5/Precios_Logos?fields=" . $this->camposPreciosLogos;
                    break;

                case 'preciosLogosMontaje':
                    $this->urlGet = "/crm/v5/Precios_Montaje_Logos?fields=" . $this->camposPreciosLogosMontaje;
                    break;

                case 'rutas':
                    $this->urlGet = "/crm/v5/Rutas?fields=" . $this->camposRutas;
                    break;

                default:
                    $this->urlGet = $link;
                    break;
            }
            $datos = $zoho->get($this->urlGet);
            if ($datos[0]) {
                $this->respuesta = $datos;
            } else {
                $this->estado = false;
                $this->mensajeError = $datos[1];
                $this->respuestaError = $datos;
            }
        } catch (\Throwable $th) {
            $this->estado = false;
            $this->mensajeError = $th->getMessage();
            $this->respuestaError = [];
            $this->respuestaError[0] = false;
            $this->respuestaError[1] = "Error en el objeto CRM, en la función modulos: " . $this->mensajeError;
            $this->respuestaError[2] = 500;
        }
    }

    public function eliminar($variable, $ids)
    {
        try {
            $zoho = new Zoho;
            switch ($variable) {
                case 'lineas':
                    $this->urlGet = "/crm/v6/Products?wf_trigger=true&ids=" . $ids;
                    break;

                case 'empresas':
                    $this->urlGet = "/crm/v5/Accounts?fields=" . $this->camposEmpresas;
                    break;

                case 'ots':
                    $this->urlGet = "/crm/v5/Deals?fields=" . $this->camposOts;
                    break;
            }
            $datos = $zoho->delete($this->urlGet);
            if ($datos[0]) {
                $this->respuesta = $datos;
            } else {
                $this->estado = false;
                $this->mensajeError = $datos[1];
                $this->respuestaError = $datos;
            }
        } catch (\Throwable $th) {
            $this->estado = false;
            $this->mensajeError = $th->getMessage();
            $this->respuestaError = [];
            $this->respuestaError[0] = false;
            $this->respuestaError[1] = "Error en el objeto CRM, en la función modulos: " . $this->mensajeError;
            $this->respuestaError[2] = 500;
        }
    }

    public function actualizar($variable, $json)
    {
        try {
            $zoho = new Zoho;
            switch ($variable) {
                case 'actualizarOt':
                    $this->urlGet = "/crm/v6/Deals";
                    break;

                case 'actualizarLinea':
                    $this->urlGet = "/crm/v6/Products";
                    break;

                case 'ots':
                    $this->urlGet = "/crm/v5/Deals?fields=" . $this->camposOts;
                    break;
            }
            $datos = $zoho->put($this->urlGet, $json);
            if (isset($datos[1]['data'][0]['status']) && isset($datos[1]['data'][0]['code']) && $datos[1]['data'][0]['status'] == 'error' && $datos[1]['data'][0]['code'] == 'RECORD_IN_BLUEPRINT') {
                $datos = $zoho->put($this->urlGet, $json);
            }
            if ($datos[0]) {
                if (isset($datos[1]['data'][0]['status']) && $datos[1]['data'][0]['status'] == 'error') {
                    $this->estado = false;
                    $this->mensajeError = $datos[1];
                    $this->respuestaError = $datos;
                } else {
                    $this->respuesta = $datos;
                }
            } else {
                $this->estado = false;
                $this->mensajeError = $datos[1];
                $this->respuestaError = $datos;
            }
        } catch (\Throwable $th) {
            $this->estado = false;
            $this->mensajeError = $th->getMessage();
            $this->respuestaError = [];
            $this->respuestaError[0] = false;
            $this->respuestaError[1] = "Error en el objeto CRM, en la función modulos: " . $this->mensajeError;
            $this->respuestaError[2] = 500;
        }
    }

    public function agregar($variable, $json)
    {
        try {
            $zoho = new Zoho;
            switch ($variable) {
                case 'actualizarOt':
                    $this->urlGet = "/crm/v6/settings/Deals";
                    break;

                case 'empresas':
                    $this->urlGet = "/crm/v5/Accounts?fields=" . $this->camposEmpresas;
                    break;

                case 'ots':
                    $this->urlGet = "/crm/v5/Deals?fields=" . $this->camposOts;
                    break;
            }
            $datos = $zoho->post($this->urlGet, $json);
            if (isset($datos[1]['data'][0]['status']) && isset($datos[1]['data'][0]['code']) && $datos[1]['data'][0]['status'] == 'error' && $datos[1]['data'][0]['code'] == 'RECORD_IN_BLUEPRINT') {
                $datos = $zoho->post($this->urlGet, $json);
            }
            if ($datos[0]) {
                if (isset($datos[1]['data'][0]['status']) && $datos[1]['data'][0]['status'] == 'error') {
                    $this->estado = false;
                    $this->mensajeError = $datos[1];
                    $this->respuestaError = $datos;
                } else {
                    $this->respuesta = $datos;
                }
            } else {
                $this->estado = false;
                $this->mensajeError = $datos[1];
                $this->respuestaError = $datos;
            }
        } catch (\Throwable $th) {
            $this->estado = false;
            $this->mensajeError = $th->getMessage();
            $this->respuestaError = [];
            $this->respuestaError[0] = false;
            $this->respuestaError[1] = "Error en el objeto CRM, en la función modulos: " . $this->mensajeError;
            $this->respuestaError[2] = 500;
        }
    }
}

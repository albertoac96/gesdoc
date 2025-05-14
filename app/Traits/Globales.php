<?php

namespace App\Traits;
date_default_timezone_set("America/Mexico_City");

use Illuminate\Http\Request;
use App\Models\ReporteCambios;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


trait Globales{

    public function metodoComun() {
    return "OK";
    }

    private function RegistraCambio($tipo, $idDoc, $cDesc){
        $idUser = Auth::id();
       //return $tipo." ".$idtipo." ".$cDesc;
        $cambio = ReporteCambios::create([
            'cTipo' => $tipo,
            'idTipo' => $idDoc,
            'cDescripcion' => $cDesc,
            'idUsrAlta' => $idUser,
        ]);
        return "OK";

    }


    public function EjecutaJSONtree($PcSQL, $PcCampos, $PcCampoClave, $PcCampoPadre, $PidPadre)
    {

        $LcResp = "";

        try {


            $LcCad = $PcSQL;
            $LoRs = DB::select($LcCad);
            if ($LoRs == []) return "";

            $LcResp = $this->HijosDe($PidPadre, $LoRs, $PcCampoClave, $PcCampoPadre, $PcCampos);
        } catch (Exception $ex) {
            $LcResp = "[]";
        }

        return ($LcResp);
    }


    private function HijosDe($PiPadre, $PoRs, $PcCampoClave, $PcCampoPadre, $PcCampos)
    {


        $LcItems = "";
        $LaCampos = explode(',', $PcCampos);


        //BUSCA LOS HIJOS DEL PAPA DADO.				
        for ($LiH = 0; $LiH < count($PoRs); $LiH++) {


            $LoRowHijo = $PoRs[$LiH];

            // return $LoRowHijo->$PcCampoPadre;

            if ($PiPadre == $LoRowHijo->$PcCampoPadre) {
                //ES UN HIJO, LO AGREGA Y BUSCA TAMBIEN A SUS HIJOS.

                //SE AGREGA A LA LISTA EN CURSO.

                $LcItem = "";
                for ($LiC = 0; $LiC < count($LaCampos); $LiC++) {
                    $LcClave = $LaCampos[$LiC];
                    $LcValor = $LoRowHijo->$LcClave;


                    $LcItem = $LcItem . ',"' . $LcClave . '":"' . $LcValor . '"';

                    if ($LcClave == 'idMenu' && $LcValor == 1) {
                        $LcItem = $LcItem . ', "active": true';
                    }
                }




                //$LcItem = '{'.substr($LcItem,1).'}';


                //return $this->valores;

                //VE SI ESTE HIJO TIENE SUS PROPIOS HIJOS.
                $LbTieneHijos = false;

                for ($Lii = 0; $Lii < count($PoRs); $Lii++) {
                    $LoRow = $PoRs[$Lii];

                    //return $LoRow;
                    //return $LoRowHijo->$PcCampoClave;

                    if ($LoRow->$PcCampoPadre == $LoRowHijo->$PcCampoClave) {
                        $LbTieneHijos = true;
                        break;
                    }

                    //if($LbTieneHijos == true) $LbTieneHijos = 0;
                    //return $LbTieneHijos;
                }

                if ($LbTieneHijos) {
                    //TIENE HIJOS.				
                    //OBTIENE LOS HIJOS DEL PAPA DADO.	                               

                    $LcHijos = $this->HijosDe($LoRowHijo->$PcCampoClave, $PoRs, $PcCampoClave, $PcCampoPadre, $PcCampos);

                    //AGREGA LOS HIJOS AL PAPA.                                
                    $LcItem = $LcItem . ',"children":' . $LcHijos . '';
                }
                $LcItem = '{' . substr($LcItem, 1) . '}';

                $LcItems = $LcItems . "," . $LcItem;
                //return $LcItems;	
            }
        }
        if ($LcItems != "") $LcItems = substr($LcItems, 1);
        $LcItems = "[" . $LcItems . "]";


        return ($LcItems);
    }



    public function TraeMenu()
    {
        $LcResp = "";

        try {

            $PidPadre = 0;

            $LcCad = "select * from  (
				select C.* 	
				, ifnull((select P.iOrden from seg_menu as P where P.idMenu = C.idPadre),0) as iOrdenPapa
				from seg_menu as C where cEstatus = 'A'
				) as T1
				order by T1.iOrdenPapa, T1.iOrden   ";

            $LcResp = $this->EjecutaJSONTree($LcCad, "idMenu,cNombre,cTitulo,cDescripcion,cURL,cLogo,cPermiso", "idMenu", "idPadre", $PidPadre);
        } catch (Exception $ex) {
            $LcResp = "[]";
        }


        return ($LcResp);
    }



}
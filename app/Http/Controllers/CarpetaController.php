<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Carpetas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarpetaController extends Controller
{
    public function TraeCarpetas(Request $request)
    {
        $LcResp = "";

        $LcEquipo = $this->EquipoDelUsuario();

        try {

            $PidPadre = 0;

            $LcCad = "select * from  (
                    select C.* 	
                    , ifnull((select P.iOrden from carpetas as P where P.idCarpeta = C.idPadre),0) as iOrdenPapa
                    from carpetas as C where cEstatus = 'A' and idUsuario in (" . $LcEquipo . ")
                    ) as T1 
                    order by T1.iOrdenPapa, T1.iOrden  ";

            $LcResp = $this->EjecutaJSONTree($LcCad, "idCarpeta,cCarpeta,idPadre,iOrden,iOrdenPapa,cDescCarpeta", "idCarpeta", "idPadre", $PidPadre);
        } catch (Exception $ex) {
            $LcResp = "[]";
        }

        if ($LcResp == "") {
            $LcCarpetas = '[{ "idCarpeta": "0", "cCarpeta": "Mi biblioteca", "idPadre": "", "iOrden": "", "iOrdenPapa": "", "cDescCarpeta": "" }]';
            return ($LcCarpetas);
        }

        $LcCarpetas = '[{ "idCarpeta": "0", "cCarpeta": "Mi biblioteca", "idPadre": "", "iOrden": "", "iOrdenPapa": "", "cDescCarpeta": "", "children": ' . $LcResp . "}]";
        return ($LcCarpetas);
    }

    public function EquipoDelUsuario()
    {
        $idUser = Auth::id();

        $Rol = DB::select("select T2.* from
        model_has_roles as T1 
        left join roles as T2 on T1.role_id = T2.id
        where T1.model_id = " . $idUser);
        $Rol = $Rol[0]->id;

        if ($Rol != 5) {
            $idInvestigador = DB::select("select idInvestigador from rel_invuser where idAsistente = " . $idUser);
            $idInvestigador = $idInvestigador[0]->idInvestigador;
        } else {
            $idInvestigador = $idUser;
        }

        $equipo = DB::select("select T2.id from 
        rel_invuser as T1
        left join users as T2 on T1.idAsistente = T2.id
        where idInvestigador = " . $idInvestigador);

        $LcEquipo = $idInvestigador;

        for ($i = 0; $i < count($equipo); $i++) {
            $LcEquipo = $LcEquipo . "," . $equipo[$i]->id;
        }

        return $LcEquipo;
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



    public function TraeMenu(Request $request)
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


    public function CarpetasPadreDe($idCarpeta)
    {

        $LcResp = "";

        if ($idCarpeta == 0) {
            $LcFinal = '[{ "idCarpeta": "0", "cCarpeta": "Mi biblioteca" }] ';
            $LcFinal = json_decode($LcFinal);
            return $LcFinal;
        }

        try {

            $PidHijo = $idCarpeta;


            $LcResp = $this->CarpetasPadre($PidHijo);
            //$LcResp="[".$LcResp."]";

        } catch (Exception $ex) {
            $LcResp = 0;
        }



        $LcCad = "select idCarpeta, cCarpeta from carpetas where idCarpeta in (" . $LcResp . ") order by idPadre";
        $LoRs = DB::select($LcCad);
        $LoRs = json_encode($LoRs);
        $LoRs = substr($LoRs, 1);

        $LcFinal = '[{ "idCarpeta": "0", "cCarpeta": "Mi biblioteca" }, ' . $LoRs;
        $LcFinal = json_decode($LcFinal);
        return $LcFinal;
    }


    private function CarpetasPadre($PidHijo)
    {
        $LcCarpetas = "";


        try {





            $LcCad = "select idPadre from carpetas where idCarpeta = " . $PidHijo;





            $LoRs = DB::select($LcCad);
            if (empty($LoRs)) {
                //YA NO TIENE CARPETAS HIJAS.				
                return ($LcCarpetas . $PidHijo);
            } else {
                //TIENE CARPETAS HIJAS.
                $LcCarpetas = $PidHijo;

                for ($Lii = 0; $Lii < count($LoRs); $Lii++) {
                    $LcCarpetas = $LcCarpetas . "," . $this->CarpetasPadre($LoRs[$Lii]->idPadre);
                }
            }
        } catch (Exception $ex) {
            $LcCarpetas = 0;
        }

        return ($LcCarpetas);
    }


    public function NombreDe($id){
        $item = Carpetas::where("idCarpeta","=",$id)
        ->get();
        return $item[0]->cCarpeta;
    }

    public function registraCarpeta(Request $request){
        $idUser = Auth::id();
        $item = Carpetas::create([
            'cCarpeta' => $request->cNombre,
            'idPadre' => $request->idPadre,
            'cDescCarpeta' => $request->cDesc,
            'idUsuario' => $idUser,
            
        ]);
        $idCarpeta = $item->idCarpeta;
        return $idCarpeta;
    }
}

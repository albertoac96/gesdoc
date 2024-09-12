<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BuscadorController extends Controller
{
  

    public function ResultadosBuscador(Request $request){
        $PcBusqueda = $request->cBusqueda;
        $PidCarpeta = $request->idCarpetas;
        $PidTema = $request->idTemas;
        $PiNota = $request->iNota;
        $PiPDF = $request->iPDF;
        $PiBusqueda = $request->iBusqueda;
        $PiTxtCompleto = $request->iTxtCompleto;
        $PidAutor = $request->idAutor;
        $PiAnyoDe = $request->cAnyoDe;
        $PiAnyoA = $request->cAnyoA;
        $PidPublicacion = $request->idPublicacion;

        if($PidCarpeta == "") $PidCarpeta = 0;
        if($PidTema == "") $PidTema = 0;
        if($PidAutor == "") $PidAutor = 0;
        if($PiAnyoDe == "") $PiAnyoDe = 0;
        if($PiAnyoA == "") $PiAnyoA = 0;
        if($PiNota == "") $PiNota = 1;
        if($PiPDF == "") $PiPDF = 1;
        if($PiBusqueda == "") $PiBusqueda = 1;
        if($PiTxtCompleto == "") $PiTxtCompleto = 0;
        if($PidPublicacion == "") $PidPublicacion = 0;

        if($PidCarpeta > 0){
            $LcCarpetas = $this->CarpetasHija($PidCarpeta);
        } else {
            $LcCarpetas = $PidCarpeta;
        }

       //BUSCA TAMBIEN EN NOTAS.
				
				$LcCad = "select D.idDocumento as id, replace(D.cResumenDoc, '<LF>', '<br>') as cDesc, D.cTituloDoc as cTitulo, D.cURL, D.cAutores, 
				ifnull(T7.cImagen, 'docDefault.jpg') as cImagen, ifnull(Cont.idContPdf, '0') as Contenido,
                T2.sEnDocs, T2.sEnAtr, F.dInicio, F.dFin , T2.sEnNotas as idNota, T4.cTipoDoc, T4.cIcono, ifnull(T6.cExtension, 'NA') as cExtension, ifnull(T6.cIcono, 'mdi-file-question-outline') as cIconExt, ifnull(T6.cColor, 'black') as cExtColor, ifnull(Cont.idContPdf, '0') as Contenido ";
				
				
				
				if($PiNota == 2){
					
					$LcCad .=" , T2.sEnNotas from (
						select idDocumento, sum(iEnDocs) as sEnDocs, sum(iEnAtr) as sEnAtr , sum(iEnNotas) as sEnNotas from (
						select idDocumento, 0 as iEnDocs, 0 as iEnAtr, 1 as iEnNotas, 0 as iEnPdf  
						from notas where 1=1 ";
					if($PcBusqueda != ""){
						$LcCad .= " and match (cTitulo, cNota) AGAINST ";
						if ($PiTxtCompleto == 1){
							$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
						} else {
							$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
						}
					}
					
				} else if ($PiPDF == 3){
					$LcCad .=" , T2.sEnPdf from (
						select idDocumento, sum(iEnDocs) as sEnDocs, sum(iEnAtr) as sEnAtr, sum(iEnPdf) as sEnPdf from (
						select idDocumento, 0 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 1 as iEnPdf 
						from cont_pdf where 1=1 ";
					if($PcBusqueda != ""){
						$LcCad .= " and match (cContenido) AGAINST ";
						if ($PiTxtCompleto == 1){
							$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
						} else {
							$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
						}
					}
				} else {
            
					
					if ($PiNota == 1){
						$LcCad .=", T2.sEnNotas ";
					}
					if ($PiPDF == 2){
						$LcCad .=", T2.sEnPdf ";
					}
					$LcCad .=" from (
						select idDocumento, sum(iEnDocs) as sEnDocs, sum(iEnAtr) as sEnAtr ";
					if ($PiNota == 1){
						$LcCad .=", sum(iEnNotas) as sEnNotas ";
					}
					if ($PiPDF == 2){
						$LcCad .=", sum(iEnPdf) as sEnPdf ";
					}
					if ($PiBusqueda == 1){
						$LcCad .=" from (
							SELECT idDocumento, 1 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf 
							FROM documentos WHERE 1=1 ";
						if($PcBusqueda != ""){					
							$LcCad .= " and MATCH (cTituloDoc, cResumenDoc) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
						$LcCad .=" union
							select idDocumento, 0 as iEnDocs, 1 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf 
							from rel_docatr WHERE 1=1 ";
						if($PcBusqueda != ""){
							$LcCad .= " and	match (cValor) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
						$LcCad .=" union 
							select idDoc as idDocumento, 0 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf  
							from (
							select idAutor from cat_autores where 1=1 ";
						if($PcBusqueda != ""){
							$LcCad .= " and	match (cNombre) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
						$LcCad .=" ) as AUTOR
							left join rel_docautor as REL on REL.idAutor = AUTOR.idAutor ";
					}
					
					
					
					
					if ($PiBusqueda == 2){
						$LcCad .=" from (
							SELECT idDocumento, 1 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf 
							FROM documentos WHERE 1=1 ";
						if($PcBusqueda != ""){
							$LcCad .=" and MATCH (cTituloDoc) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";	
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
					}
					if ($PiBusqueda == 3){
						$LcCad .=" from (
							SELECT idDocumento, 1 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf 
							FROM documentos WHERE 1=1 ";
						if($PcBusqueda != ""){	
							$LcCad .=" and MATCH (cResumenDoc) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}		
					}
					if ($PiBusqueda == 4){
						$LcCad .=" from (
							select idDocumento, 0 as iEnDocs, 1 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf 
							from rel_docatr where 1=1 "; 
						if($PcBusqueda != ""){	
							$LcCad .= " and match (cValor) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}	
						$LcCad .=" union 
							select idDoc as idDocumento, 0 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 0 as iEnPdf  
							from (
							select idAutor from cat_autores where 1=1 ";
						if($PcBusqueda != ""){
							$LcCad .= " and	match (cNombre) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
						$LcCad .=" ) as AUTOR
							left join rel_docautor as REL on REL.idAutor = AUTOR.idAutor ";		
					}
					
					if($PiNota == 1){
						$LcCad .=" union
							select idDocumento, 0 as iEnDocs, 0 as iEnAtr, 1 as iEnNotas, 0 as iEnPdf  
							from notas where 1=1 ";
						if($PcBusqueda != ""){
							$LcCad .= " and match (cTitulo, cNota) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
					}
					
					
					if ($PiPDF == 2){
						$LcCad .=" union
							select idDocumento, 0 as iEnDocs, 0 as iEnAtr, 0 as iEnNotas, 1 as iEnPdf 
							from cont_pdf where 1=1 ";
						if($PcBusqueda != ""){
							$LcCad .= " and match (cContenido) AGAINST ";
							if ($PiTxtCompleto == 1){
								$LcCad .=" ('\"".$PcBusqueda."\"' IN BOOLEAN MODE) ";
							} else {
								$LcCad .=" ('*".$PcBusqueda."*' IN BOOLEAN MODE) ";	
							}
						}
					}
					
					
							
					
			}		
		
//AQUI TODOS		
			$LcCad .=" ) as T1 group by idDocumento ) as T2
					inner join documentos as D on D.idDocumento = T2.idDocumento
					left join rel_docfecha as F on F.idDoc = T2.idDocumento ";
			
			
			$LcCad .=" left join rel_docautor as A on A.idDoc = T2.idDocumento
					left join rel_pubdoc as P on P.idDocumento = T2.idDocumento
					left join rel_doctema as T on T.idDocumento = T2.idDocumento
					left join cat_tipodoc as T4 on D.idTipoDoc = T4.idTipoDoc
					left join (select * from rel_archivosdoc where iMostrar = 1)  as T7 on D.idDocumento = T7.idDocumento
                    left join cat_extensiones as T6 on T7.idExt = T6.idExtension
                    left join cont_pdf as Cont on D.idDocumento = Cont.idDocumento ";

			$LcCad .=" where 1=1
					and D.cEstatus = 'A' ";
					
			if($PidPublicacion > 0) $LcCad .=" and P.idPublicacion = ".$PidPublicacion;
			if($PidTema > 0) $LcCad .=" and T.idTema = ".$PidTema;
			if($PidCarpeta > 0)	$LcCad .=" and D.idCarpeta in (".$LcCarpetas.") ";
			if($PidAutor > 0) $LcCad .=" and A.idAutor = ".$PidAutor;
			/*if($PcCaja != "0") $LcCad .=" and RDAC.cValor = ".$PcCaja;
			if($PcTomo != "0") $LcCad .=" and RDAT.cValor = ".$PcTomo;
			if($PcVolumen != "0") $LcCad .=" and RDAV.cValor = ".$PcVolumen;*/
			
			
			if(!($PiAnyoDe == 0 && $PiAnyoA == 0)){
				$LiDesde = 0; $LiHasta = 3000;
				if($PiAnyoDe > 0) $LiDesde = $PiAnyoDe;
				if($PiAnyoA < 3000) $LiHasta = $PiAnyoA;
					if($LiDesde > 0){
						$LcCad .= " and year(F.dFin) >= ".$LiDesde;
					}
					if($LiHasta < 3000){
						$LcCad .= " and year(F.dInicio) <= ".$LiHasta;
					}
				
			} 
				
					
			$LcCad .=" group by D.idDocumento  ";	
            
            //return $LcCad;
            $LcResp = DB::select($LcCad);
            return $LcResp;
    }


    public function CarpetasHijasDe($idCarpeta)
    {
        $LcResp = "";
        try {

            $PidHijo = $idCarpeta;
            $LcResp = $this->CarpetasHija($PidHijo);
            //$LcResp="[".$LcResp."]";
        } catch (Exception $ex) {
            $LcResp = 0;
        }
        return $LcResp;
    }


    private function CarpetasHija($PidPadre)
    {
        $LcCarpetas = "";
        try {
            $LcCad = "select idCarpeta from carpetas where idPadre = ".$PidPadre;
            $LoRs = DB::select($LcCad);
            if (empty($LoRs)) {
                //YA NO TIENE CARPETAS HIJAS.				
                return ($LcCarpetas . $PidPadre);
            } else {
                //TIENE CARPETAS HIJAS.
                $LcCarpetas = $PidPadre;

                for ($Lii = 0; $Lii < count($LoRs); $Lii++) {
                    $LcCarpetas = $LcCarpetas . "," . $this->CarpetasHija($LoRs[$Lii]->idCarpeta);
                }
            }
        } catch (Exception $ex) {
            $LcCarpetas = 0;
        }
        return ($LcCarpetas);
    }


}

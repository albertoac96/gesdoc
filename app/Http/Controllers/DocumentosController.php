<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentos;
use App\Models\Carpetas;
use App\Models\catExtensiones;
use App\Models\Catalogos\catTemas;
use App\Models\Catalogos\catPublicaciones;
use App\Models\Catalogos\catAutores;
use App\Models\Catalogos\catEtiquetas;
use App\Models\Docs\Notas;
use App\Models\Docs\Relaciones\conAtributos;
use App\Models\Docs\Relaciones\conArchivos;
use App\Models\Docs\Relaciones\conEnlaces;
use App\Models\Docs\Relaciones\conDocs;
use App\Models\Docs\Relaciones\conTemas;
use App\Models\Docs\Relaciones\conPublicaciones;
use App\Models\Docs\Relaciones\conAutores;
use App\Models\Docs\Relaciones\conFechas;
use App\Models\Docs\Relaciones\conEtiquetas;


use App\Traits\Globales;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DocumentosController extends Controller
{
    use Globales;

    public $dirBase = "/media/compartida/Haydee/gesdoc/public";
    //public $dirBase = "/home/desarrollo01/gesdocpublic";


    public function ListaDocs($id){

        $LcEquipo = $this->EquipoDelUsuario();

        $LcCad = "select T1.idCarpeta as id, T1.cDescCarpeta as cDesc, T1.cCarpeta as cTitulo, 0 as cURL, T1.created_at as dInicio , T1.created_at as dFin, 
        'C' as cTipoDoc, 'mdi-folder' as cIcono, 0 as Contenido, 0 as idNota, 
        0 as cExtension, 0 as cIconExt, 0 as cExtColor, T2.name as cAutores, 0 as cImagen
        from carpetas as T1
        left join users as T2 on T1.idUsuario = T2.id
        where T1.idPadre = ".$id." and T1.cEstatus = 'A' and idUsuario in (".$LcEquipo.")


        union all
        select T1.idDocumento as id, replace(T1.cResumenDoc, '<LF>', ' <br> ') as cDesc, T1.cTituloDoc as cTitulo, T7.cNombre as cURL, T5.dInicio, T5.dFin, T4.cTipoDoc, T4.cIcono, 
        ifnull(T2.idContPdf, '0') as Contenido, ifnull(T3.idNota, '0') as idNota,
        ifnull(T6.cExtension, 'NA'), ifnull(T6.cIcono, 'mdi-file-question-outline') as cIconExt, ifnull(T6.cColor, 'black') as cExtColor, T1.cAutores, ifnull(T7.cImagen, 'docDefault.jpg')
        from(
        (select *
        from documentos
        where idCarpeta = ".$id." and cEstatus = 'A' and idUsrAlta in (".$LcEquipo.")
        order by cTituloDoc) as T1


        left join cont_pdf as T2 on T1.idDocumento = T2.idDocumento 
        left join cat_tipodoc as T4 on T1.idTipoDoc = T4.idTipoDoc
        left join rel_docfecha as T5 on T1.idDocumento = T5.idDoc
        left join (select * from rel_archivosdoc where iMostrar = 1)  as T7 on T1.idDocumento = T7.idDocumento
        left join cat_extensiones as T6 on T7.idExt = T6.idExtension
        left join (select * from notas ORDER BY idNota DESC LIMIT 1) as T3 on T1.idDocumento = T3.idDocumento)";
        
        $LcResp = DB::select($LcCad);
        return $LcResp;
    }

    public function DocsRecientes(){
        $LcCad ="select T1.idDocumento, T1.cTituloDoc, ifnull(T3.cImagen, 'docDefault.jpg') as cImagen, T1.cResumenDoc, T2.cTipoDoc, T2.cIcono from 
        documentos as T1
        left join cat_tipodoc as T2 on T1.idTipoDoc = T2.idTipoDoc
        left join (select * from rel_archivosdoc where iMostrar = 1) as T3 on T1.idDocumento = T3.idDocumento
        where T1.cEstatus = 'A'
        order by T1.created_at DESC limit 16";

        $LcResp = DB::select($LcCad);
        return $LcResp;
    }


    public function EquipoDelUsuario(){
        $idUser = Auth::id();

        $Rol = DB::select("select T2.* from
        model_has_roles as T1 
        left join roles as T2 on T1.role_id = T2.id
        where T1.model_id = ".$idUser);
        $Rol = $Rol[0]->id;

        if($Rol != 5){
            $idInvestigador = DB::select("select idInvestigador from rel_invuser where idAsistente = ". $idUser);
            $idInvestigador = $idInvestigador[0]->idInvestigador;
        } else {
            $idInvestigador = $idUser;
        }

        $equipo = DB::select("select T2.id from 
        rel_invuser as T1
        left join users as T2 on T1.idAsistente = T2.id
        where idInvestigador = ".$idInvestigador);

        $LcEquipo = $idInvestigador;

        for($i=0; $i < count($equipo); $i++){
            $LcEquipo = $LcEquipo.",".$equipo[$i]->id;
        }

        return $LcEquipo;
    }

    public function InfoDoc($id){
        $info = $this->TraeBasicos($id);
        $ubicacion = $this->CarpetaDoc($id);
        $autores = $this->AutoresDelDoc($id);
        $temas = $this->TraeTemasDelDoc($id);
        $notas = $this->NotasDelDoc($id);
        $atributos = $this->AtributosDelDoc($id);
        $fechas = $this->FechasDelDoc($id);
        $etiquetas = $this->EtiquetasDelDoc($id);
        $archivos = $this->ArchivosDelDoc($id);
        $enlaces = $this->EnlacesDelDoc($id);
        $relacionados = $this->DocsRelacionados($id);

        
     
        $Total = array_merge(array('info' => $info), 
                    array('ubi' => $ubicacion),  
                    array('autores' => $autores), 
                    array('temas' => $temas), 
                    array('notas' => $notas), 
                    array('atributos' => $atributos),
                    array('fechas' => $fechas),
                    array('etiquetas' => $etiquetas),
                    array('archivos' => $archivos),
                    array('enlaces' => $enlaces),
                    array('relacionados' => $relacionados)
                );
        return $Total;
    }

    public function TraeBasicos($id){
        $LcCad = "select T1.created_at, T1.cTituloDoc, replace(T1.cResumenDoc, '<LF>', '<br>') as cResumenDoc, T2.cTipoDoc, T2.idTipoDoc, T2.cIcono, T4.cPublicacion, T4.idPublicacion, T1.cEstatus from  documentos as T1
                    left join cat_tipodoc as T2 on T1.idTipoDoc = T2.idTipoDoc
                    left join rel_pubdoc as T3 on T1.idDocumento = T3.idDocumento
                    left join cat_publicaciones as T4 on T3.idPublicacion = T4.idPublicacion
                    where T1.idDocumento =".$id;
        $LcResp = DB::select($LcCad);
        return $LcResp;
    }

    public function AutoresDelDoc($id){
        
        $LcCad = "select T1.idAutor, T1.iOrden, T2.cTipoAutor, T2.cColor, T3.cNombre from
        rel_docautor as T1
        left join cat_tipoautor as T2 on T1.idTipoAutor = T2.idTipoAutor
        left join cat_autores as T3 on T1.idAutor = T3.idAutor
        where idDoc = ".$id."
        order by iOrden";
       
        $LcResp = DB::select($LcCad);
        return $LcResp;

    }

    public function CarpetaDoc($id){

    
        $idCarpeta = DB::select("select idCarpeta from documentos where idDocumento = ".$id);
        $LcResp = $this->CarpetasPadre($idCarpeta[0]->idCarpeta);
        
        $carpetas = $this->NombresCarpetas($LcResp);
        return $carpetas;

    }
    private function CarpetasPadre($PidHijo){
        $LcCarpetas = "";
        $LcCad = "select idPadre from carpetas where idCarpeta = ".$PidHijo;
        $LoRs = DB::select($LcCad);
            if(empty($LoRs)){
                //YA NO TIENE CARPETAS HIJAS.				
                return($LcCarpetas.$PidHijo);
            } else {
                //TIENE CARPETAS HIJAS.
                $LcCarpetas = $PidHijo;

                for($Lii=0; $Lii < count($LoRs); $Lii++){
                        $LcCarpetas = $LcCarpetas.",".$this->CarpetasPadre($LoRs[$Lii]->idPadre);
                }
            }

            return($LcCarpetas);	
    }
    private function NombresCarpetas($Carpetas){
        $LcResp = "";
        $LcCad ="select cCarpeta, idCarpeta from carpetas where idCarpeta in (".$Carpetas.") order by idPadre";
        $LcResp = DB::select($LcCad);
        $carp0 = array(
            "cCarpeta" => "Mi biblioteca",
            "idCarpeta" => 0
        );
        array_unshift($LcResp, $carp0);

       
        return $LcResp;
    }

    public function TraeTemasDelDoc($id){
        $LcResp = catTemas::select(
            "temas.idTema",
            "temas.cTema",
            "temas.cDescTema",
            "temas.cColor"
        )
        ->leftJoin("rel_doctema", "rel_doctema.idTema", "=", "temas.idTema")
        ->where("rel_doctema.idDocumento", "=", $id)
        ->where("rel_doctema.cEstatus", "=", "A")
        ->get();

        return $LcResp;
    }

    public function NotasDelDoc($id){
        $LcResp = Notas::select(
            "notas.idNota",
            "notas.cTitulo",
            "notas.cNota",
            "notas.created_at",
            "users.name",
            "users.cAvatar"
        )
        ->leftJoin("users", "users.id", "=", "notas.idUsrAlta")
        ->where("notas.idDocumento", "=", $id)
        ->where("notas.cEstatus", "=", "A")
        ->get();

        return $LcResp;
    }

    public function AtributosDelDoc($id){
        $LcResp = conAtributos::select(
            "cat_atributos.*",
            "rel_docatr.cValor",
            "rel_docatr.idDocAtr",
        )
        ->join("cat_atributos", "cat_atributos.idAtributo", "=", "rel_docatr.idAtributo")
        ->where("rel_docatr.idDocumento", "=", $id)
        ->where("cat_atributos.cEstatus", "=", "A")
        ->where("rel_docatr.cEstatus", "=", "A")
        ->get();

        return $LcResp;
    }

    public function EtiquetasDelDoc($id){

        $LcCad = "select T1.cEtiqueta, T1.idEtiqueta, T2.idRel from
                    cat_etiquetas as T1
                    left join rel_etiquetadoc as T2 on T1.idEtiqueta = T2.idEtiqueta
                    left join documentos as T3 on T2.idDocumento = T3.idDocumento
                    where T2.cEstatus = 'A' and T3.idDocumento = ".$id;
        $LcResp = DB::select($LcCad);
        /*$LcResp = Documentos::select(
            "cat_etiquetas.cEtiqueta",
            "cat_etiquetas.idEtiqueta",
            "rel_etiquetadoc.idRel"
        )
        ->leftJoin("rel_etiquetadoc", "rel_etiquetadoc.idDocumento", "=", "documentos.idDocumento")
        ->leftJoin("cat_etiquetas", "cat_etiquetas.idEtiqueta", "=", "rel_etiquetadoc.idEtiqueta")
        ->where("documentos.idDocumento", "=", $id)
        ->get();
        
        //if(count($LcResp) == 0) $LcResp = [];*/

        return $LcResp;
    }

    public function FechasDelDoc($id){
        $LcResp = Documentos::select(
            "rel_docfecha.dInicio",
            "rel_docfecha.dFin",
        )
        ->leftJoin("rel_docfecha", "rel_docfecha.idDoc", "=", "documentos.idDocumento")
        ->where("documentos.idDocumento", "=", $id)
        ->get();

        return $LcResp;
    }

    public function ArchivosDelDoc($id){
        $LcResp = conArchivos::select(
            "rel_archivosdoc.*",
            "cat_extensiones.cExtension"
        )
        ->leftJoin("cat_extensiones", "cat_extensiones.idExtension", "=", "rel_archivosdoc.idExt")
        ->where("idDocumento","=",$id)
        ->where('cEstatus', "=", "A")
        ->orderBy('created_at')
        ->get();
        return $LcResp;
    }

    public function EnlacesDelDoc($id){
        $LcResp = conEnlaces::where("idDocumento","=",$id)
        ->where('cEstatus', '=', "A")
        ->orderBy('created_at')
        ->get();
        return $LcResp;
    }

    public function DocsRelacionados($id){
        $LcDocs = conDocs::where("idDocumento","=",$id)
        ->orWhere("idDocRelacionado", "=", $id)
        ->orderBy('created_at')
        ->get();

        
        $LidDocs = "";
        foreach($LcDocs as $Doc){
            if($Doc->idDocRelacionado == $id){
                $LidDocs = $LidDocs.$Doc->idDocumento.", ";
            } else {
                $LidDocs = $LidDocs.$Doc->idDocRelacionado.", ";
            }
            
        }
        $LidDocs = substr($LidDocs, 0, -2);
        //$LidDocs = $LidDocs."]";
        if($LidDocs == ""){
            return array();
        }
        $LcCad ="select T1.idDocumento, T1.cTituloDoc, ifnull(T3.cImagen, 'docDefault.jpg') as cImagen, T1.cResumenDoc, T2.cTipoDoc, T2.cIcono from 
        documentos as T1
        left join cat_tipodoc as T2 on T1.idTipoDoc = T2.idTipoDoc
        left join (select * from rel_archivosdoc where iMostrar = 1) as T3 on T1.idDocumento = T3.idDocumento
        where T1.idDocumento in (".$LidDocs.") and T1.cEstatus = 'A'
        order by T1.idDocumento";
        $LcResp = DB::select($LcCad);

       
        
        return $LcResp;
    }
    
    public function UploadArchivo(Request $request){

        $PcTipo = $request->tipo;
        $Pid = $request->id;
        $Archivo = $_FILES;

       

        if($PcTipo == "soloACarpeta"){ //El archivo se subira solo y se creara un registro de documento nuevo
            $id = $this->UploadSoloACarpeta($Pid, $Archivo);
            //return $estatus;
        }

        if($PcTipo == "adjuntoADoc"){ //El archivo que se sube esta asociado a un id de documento
            $id = $this->UploadAdjuntoADoc($Pid, $Archivo);
           // return $estatus;
        }

        if($PcTipo == "actualizaArchivo"){ //El archivo se reemplazara por otro, el id es del Archivo

        }

        $LcInfo = conArchivos::where("idRelArDoc","=",$id)
        ->get();
        

        return response()->json([
            'info' => $LcInfo[0],
            'estatus' => '200',
        ]);
    }

    private function UploadSoloACarpeta($LidCarpeta, $LArchivo){
        $LcNombre = $LArchivo["archivo"]["name"];
        
        $LidDoc = $this->RegistraDocBasico($LcNombre, $LidCarpeta);
        $LidArchivo = $this->UploadAdjuntoADoc($LidDoc, $LArchivo);
        if($LidArchivo == 0) return "Hubo un problema";
        return $LidArchivo;
    }

    private function UploadAdjuntoADoc($LidDocumento, $LArchivo){
        $idUser = Auth::id();
        $extension = $this->sacarExtArchivo($LArchivo["archivo"]["name"]);
        
        
        $adjuntos = $this->ArchivosDelDoc($LidDocumento);
        if(count($adjuntos) == 0){
            $iMostrar = 1;
        } else {
            $iMostrar = 0;
        }
        
        $Archivo = conArchivos::create([
            'idDocumento' => $LidDocumento,
            'cNombre' => $LArchivo["archivo"]["name"],
            'iMostrar' => $iMostrar,
            'cImagen' => "",  
            'idExt' => $extension, 
            'idUsrAlta' => $idUser,
            
        ]);
        $idArchivo = $Archivo->idRelArDoc;

        $estatus = $this->GuardaArchivo($LArchivo, $LidDocumento, $idArchivo);
        if($estatus != "OK"){
            return 0;
        }
        
        
        

        $imagen = $this->sacaImagen($LArchivo, $LidDocumento, $idArchivo);

        

        return $idArchivo;
    }

    private function sacaImagen($archivo, $idDoc, $idArchivo){
        $cNombreArchivo = $archivo["archivo"]["name"];
        $dirArchivo = $this->dirBase."/archivos"; 
        $dirImg = $this->dirBase."/images/archivos"; 
        
        $cURL = strtolower($cNombreArchivo);
        $pos = strrpos($cURL, ".");
        $ext = substr($cURL, $pos+1);

        if($ext == "pdf"){
            shell_exec("convert -colorspace sRGB ".$dirArchivo."/".$idDoc."-".$idArchivo.".".$ext."[0] -quality 50 ".$dirImg."/".$idArchivo.".webp");
            conArchivos::where('idRelArDoc', $idArchivo)->update(['cImagen'=>$idArchivo.".webp"]);
        }
       

        if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "tiff" || $ext == "tif" || $ext == "gif"){
            shell_exec("convert -colorspace sRGB ".$dirArchivo."/".$idDoc."-".$idArchivo.".".$ext." -quality 50 ".$dirImg."/".$idArchivo.".webp");
            conArchivos::where('idRelArDoc', $idArchivo)->update(['cImagen'=>$idArchivo.".webp"]);
        }

        
        return "OK";


        
    }

    private function UploadActualiza($LidArchivo, $LArchivo){

    }

    private function sacarExtArchivo($LcNombre){
        $cURL = strtolower($LcNombre);
        $pos = strrpos($cURL, ".");
        $ext = substr($cURL, $pos+1);
        $id = 0;

        $extensiones = DB::select("select * from cat_extensiones");
            for($Ei = 0; $Ei < count($extensiones); $Ei++){
                $BDDext = strtolower($extensiones[$Ei]->cExtension);
                if($BDDext == $ext){
                    $idext = $extensiones[$Ei]->idExtension;
                    
                    $id = $idext;
                    break;
                } 
                


      
            }

            if($id == 0){
                $newExtension = catExtensiones::create([
                    'cExtension' => $ext,
                    
                ]);
                $id = $newExtension->idExtension;
               
            }
            return $id;
    }

    private function RegistraDocBasico($LcNombre, $LidCarpeta){
        $idUser = Auth::id();
        $Doc = Documentos::create([
            'idCarpeta' => $LidCarpeta,
            'idTipoDoc' => 9,
            'cResumenDoc' => "",
            'cTituloDoc' => $LcNombre,   
            'idUsrAlta' => $idUser,
            
        ]);
        $idDocumento = $Doc->idDocumento;
        $this->RegistraCambio('Creación', $idDocumento, 'Alta del documento');
        return $idDocumento;
    }

    public function uploadDocumento(Request $request){
        
        $PidTipoDoc = $request->TipoDoc;
        $PidCarpeta = $request->idCarpeta;
        $PcPeriodica = $request->Periodica;
        $PcTitulo = $request->titulo;
        $PcInfo = $request->info;
        $PcTypeP = $request->typePeriodica;

        

        $PcResumen = "";


        if($PcInfo == ""){ //No trae info del DOI o ISBN
            $LcResp = $this->registraDoc($PidTipoDoc["value"], $PidCarpeta, $PcTitulo, $PcPeriodica, $PcResumen);
            return $LcResp;
        } else {//Trae info del DOI o ISBN
           
          $idDoc = $this->registraDoc($PidTipoDoc["value"], $PidCarpeta, $PcTitulo, $PcPeriodica, $PcResumen);
            if($PidTipoDoc["value"] == 1 || $PidTipoDoc["value"] == 15 ){
                $enlaces = $this->regEnlaceInt($PcInfo["URL"], $idDoc, 'CR');
                $autores = $this->regAutoresInt($PcInfo["author"], 'CR');
                $this->AsociaAutoresADoc($autores, $idDoc);
                $fechas = $this->regFechasInt($PcInfo["created"]["date-time"], $idDoc);
                if(array_key_exists('subject', $PcInfo)){
                    $etiquetas = $this->regEtiquetas($PcInfo["subject"], $idDoc, 'CR');
                }
                
                $atributos = $this->regAtr($PcInfo, $idDoc, $request->cIDWeb, 'CR');
                return $idDoc; 
            }
            if($PidTipoDoc["value"] == 3){//Libro
                $enlaces = $this->regEnlaceInt($PcInfo["volumeInfo"]["canonicalVolumeLink"], $idDoc, 'GB');
                $autores = $this->regAutoresInt($PcInfo["volumeInfo"]["authors"], 'GB');
                $this->AsociaAutoresADoc($autores, $idDoc);
                $fechas = $this->regFechasInt($PcInfo["volumeInfo"]["publishedDate"]."-01-01", $idDoc);
                if(array_key_exists('categories', $PcInfo["volumeInfo"])){
                    $etiquetas = $this->regEtiquetas($PcInfo["volumeInfo"]["categories"], $idDoc, 'GB');
                }
                
                $atributos = $this->regAtr($PcInfo, $idDoc, $request->cIDWeb, 'GB');
                return $idDoc; 

            }

            if($PidTipoDoc["value"] == 2){ //Seccion de libro
                $autores = $this->regAutoresInt($PcInfo["volumeInfo"]["authors"], 'GB');
                $this->AsociaAutoresADoc($autores, $idDoc);
                $fechas = $this->regFechasInt($PcInfo["volumeInfo"]["publishedDate"]."-01-01", $idDoc);
                $etiquetas = $this->regEtiquetas($PcInfo["volumeInfo"]["categories"], $idDoc, 'GB');
                $atributos = $this->regAtr($PcInfo, $idDoc, $request->cIDWeb, 'GB');
            }

            if($PidTipoDoc["value"] == 16 || $PidTipoDoc["value"] == 14){//Revista o memoria numero completo
                $LcResp = $this->registraDoc($PidTipoDoc["value"], $PidCarpeta, $PcTitulo, $PcPeriodica, $PcResumen);
                return $LcResp;
            }

            return $idDoc; 
        }
        
        
    }

    private function regAtr($PcInfo, $idDoc, $cIDWeb, $tipo){
       
        $publisher = "";
        
        $numero = "";
        $volumen = "";
        $paginas = "";


        if($tipo == 'GB'){
            $item = $PcInfo["volumeInfo"];
            if(array_key_exists('publisher', $item))  $publisher = $item["publisher"];
            
            if(array_key_exists('pageCount', $item))  $paginas = $item["pageCount"];

            if($cIDWeb != "") $this->creaAtr($cIDWeb, $idDoc, 11);
            
            
        }

        if($tipo == 'CR'){
            $item = $PcInfo;
            if(array_key_exists('publisher', $item)) $publisher = $item["publisher"];
            
            if(array_key_exists('page', $item)) $paginas = $item["page"];

            if(array_key_exists('issue', $item)) $numero = $item["issue"];

            if(array_key_exists('volume', $item)) $volumen = $item["volume"];

            if($cIDWeb != "") $this->creaAtr($cIDWeb, $idDoc, 47);

        }

        if($publisher != "") $this->creaAtr($publisher, $idDoc, 8);
        if($paginas != "") $this->creaAtr($paginas, $idDoc, 13);
        if($numero != "") $this->creaAtr($numero, $idDoc, 21);
        if($volumen != "") $this->creaAtr($volumen, $idDoc, 5);
        
           
        
    }

    private function creaAtr($cValor, $idDoc, $idAtr){
        $idUser = Auth::id();
        conAtributos::create([
            'idDocumento' => $idDoc,
            'idAtributo' => $idAtr,
            'cValor' => $cValor,
            'cEstatus' => 'A',
            'idUsrAlta' => $idUser,
            
        ]);

    }

    private function regEtiquetas($etiquetas, $idDoc, $tipo){
        $misEtiquetas = catEtiquetas::get();
        $idUser = Auth::id();
        $idEtiquetas = array();
        for($i=0; $i<count($etiquetas); $i++){
            $idExiste = 0;
            for($Ai=0; $Ai<count($misEtiquetas); $Ai++){
                
                    if($etiquetas[$i] == $misEtiquetas[$Ai]["cEtiqueta"]){
                        array_push($idEtiquetas, $misEtiquetas[$Ai]["idEtiqueta"]);
                        $idExiste = 1;
                        break;
                    }
                
            }

            if($idExiste == 0){
                $new = catEtiquetas::create([
                    'cEtiqueta' => $etiquetas[$i],
                    'idUsrAlta' => $idUser,
                    
                ]);
                array_push($idEtiquetas, $new->idEtiqueta);
            }

        }

        for($Ei=0; $Ei<count($idEtiquetas); $Ei++){
            
            $Rel = conEtiquetas::create([
                'idEtiqueta' => $idEtiquetas[$Ei],
                'idDocumento' => $idDoc,  
                'idUsrAlta' => $idUser,
                
            ]);
            
        }

        return 'OK';

    }

    private function regFechasInt($fecha, $idDoc){
        $idUser = Auth::id();

        conFechas::create([
            'idDoc' => $idDoc,
            'dInicio' => substr($fecha, 0, 10),
            'dFin' => substr($fecha, 0, 10),
            'idUsrAlta' => $idUser,
            
        ]);
    }

    private function regEnlaceInt($PcURL, $idDoc, $tipo){
        $idUser = Auth::id();

        $Enlace = conEnlaces::create([
            'idDocumento' => $idDoc,
            'cTitulo' => "Ficha electrónica",
            'cURL' => $PcURL,
            'idUsrAlta' => $idUser,
            
        ]);
        $this->RegistraCambio('Registro de enlace', $idDoc, "Ficha electrónica");
 
        return $Enlace;
    }

    private function regAutoresInt($autores, $tipo){
        $misAutores = catAutores::get();

        $LcRegistro = "";
        $idAutores = array();
        
        
        if($tipo == 'CR'){
            for($i=0; $i<count($autores); $i++){
                $idExiste = 0;
                for($Ai=0; $Ai<count($misAutores); $Ai++){
                    if(array_key_exists('ORCID', $autores[$i])){
                        
                         

                        if(substr($autores[$i]["ORCID"], -19) == substr($misAutores[$Ai]["cORCID"], -19)){ //El orcid esta registrado
                            array_push($idAutores, $misAutores[$Ai]["idAutor"]);
                            $idExiste = 1;
                            //$LcRegistro = $LcRegistro." Esta el autor ".$autores[$i]["family"].substr($autores[$i]["ORCID"], -19)." por orcid ".$misAutores[$Ai]["cORCID"]."; <br> "; 
                            break;
                        } else {
                            if($autores[$i]["family"] == $misAutores[$Ai]["cApellido"]){ //El apellido es igual
                                if($autores[$i]["given"] == $misAutores[$Ai]["cFirstNombre"]){ //El apellido es igual
                                    array_push($idAutores, $misAutores[$Ai]["idAutor"]);
                                    $idExiste = 1;
                                    //$LcRegistro = $LcRegistro." Esta el autor ".$autores[$i]["family"]." por nombre ".$misAutores[$Ai]["cApellido"]."; <br> "; 
                                    break;
                                }
                            }
                            //$LcRegistro = $LcRegistro.$autores[$i]["family"]." No esta el autor; <br> ";
                           
                            
                        }
                    } else {
                        if($autores[$i]["family"] == $misAutores[$Ai]["cApellido"]){ //El apellido es igual
                            if($autores[$i]["given"] == $misAutores[$Ai]["cFirstNombre"]){ //El apellido es igual
                                array_push($idAutores, $misAutores[$Ai]["idAutor"]);
                                $idExiste = 1;
                                //$LcRegistro = $LcRegistro." Esta el autor ".$autores[$i]["family"]." por nombre ".$misAutores[$Ai]["cApellido"]."; <br> "; 
                                break;
                            }
                        }
                        //$LcRegistro = $LcRegistro.$autores[$i]["family"]." No esta el autor; <br>"; 
                       
                    }
                }

                if($idExiste == 0){
                    $idAutor = $this->RegistraAutorCR($autores[$i], $tipo);
                    array_push($idAutores, $idAutor);
                }

                
                
            }
        } else {
            for($i=0; $i<count($autores); $i++){
                $idExiste = 0;
                for($Ai=0; $Ai<count($misAutores); $Ai++){
                    
                        if($autores[$i] == $misAutores[$Ai]["cNombre"]){ //El apellido es igual
                            
                                array_push($idAutores, $misAutores[$Ai]["idAutor"]);
                                $idExiste = 1;
                                
                                break;
                            
                        }
                        
                       
                    }
                

                if($idExiste == 0){
                    $idAutor = $this->RegistraAutorCR($autores[$i], $tipo);
                    array_push($idAutores, $idAutor);
                }

                
                
            }
        }


        
        return $idAutores;
        

    }

    private function RegistraAutorCR($autor, $tipo){
        $idUser = Auth::id();
        $ORCID = "";

        if($tipo == 'CR'){
            if(array_key_exists('ORCID', $autor)) $ORCID = $autor["ORCID"];
            $cNombre = $autor["given"]." ".$autor["family"];
            $given = $autor["given"];
            $family = $autor["family"];
        } else {
            $cNombre = $autor;
            $given = $autor;
            $family = "";
        }

        $Autor = catAutores::create([
            'cNombre' => $cNombre,
            'cFirstNombre' => $given,
            'cApellido' => $family,
            'cORCID' => $ORCID,   
            'idUsrAlta' => $idUser,
            
        ]);

        $idAutor = $Autor->idAutor;

        return $idAutor;
    }

    private function AsociaAutoresADoc($aAutores, $idDoc){
        $iOrden = 0;

        for($i=0; $i<count($aAutores); $i++){
            $idUser = Auth::id();
            $Rel = conAutores::create([
                'idDoc' => $idDoc,
                'idAutor' => $aAutores[$i],
                'idTipoAutor' => '1',
                'iOrden' => $iOrden++,   
                'idUsrAlta' => $idUser,
                
            ]);
            
        }
    }

    private function registraDoc($idTipoDoc, $idCarpeta, $cTitulo, $idPeriodica, $cResumen){
        
        $idUser = Auth::id();
        $Doc = Documentos::create([
            'idCarpeta' => $idCarpeta,
            'idTipoDoc' => $idTipoDoc,
            'cResumenDoc' => $cResumen,
            'cTituloDoc' => $cTitulo,   
            'idUsrAlta' => $idUser,
            
        ]);
        $idDocumento = $Doc->idDocumento;
        
        if($idTipoDoc == 1 || $idTipoDoc == 15 ){
            //$this->RegistraCambio('Creación', $idDocumento, 'Alta del documento');
            if(gettype($idPeriodica) == 'array'){ //La trae del combo
                $this->asociaConPeriodica($idDocumento, $idPeriodica, 'A', $idTipoDoc);
            }
            if(gettype($idPeriodica) == 'string'){ //La puso el usuario y hay que darla de alta
                
                $this->asociaConPeriodica($idDocumento, $idPeriodica, 'S', $idTipoDoc);
            }
        }
        if($idTipoDoc == 3){//Libro

        }

        if($idTipoDoc == 2){ //Seccion de libro

        }

        if($idTipoDoc == 16){//Revista numero completo

        }
       
        return $idDocumento;
    }

    public function asociaConPeriodica($idDocumento, $idPeriodica, $tipo, $idTipoDoc){
        $idUser = Auth::id();
        if($tipo == 'A'){
            conPublicaciones::create([
                'idDocumento' => $idDocumento,
                'idPublicacion' => $idPeriodica["value"], 
                'idUsrAlta' => $idUser,
                
            ]);
            return;
            
        }
        if($tipo == 'S'){
            $Periodica = $this->creaPublicacion($idPeriodica, 'S', $idTipoDoc);
            
            conPublicaciones::create([
                'idDocumento' => $idDocumento,
                'idPublicacion' => $Periodica, 
                'idUsrAlta' => $idUser,
                
            ]);
            return;
        }
    }

    public function creaPublicacion($idPeriodica, $tipo, $idTipoDoc){
        $idUser = Auth::id();
        if($tipo == 'S'){
            $idTipoPub = 0;
            if($idTipoDoc == 1 || $idTipoDoc == 15 || $idTipoDoc == 14 || $idTipoDoc == 16) $idTipoPub = 1;
            if($idTipoDoc == 4) $idTipoPub = 2; 
            $Periodica = catPublicaciones::create([
                'cPublicacion' => $idPeriodica,
                'idCat_TipoPub' => $idTipoPub, 
                'idUsrAlta' => $idUser,
                
            ]);
            $idPublicacion = $Periodica->idPublicacion;
            return $idPublicacion;
        }
    }

    private function GuardaArchivo($cArchivo, $idDocumento, $idArchivo){
        try {
            //OBTIENE LOS PARAMETROS DE ENTRADA DEL ARCHIVO A SUBIR.
            
                $target_dir = $this->dirBase."/archivos"; 
                
                //$pdftotext_dir = "C:\\xampp\\htdocs\\xpdf\\bin64\\pdftotext.exe";
            
            
            $PidDocumento = $idDocumento;
            $PcArchivo = $cArchivo;
        
            
            $LcArchivo = $PcArchivo["archivo"]["name"];
            $extension = strtolower(substr($LcArchivo, -4));

           
                
                //PROCEDE A SUBIRLO.		
                if(move_uploaded_file($PcArchivo['archivo']['tmp_name'], $target_dir."/".$PidDocumento."-".$idArchivo.$extension)){
                        
                        $LcResp = "OK";
                        
                        //$this->RegistraCambio('Se asoció un archivo', $idDocumento, 'Se asoció el archivo: ' + $LcArchivo);
                        return $LcResp;
                        
                        /*if(strtolower(substr($LcArchivo, -4)) == ".pdf"){
                            
                            if ($GcSystem == "Windows"){
                                $content = shell_exec($pdftotext_dir.' "'.$target_dir.'\\'.$PidDocumento.'-'.$LcArchivo.'" -');
                                $content = utf8_encode($content);  
                            }
                            if ($GcSystem == "Linux"){
                                $content = shell_exec($pdftotext_dir.' "'.$target_dir.'/'.$PidDocumento.'-'.$LcArchivo.'" -');
                                
                            }
                            if ($GcSystem == "LinuxVirtual"){
                                $content = shell_exec($pdftotext_dir.' "'.$target_dir.'/'.$PidDocumento.'-'.$LcArchivo.'" -');
                                
                            }
                            
                            //$valor = ord(substr($content,0,1));
                            //$content = $valor."   ".$content;
                            $content = str_replace(chr(12), "", $content);
                            
                            if($content == ''){
                                //"El PDF no contiene texto legible";
                                $LcResp = "OK1";
                            } else {
                                //$LcDriver = "Driver={MySQL ODBC 5.1 Driver}";
                                //$GcBDD = $LcDriver.";Server=localhost:3306;Database=betoasab_deh;User=root;Password=;Option=3;";
                                $LoObj = new clsDoc;
                                $LoObj->Conexion = $GcBDD;
                                $LcResp = $LoObj->InsertarContenidoPDF($PidDocumento,$content);
                                
                                $LoJSON = new clsJSON;
                                $LoResp = $LoJSON->parse($LcResp);
                                
                                if ($LoResp["resultado"] != "OK") {
                                    //se subio pero no se inserto
                                    $LcResp = "ERROR2";
                                } else {
                                    $LcResp = "OK2";
                                }
                                
                            }                
                            
                        }*/
                        
                } else {			
                    throw new Exception("ERROR");
                }	

          
                
              
                
        } catch(Exception $e){
            $LcResp = "ERROR";
            return $LcResp;
        }
        
       
        
    }

    public function regEnlace(Request $request){
        $PcURL = $request->url;
        $PcNombre = $request->nombre;
        $idDoc = $request->idDoc;
        $idUser = Auth::id();
        $Enlace = conEnlaces::create([
            'idDocumento' => $idDoc,
            'cTitulo' => $PcNombre,
            'cURL' => $PcURL,
            'idUsrAlta' => $idUser,
            
        ]);
        $this->RegistraCambio('Registro de enlace', $idDoc, $PcNombre);
 
        return $Enlace;
    }

    public function temasNoAsociados(Request $request){
        $PidDoc = $request->idDoc;
        
        $LcCad = "select T1.idTema from 
        temas as T1
        left join rel_doctema as T2 on T1.idTema = T2.idTema
        where T2.cEstatus = 'A' and idDocumento = ".$PidDoc;
        
        $temas = DB::select($LcCad);

        if($temas == []){
            $LcCad = "select idTema, cTema, cDescTema, cColor from temas";
            $LcResp = DB::select($LcCad);
            return $LcResp;
        }

        $cTemas = "";
        
        for($i=0; $i < count($temas); $i++){
            $cTemas = $cTemas.$temas[$i]->idTema.", ";
        }
        $cTemas = substr($cTemas, 0, -2);

        $LcCad = "select idTema, cTema, cDescTema, cColor from temas where idTema not in (".$cTemas.")";
        $LcResp = DB::select($LcCad);
        return $LcResp;

      

    }

    public function asociaTemas(Request $request){
        $idUser = Auth::id();
      
        $PcTemas = json_decode($request->temas);
        
        $PidDoc = $request->idDoc;
       

        for($i=0; $i < count($PcTemas); $i++){
            
            
            $newTema = conTemas::create([
                'idDocumento' => $PidDoc,
                'idTema' => $PcTemas[$i]->idTema,
                'idUsrAlta' => $idUser,
                
            ]);
            //$cDesc = 'Se asocio al tema: ' + $PcTemas[$i]->cTema;
            $this->RegistraCambio('Se asoció a un tema', $PidDoc, $PcTemas[$i]->cTema);
            
        }
        return "OK";
    }

    public function tagsNoAsociados(Request $request){
        $idUser = Auth::id();       
        $PidDoc = $request->idDoc;
        $LcCad = "select T1.idEtiqueta from 
        cat_etiquetas as T1
        left join rel_etiquetadoc as T2 on T1.idEtiqueta = T2.idEtiqueta
        where T2.cEstatus = 'A' and idDocumento = ".$PidDoc;
        
        $etiquetas = DB::select($LcCad);
        if($etiquetas == []){
            $LcCad = "select idEtiqueta, cEtiqueta from cat_etiquetas";
            $LcResp = DB::select($LcCad);
            return $LcResp;
        }
        $cTags = "";
        
        for($i=0; $i < count($etiquetas); $i++){
            $cTags = $cTags.$etiquetas[$i]->idEtiqueta.", ";
        }
        $cTags = substr($cTags, 0, -2);

        $LcCad = "select idEtiqueta, cEtiqueta from cat_etiquetas where idEtiqueta not in (".$cTags.")";
        $LcResp = DB::select($LcCad);
        return $LcResp;
    }

    public function traeTags(Request $request){
        $PidDoc = $request->idDoc;
        $tagsDoc = DB::select("select T1.idEtiqueta, T1.cEtiqueta from 
        cat_etiquetas as T1
        left join rel_etiquetadoc as T2 on T1.idEtiqueta = T2.idEtiqueta
        where idDocumento = ".$PidDoc);

        $tags = DB::select("select idEtiqueta, cEtiqueta from cat_etiquetas");

        return response()->json([
            'tags' => $tags,
            'tagsDoc' => $tagsDoc,
        ]);
    }



    public function DocsARelacionar($idCarpeta){
        //$LcCad="select * from documentos where idCarpeta = ".$idCarpeta;

        $LcCad ="select T1.idDocumento, T1.cTituloDoc, ifnull(T3.cImagen, 'docDefault.jpg') as cImagen, T1.cResumenDoc, T2.cTipoDoc, T2.cIcono from 
        documentos as T1
        left join cat_tipodoc as T2 on T1.idTipoDoc = T2.idTipoDoc
        left join (select * from rel_archivosdoc where iMostrar = 1) as T3 on T1.idDocumento = T3.idDocumento
        where T1.idCarpeta = ".$idCarpeta." and T1.cEstatus = 'A'
        order by T1.idDocumento";

        $LcResp = DB::select($LcCad);
        return $LcResp;
    }


    public function deleteDocumento(Request $request){
        Documentos::where('idDocumento',$request->idDoc)->update(['cEstatus'=>'B']);
        $LcResp = DB::select("select idCarpeta from documentos where idDocumento = ".$request->idDoc);
        return $LcResp[0];
    }

    public function editTituloDoc(Request $request){
        Documentos::where('idDocumento',$request->idDoc)->update(['cTituloDoc'=>$request->titulo]);
        return 1;
    }

    public function moveDoc(Request $request){
        Documentos::where('idDocumento',$request->idDoc)->update(['idCarpeta'=>$request->carpeta]);
        $ubicacion = $this->CarpetaDoc($request->idDoc);
        return $ubicacion;
    }






















    public function sacarAutores(){
        $Docs = DB::select("select * from documentos");
        for ($i = 0; $i < count($Docs); $i++) {
            $Autores = DB::select("select * from rel_docautor where idDoc = ".$Docs[$i]->idDocumento); 
            if(count($Autores) == 1){
                $idAutor = $Autores[0]->idAutor;
                $Autor = DB::select("select * from cat_autores where idAutor = ".$idAutor);
                $Autor = $Autor[0]->cNombre;
                $data = Documentos::find($Docs[$i]->idDocumento);
                    $data->cAutores=$Autor;
                $data->save();
                //return $Autor;
            }
            if(count($Autores) == 2){
                $cAutores = "";
                for ($Ai = 0; $Ai < count($Autores); $Ai++){
                    $idAutor = $Autores[$Ai]->idAutor;
                    $Autor = DB::select("select * from cat_autores where idAutor = ".$idAutor);
                    $Autor = $Autor[0]->cNombre;
                    //return $Autor;
                    $cAutores = $cAutores. " & " . $Autor;
                }
                $cAutores = substr($cAutores, 3);
                $data = Documentos::find($Docs[$i]->idDocumento);
                    $data->cAutores=$cAutores;
                $data->save();
               // return $cAutores;
            }
            if(count($Autores) > 2){
                
                $idAutor = $Autores[0]->idAutor;
                $Autor = DB::select("select * from cat_autores where idAutor = ".$idAutor);
                $Autor = $Autor[0]->cNombre;
                $Autor = $Autor. " et al.";
                $data = Documentos::find($Docs[$i]->idDocumento);
                    $data->cAutores=$Autor;
                $data->save();
                //return $Autor;
            }
           
        }
    }

    public function sacarExt(){
        
        $Docs = DB::select("select * from rel_archivosdoc");
        
        for ($i = 0; $i < count($Docs); $i++) {
            $cURL = strtolower($Docs[$i]->cNombre);
            $pos = strrpos($cURL, ".");
            $ext = substr($cURL, $pos+1);

            $extensiones = DB::select("select * from cat_extensiones");
            for($Ei = 0; $Ei < count($extensiones); $Ei++){
                $BDDext = strtolower($extensiones[$Ei]->cExtension);
                if($BDDext == $ext){
                    $idext = $extensiones[$Ei]->idExtension;
                    
                    $data = conArchivos::find($Docs[$i]->idRelArDoc);
                        $data->idExt=$idext;
                    $data->save();
                    break;
                } 
                if($Ei == count($extensiones)-1){
                    $newExtension = catExtensiones::create([
                        'cExtension' => $ext,
                        
                    ]);
                    $newExtension = $newExtension->idExtension;
                    $data = conArchivos::find($Docs[$i]->idRelArDoc);
                        $data->idExt=$newExtension;
                    $data->save();
                }
                
            }

           
            
        }

        
    }
}

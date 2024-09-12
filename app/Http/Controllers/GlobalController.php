<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
date_default_timezone_set("America/Mexico_City");

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Docs\Relaciones\conEnlaces;
use App\Models\Docs\Relaciones\conTemas;
use App\Models\Docs\Relaciones\conEtiquetas;
use App\Models\Docs\Relaciones\conArchivos;
use App\Models\Docs\Relaciones\conPublicaciones;
use App\Models\Docs\Relaciones\conFechas;
use App\Models\Docs\Relaciones\conAutores;
use App\Models\Docs\Relaciones\conDocs;
use App\Models\Docs\Relaciones\conAtributos;
use App\Models\Docs\Notas;
use App\Models\Documentos;


use App\Traits\Globales;

class GlobalController extends Controller
{

    use Globales;


    public function DeleteRel(Request $request){

       
        $tipo = $request->tipo;
        $id = $request->idElemento;
        $cDesc = $request->cDesc;
        $idDoc = $request->idDocumento;

        if($tipo == "autor"){
            $autor = json_decode($request->item);
            conAutores::where("idDoc", "=", $idDoc)
            ->where("idAutor", "=", $id)    
            ->delete();
        }
        
        if($tipo == "enlace"){
            $this->RegistraCambio('Baja de enlace', $idDoc, $cDesc);
            DB::table("rel_enlacesdoc")->where('idRelEnlDoc', "=", $id)->update(['cEstatus'=>'B']);
        }
        if($tipo == "temas"){
            $this->RegistraCambio('Baja de tema', $idDoc, $cDesc);
            DB::table("rel_doctema")->where('idTema', "=", $id)->where('idDocumento', '=', $idDoc)->delete();
        }
        if($tipo == "tags"){
            $this->RegistraCambio('Baja de etiqueta', $idDoc, $cDesc);
            DB::table("rel_etiquetadoc")->where('idEtiqueta', "=", $id)->where('idDocumento', '=', $idDoc)->delete();
        }
        if($tipo == "archivo"){
            $archivo = json_decode($request->archivo);
            
            $infoAr = conArchivos::where("idRelArDoc", "=", $id)->get();
            $idDocMostrar = 0;
            if($infoAr[0]["iMostrar"] == 1){
                $otros = conArchivos::where("idDocumento", "=", $idDoc)->get();
                if(count($otros)>0){
                    DB::table("rel_archivosdoc")->where('idRelArDoc', "=", $otros[0]["idRelArDoc"])->update(['iMostrar'=>'1']);
                    $idDocMostrar = $otros[0]["idRelArDoc"];
                }
            }
            //return $infoAr;
            $this->RegistraCambio('Se envió un archivo a la papelera', $archivo->idDocumento, $archivo->cNombre);
            DB::table("rel_archivosdoc")->where('idRelArDoc', "=", $id)->update(['cEstatus'=>'B']);
            return $idDocMostrar;
        }
    }

    public function AddRel(Request $request){
        $tipo = $request->tipo;
        $idDoc = $request->idDocumento;
        $cDesc = $request->cDesc;
        $idUser = Auth::id();

        if($tipo == "docsrel"){
            $Docs = json_decode($request->items);
            
            for($i=0; $i < count($Docs); $i++){
            
            
                $new = conDocs::create([
                    'idDocumento' => $idDoc,
                    'idDocRelacionado' => $Docs[$i]->idDocumento,
                    'idUsrAlta' => $idUser,
                    
                ]);

                
            }
            $this->RegistraCambio('Relacionar con otro documento', $idDoc, $cDesc);

            return 1;
        }
        
        if($tipo == "enlace"){
            $titulo = $request->titulo;
            $url = $request->url;
            $this->RegistraCambio('Registro de enlace', $idDoc, $cDesc);
            $new = conEnlaces::create([
                'idDocumento' => $idDoc,
                'cTitulo' => $titulo,
                'cURL' => $url,
                'idUsrAlta' => $idUser,
                
            ]);
            return $new;
        }

        if($tipo == "temas"){
            $PcTemas = json_decode($request->temas);
            
            for($i=0; $i < count($PcTemas); $i++){
            
            
                $newTema = conTemas::create([
                    'idDocumento' => $idDoc,
                    'idTema' => $PcTemas[$i]->idTema,
                    'idUsrAlta' => $idUser,
                    
                ]);

                
            }
            $this->RegistraCambio('Asociar con temas', $idDoc, $cDesc);

            return 1;
        }

        if($tipo == "tags"){
            $PcTags = json_decode($request->tags);
            
            for($i=0; $i < count($PcTags); $i++){
            
            
                $new = conEtiquetas::create([
                    'idDocumento' => $idDoc,
                    'idEtiqueta' => $PcTags[$i]->idEtiqueta,
                    'idUsrAlta' => $idUser,
                    
                ]);

                
            }
            $this->RegistraCambio('Asociar con etiquetas', $idDoc, $cDesc);

            return 1;
        }
    }


    public function EditItem(Request $request){
        $tipo = $request->tipo;
        $id = $request->idElemento;
        $idDoc = $request->idDocumento;
        $cDesc = $request->cDesc;
        $idUser = Auth::id();


        if($tipo == "newNota"){
            $new = Notas::create([
                'idDocumento' => $idDoc,
                'cTitulo' => $request->titulo,
                'cNota' => $request->contenido,
                'idUsrAlta' => $idUser
                
            ]);
            $this->RegistraCambio('Creacion de nota', $idDoc, $cDesc);
            return $new->idNota;

        }

        if($tipo == "editNota"){
            
            Notas::where('idNota', "=", $id)
                ->update([
                    'cTitulo' => $request->titulo,
                    'cNota' => $request->contenido,
                ]);
                $this->RegistraCambio('Actualizacion de nota', $idDoc, $cDesc);

        }

        if($tipo == "atributo"){
            
            $atributo = json_decode($request->item);
            if($id == 'null'){
                conAtributos::create([
                    'idDocumento' => $idDoc,
                    'idAtributo' => $atributo->idAtributo,
                    'cValor' => $atributo->value,
                    'idUsrAlta' => $idUser
                    
                ]);
            } else {
                conAtributos::where('idDocAtr', "=", $id)
                ->update([
                    'cValor' => $atributo->value,
                ]);
            }
            $this->RegistraCambio('Actualizacion de atributo', $idDoc, $cDesc);
        }

        if($tipo == "resumen"){
            
            Documentos::where('idDocumento', "=", $id)
            ->update([
                'cResumenDoc' => $request->item,
            ]);
            $this->RegistraCambio('Actualización del resumen', $idDoc, $cDesc);

        }

        if($tipo == "autor"){
           
            $tipoAutor = json_decode($request->item);
            conAutores::where('idAutor', "=", $id)
            ->where("idDoc", "=", $idDoc)
            ->update([
                'idTipoAutor' => $tipoAutor->idTipoAutor,
            ]);
            $this->RegistraCambio('Cambio en el tipo de autor', $idDoc, $cDesc);
        }

        if($tipo == 'autores'){

            $autores = json_decode($request->item);

            if(count($autores) == 0){ //Se envio vacio
                Documentos::where('idDocumento', "=", $idDoc)->update([
                    'cAutores'=>""
                ]);
                return "Vacio";
            }
            
            $enBase = conAutores::where("idDoc", "=", $idDoc)->get();
            
            if($enBase == '[]'){ //No tiene autores asociados
                for($i=0; $i<count($autores); $i++){
                    conAutores::create([
                        'idDoc' => $idDoc,
                        'idAutor' => $autores[$i]->idAutor,
                        'idTipoAutor' => $autores[$i]->idTipoAutor,
                        'iOrden' => $autores[$i]->iOrden
                        
                    ]);
                }
            } else {
                for($i=0; $i<count($autores); $i++){
                    $idRel = conAutores::where("idDoc", "=", $idDoc)
                        ->where("idAutor", "=", $autores[$i]->idAutor)
                        ->get();
                    if($idRel == '[]'){
                        conAutores::create([
                            'idDoc' => $idDoc,
                            'idAutor' => $autores[$i]->idAutor,
                            'idTipoAutor' => $autores[$i]->idTipoAutor,
                            'iOrden' => $autores[$i]->iOrden
                            
                        ]);
                    } else {
                        if($idRel[0]->iOrden != $autores[$i]->iOrden){
                            conAutores::where('idRelDocAutor', "=", $idRel[0]->idRelDocAutor)->update([
                                'iOrden' => $autores[$i]->iOrden,
                            ]);
                        }
                    }   
                }
            }


            $Autores = DB::select("select * from rel_docautor where idDoc = ".$idDoc); 
            if(count($Autores) == 1){
                $idAutor = $Autores[0]->idAutor;
                $Autor = DB::select("select * from cat_autores where idAutor = ".$idAutor);
                $Autor = $Autor[0]->cNombre;
                $data = Documentos::find($idDoc);
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
                $data = Documentos::find($idDoc);
                    $data->cAutores=$cAutores;
                $data->save();
               // return $cAutores;
            }
            if(count($Autores) > 2){
                
                $idAutor = $Autores[0]->idAutor;
                $Autor = DB::select("select * from cat_autores where idAutor = ".$idAutor);
                $Autor = $Autor[0]->cNombre;
                $Autor = $Autor. " et al.";
                $data = Documentos::find($idDoc);
                    $data->cAutores=$Autor;
                $data->save();
                //return $Autor;
            }
            
            
            
        }

        if($tipo == 'fechas'){
            $fechas = json_decode($request->item);
            $existe = conFechas::where('idDoc', "=", $idDoc)->get();
            if($existe == '[]'){
                $new = conFechas::create([
                    'idDoc' => $idDoc,
                    'dInicio' => $fechas->dInicio,
                    'dFin' => $fechas->dFin,
                    'idUsrAlta' => $idUser
                    
                ]);
                $this->RegistraCambio('Registro de fechas', $idDoc, $cDesc);
            } else {
            
            conFechas::where('idDoc', "=", $idDoc)->update([
                'dInicio' => $fechas->dInicio,
                'dFin' => $fechas->dFin,
            ]);
            $this->RegistraCambio('Cambio de fechas', $idDoc, $cDesc);
            }
        }

        if($tipo == "tipodoc"){
            Documentos::where('idDocumento', "=", $idDoc)->update([
                'idTipoDoc'=>$id
            ]);
            if($id == 1 || $id == 4 || $id == 14 || $id == 15 || $id == 16 || $id == 17){
                conPublicaciones::where("idDocumento", "=", $idDoc)->delete();
            }
    
            $this->RegistraCambio('Cambio de tipo de documento', $idDoc, $cDesc);
        }

        if($tipo == "publicacion"){
            $existe = conPublicaciones::where('idDocumento', "=", $idDoc)->get();
            if($existe == '[]'){
                $new = conPublicaciones::create([
                    'idDocumento' => $idDoc,
                    'idPublicacion' => $id,
                    'idUsrAlta' => $idUser,
                    
                ]);
                $this->RegistraCambio('Registro de publicación', $idDoc, $cDesc);
            } else {
            
            conPublicaciones::where('idDocumento', "=", $idDoc)->update([
                'idPublicacion'=>$id
            ]);
            $this->RegistraCambio('Cambio de publicacion', $idDoc, $cDesc);
            }
        }

        
    }
}

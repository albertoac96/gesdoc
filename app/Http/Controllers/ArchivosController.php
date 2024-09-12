<?php

namespace App\Http\Controllers;
use App\Models\Docs\Relaciones\conArchivos;

use Illuminate\Http\Request;

class ArchivosController extends Controller
{
    public function sacaImagenes(){

        
        
        $LcResp = conArchivos::
        where('cEstatus', "=", "A")
        ->orderBy('created_at')
        ->get();

        $i = 0;

        foreach($LcResp as $archivo){

            $cNombreArchivo = $archivo->cNombre;
            if(strpos($cNombreArchivo, " ")){
                $idDoc = $archivo->idDocumento;
                $idArchivo = $archivo->idRelArDoc;
                $dirArchivo = "/media/sf_F_DRIVE/base/archivos"; 
                $dirImg = "/var/www/haydee/public/images/archivos"; 
                
                $cURL = strtolower($cNombreArchivo);
                $pos = strrpos($cURL, ".");
                $ext = substr($cURL, $pos+1);
    
                
        
                if($ext == "pdf"){
                    shell_exec("convert -colorspace sRGB '".$dirArchivo."/".$idDoc."-".$cNombreArchivo."'[0] -quality 50 ".$dirImg."/".$idArchivo.".webp");
                    conArchivos::where('idRelArDoc', $idArchivo)->update(['cImagen'=>$idArchivo.".webp"]);
                }
        
                if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "tiff" || $ext == "tif" || $ext == "gif"){
                    shell_exec("convert -colorspace sRGB '".$dirArchivo."/".$idDoc."-".$cNombreArchivo."' -quality 50 ".$dirImg."/".$idArchivo.".webp");
                    conArchivos::where('idRelArDoc', $idArchivo)->update(['cImagen'=>$idArchivo.".webp"]);
                }
    
                echo("Hecho <br>");
            }

            
           
        }

        echo "Finalizado";


    }
}

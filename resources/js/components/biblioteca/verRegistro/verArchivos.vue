<template>
    <div>
    <h5 class="mt-4 md-4">Archivos adjuntos

      <v-btn v-if="$canBtn('agregar', 'No tiene permisos para agregar')" icon @click.stop="dlgSubirArchivo = true"><v-icon>mdi-plus</v-icon></v-btn>
       
    </h5>

    
    <v-row>
     <v-tooltip v-for="archivo in archivos" :key="archivo.idRelArDoc" bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-chip
           
            class="ma-2"
            close
            outlined
            @click:close="Delete(archivo)"
            @dblclick="fnVerArchivo(archivo)"
            @click="fnNewMin(archivo)"
            v-bind="attrs"
            v-on="on"
          >
            {{ QuitarMedio(archivo.cNombre, 30) }}
          </v-chip>
        </template>

        <span>{{ archivo.cNombre }}</span>
      </v-tooltip>


       
      
    </v-row>

      <v-dialog
        v-model="dlgVerDoc"
        fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      >
      <v-system-bar window dark v-if="noPDF">
        <v-spacer></v-spacer>
        <v-btn icon dark @click="dlgVerDoc=false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-system-bar>
        <v-card>
          
          <verAdjunto v-if="noPDF" :archivo="archivo"></verAdjunto>
          <verPDF2 v-else :pdf="archivo" @close="dlgVerDoc = false"></verPDF2>

         
        </v-card>
      </v-dialog>

      <subirArchivo @info="ArchivoAñadido($event)" v-if="dlgSubirArchivo" tipo="adjuntoADoc" :infoDoc="documento" @close="dlgSubirArchivo = false" ></subirArchivo>
      
    

  </div>
</template>
<script>
import verPDF2 from '../../dialogos/verPDF2.vue';
import subirArchivo from '../../dialogos/subirArchivo.vue'
import verAdjunto from './verAdjunto.vue'
export default {
    name: "verArchivos",
    props: ["archivos", "documento"],
    components:{
        verPDF2,
        subirArchivo,
        verAdjunto
    },
    data: () => ({
        dlgVerDoc: false,
        archivo: [],
        dlgSubirArchivo: false,
        dlgCrearEnlace: false,
        noPDF: true,
    }),
    mounted(){

    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{

    },
    computed:{

    },
    methods:{
      fnVerArchivo(archivo){
        //console.log(archivo.idExt);
        
      this.noPDF == true;
        if(archivo.cExtension == 'pdf'){ //PDF
          this.noPDF = false;
           this.archivo = archivo;
        this.dlgVerDoc=true;
          return;
        } else if(archivo.cExtension == 'mp4'){//mp4
        return;

        }else if(archivo.cExtension == 'mp3'){//mp3
        return;

        } else if(archivo.idExt >= 2 || archivo.idExt <= 7 || archivo.idExt >= 10 || archivo.idExt <= 15){
          this.archivo = archivo;
        this.dlgVerDoc=true;
        } else {
           //console.log("El archivo no se puede mostrar en el navegador, debe descargarlo");
        }

         
        
        

      },
      ArchivoAñadido(info){
        //console.log(info);
        this.archivos.push(info.info);
      },
      fnNewMin(archivo){
        this.$emit('newMin', archivo);
      },
      Delete(item){
       if(this.$canSnack('papelera', 'No tienes permisos para eliminar este elemento')){

     
          let InstFormData = new FormData();
          InstFormData.append('idElemento' , item.idRelArDoc);
          InstFormData.append('cDesc' , item.cNombre);
          InstFormData.append('idDocumento' , item.idDocumento);
          InstFormData.append('tipo' , 'archivo');
          InstFormData.append('archivo' , JSON.stringify(item));
            axios
            .post("/deleterel", InstFormData)
            .then((res) => {
              if(res.data > 0){
                var pos = this.archivos.map(function(e) { return e.idRelArDoc; }).indexOf(res.data);
                this.archivos[pos].iMostrar = 1;
              }
              var pos = this.archivos.map(function(e) { return e.idRelArDoc; }).indexOf(item.idRelArDoc);
              this.archivos.splice(pos, 1);
              this.$store.state.snack.text = "Se envió el archivo a la papelera";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
            })
            .catch((error) => {});
           
         }

      }
      
    }
}
</script>
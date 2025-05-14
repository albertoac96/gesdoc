<template>

    <div class="text-center">
       
    <v-dialog
      v-model="dlgSubirArchivo"
      width="500"
      persistent
    >
     

      <v-card>
       

        <v-card-title class="text-h5 grey lighten-2">
           
          Subir archivo
       </v-card-title>

        
        <v-card-text>

          <v-card-subtitle>
          {{msgAlert}} 
        </v-card-subtitle>

            <v-file-input
            v-model="file"
    show-size
    counter
    prepend-icon="mdi-archive"
    label="File input"
  ></v-file-input>

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="red"
            text
            @click="$emit('close')"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnSubeArchivo()"
          >
            Subir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

       <v-snackbar centered v-model="snackbar"  :timeout="3000" :color="snackColor">
      {{ textSnack }} 

      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar = false">
          CERRAR
        </v-btn>
      </template>
    </v-snackbar>


  </div>


</template>
<script>


export default {
    name: "subirArchivo",
    props: ["tipo", "infoDoc"],
    components:{

    },
    data: () => ({
        dlgSubirArchivo: true,
        nombre: "",
        tamaño: "",
        file: null,
        uploadPercentage: 0,
        snackbar:false,
        snackColor: "",
        textSnack: "",
        infoArchivo: {
          idCarpeta: 0,
          tipo: "solo"
        },
      msgAlert: ""
        
    }),
    mounted(){

      if(this.tipo == "soloACarpeta"){
         var URLactual = window.location;
        var ruta = URLactual.pathname;
        var posSlash = ruta.lastIndexOf("/");
        var nameRuta = ruta.slice(1, posSlash);
        if(nameRuta != "biblio"){
          this.msgAlert = "El archivo se subirá en la carpeta base 'Mi biblioteca'. Si quiere agregarlo en otra ubicación vaya a la carpeta en el panel de carpetas del lado izquierdo";
          this.infoArchivo.idCarpeta = 0;
        } else {
          
          this.infoArchivo.idCarpeta = ruta.slice(posSlash+1);
          axios
          .get("/nombrede/" + this.infoArchivo.idCarpeta)
          .then((res) => {
             this.msgAlert = "Subir a la carpeta " + res.data;
        })
        .catch((error) => {
        });
          
        }
        return;
      }

      if(this.tipo == "adjuntoADoc"){
        //console.log(this.infoDoc);
        this.infoArchivo.idCarpeta = this.infoDoc.idDoc;
        this.msgAlert = "Adjuntar un archivo al documento " + this.infoDoc.Documento.cTituloDoc;
      }

     

      
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
      fnSubeArchivo(){

        


       

        if(this.$store.state.UploadProgress.infoArchivos.length > 3){
          this.snackbar = true,
          this.snackColor = "error",
          this.textSnack = "Solo puede subir 3 archivos al mismo tiempo. Espere a que termine de subir los archivos pendientes"
          return;
        }


        var gArchivos = this.$store.state.UploadProgress.infoArchivos;
        let InstFormData = new FormData();
        InstFormData.append('archivo' , this.file);
        InstFormData.append('id' , this.infoArchivo.idCarpeta);
        InstFormData.append('tipo' , this.tipo);
        gArchivos.push({"name": this.file.name, "progress": "0"});
        var pArchivo = gArchivos.findIndex(x => x.name === this.file.name);

        
       
        

        axios.post( '/upload',
            InstFormData,
            {
              headers: {
                  'Content-Type': 'multipart/form-data',
                  
              },
             onUploadProgress: function( progressEvent ) {
               
                gArchivos[pArchivo].progress = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                
              }.bind(this)
            }
          ).then((res) => {
           ////console.log("OK")
           this.$store.state.UploadProgress.infoArchivos.splice(pArchivo);
           this.$emit('info', res.data.info);
           
          })
          .catch((res) => {
            ////console.log('FAILURE!!');
          });


        

      
      },
       

    }
}
</script>
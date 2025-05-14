<template>
     <v-dialog
      v-model="dlg"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          
         <span v-if="tipo == 'new'">Nueva nota</span>
         <span v-else>Editar nota</span>
           
           
        </v-card-title>

        <v-card-text>
           <v-text-field v-model="notae.cTitulo" label="Título de la nota"></v-text-field>
           <v-textarea v-model="notae.cContenido" label="Contenido de nota"></v-textarea>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="error"
            text
            @click="$emit('close')"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnGuardar()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "dlgEditNota",
    props: ["nota", "tipo", "idDoc"],
    components:{

    },
    data: () => ({
        notae:{
            cTitulo: "",
            cContenido: "",
        },
        dlg: true,
    }),
    mounted(){
        if(this.tipo == 'edit'){
            this.notae.cTitulo = this.nota.cTitulo;
            this.notae.cContenido = this.brtolf(this.nota.cNota);
        }
        //console.log(this.nota);
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
        fnGuardar(){

          var NotaOK = this.lftobr(this.notae.cContenido);
          var idNota = 0;
         

          if(this.tipo == "new"){
            if(NotaOK == "" || this.notae.cTitulo == ""){
              this.$store.state.snack.text = "Agregue un título y contenido a la nota";
                this.$store.state.snack.color = "info";
                this.$store.state.snack.centered = true;
                this.$store.state.snack.model = "true";
                return;
            }

            let InstFormData = new FormData();
            InstFormData.append('idElemento' , this.idDoc);
            InstFormData.append('cDesc' , "Titulada " + this.notae.cTitulo);
            InstFormData.append('idDocumento' , this.idDoc);
            InstFormData.append('tipo' , 'newNota');
            InstFormData.append('titulo' , this.notae.cTitulo);
            InstFormData.append('contenido' , NotaOK);
              axios
              .post("/edit", InstFormData)
              .then((res) => {
                this.$store.state.snack.text = "La nota fue agregada";
                this.$store.state.snack.color = "success";
                this.$store.state.snack.centered = false;
                this.$store.state.snack.model = "true";
                idNota = res.data;

                var NotaFin = {
                    "idNota": idNota,
                    "cTitulo": this.notae.cTitulo,
                    "cNota": NotaOK,
                    "created_at": this.Hoy(),
                    "name": this.$store.state.dataUser.info.user,
                    "cAvatar": this.$store.state.dataUser.info.avatar
                }
                this.$emit('itemEdit', NotaFin);
                
              })
              .catch((error) => {});

          } else {

             if(this.nota.cTitulo == this.notae.cTitulo && this.nota.cNota == NotaOK){
               this.$store.state.snack.text = "Edite la nota para guardar los cambios";
                this.$store.state.snack.color = "info";
                this.$store.state.snack.centered = true;
                this.$store.state.snack.model = "true";
              return;
             }

               let InstFormData = new FormData();
            InstFormData.append('idElemento' , this.nota.idNota);
            InstFormData.append('cDesc' , "Titulada " + this.notae.cTitulo);
            InstFormData.append('idDocumento' , this.idDoc);
            InstFormData.append('tipo' , 'editNota');
            InstFormData.append('titulo' , this.notae.cTitulo);
            InstFormData.append('contenido' , NotaOK);
              axios
              .post("/edit", InstFormData)
              .then((res) => {
                idNota = this.nota.idNota;
                this.$store.state.snack.text = "La nota fue actualizada";
                this.$store.state.snack.color = "success";
                this.$store.state.snack.centered = false;
                this.$store.state.snack.model = "true";

                var NotaFin = {
                    "idNota": idNota,
                    "cTitulo": this.notae.cTitulo,
                    "cNota": NotaOK,
                    "created_at": this.Hoy(),
                    "name": this.$store.state.dataUser.info.user,
                    "cAvatar": this.$store.state.dataUser.info.avatar
                }
                this.$emit('itemEdit', NotaFin);
                
                
              })
              .catch((error) => {});


          }
          
      
                
                
            


        }
    }
}
</script>
<template>
     <v-dialog
      v-model="dlg"
      width="600px"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Editar resumen
           
           
        </v-card-title>

        <v-card-text>

           

            <v-textarea
            v-model="cResumen"
          name="input-7-1"
          label="Default style"
        ></v-textarea>
            
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
    name: "dlgEditResumen",
    props: ["resumen", "idDoc"],
    components:{

    },
    data: () => ({
        dlg: true,
        cResumen: "",

    }),
    mounted(){
        this.cResumen = this.brtolf(this.resumen);
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

        if(this.VerificaPermiso('editar') == true){

           var resumenOK = this.lftobr(this.cResumen);
         ////console.log(resumenOK);
         if(resumenOK == this.resumen){
           this.$store.state.snack.text = "Realice un cambio al resumen y guarde";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
          this.$store.state.snack.centered = true;
           return;
         }

          let InstFormData = new FormData();
          InstFormData.append('idElemento' , this.idDoc);
          InstFormData.append('cDesc' , "Editó resumen");
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'resumen');
          InstFormData.append('item' , resumenOK);
            axios
            .post("/edit", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "El resumen fue actualizado";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
               this.$emit('itemEdit', resumenOK);
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para editar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }


        
        
            
        }
    }
}
</script>
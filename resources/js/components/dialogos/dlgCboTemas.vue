<template>
    <v-dialog
      v-model="cboTemas"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Agrega un tema al documento
          
        </v-card-title>

        <v-card-text>
            <v-col cols="12">
                <v-combobox
                v-model="temaSelect"
                :items="temas"
                
                label="Elige los temas a asociar"
                multiple
                outlined
                dense
                item-value="idTema"
                item-text="cTema"
                ></v-combobox>
            </v-col>
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
            @click="fnAsociaTemas()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>

export default {
    name: "dlgCboTemas",
    props: ["titulo", "idDoc"],
    components:{
        
    },
    data: () => ({
        cboTemas: true,
        dlgNuevoTema: false,
        temas: [],
        temaSelect: [],
    }),
    mounted(){
        let Data = new FormData();
        Data.append('idDoc' , this.idDoc);
        axios
          .post("/traetemasasocia", Data)
          .then((res) => {
              this.temas = res.data
        })
        .catch((error) => {
        });
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
        fnAsociaTemas(){

          
          if(this.VerificaPermiso('agregar') == true){
            let InstFormData = new FormData();

            var cDesc = "";
            for(var i=0; i<this.temaSelect.length; i++){
              cDesc = cDesc + "; " + this.temaSelect[i].cTema;
            }
            cDesc.substr(-2);

            InstFormData.append('cDesc' , "Con: " + cDesc);
            InstFormData.append('idDocumento' , this.idDoc);
            InstFormData.append('tipo' , 'temas');
            InstFormData.append('temas' , JSON.stringify(this.temaSelect));
              axios
              .post("/addrel", InstFormData)
              .then((res) => {
                this.$emit('nTemas', this.temaSelect);
              })
              .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para agregar elementos";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }

        
            
        }
    }
}
</script>
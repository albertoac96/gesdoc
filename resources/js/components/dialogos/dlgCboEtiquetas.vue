<template>
    <v-dialog
      v-model="cboTags"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Asocia etiquetas al documento
           <v-btn icon @click.stop="AddTag()"><v-icon>mdi-plus</v-icon></v-btn>
           
           <v-dialog
      v-model="dlgNuevaEtiqueta"
      width="500"
      persistent
    >
     

      <v-card>
       

        <v-card-title class="text-h5 grey lighten-2">
           
          Crear etiqueta
       </v-card-title>

       

        
        <v-card-text>

          

          <v-text-field
       v-model="newTagNombre"
      label="Nombre"
      
     
    ></v-text-field>

    

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="red"
            text
            @click="dlgNuevaEtiqueta=false"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnRegTag()"
          >
            Agregar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


        </v-card-title>

        <v-card-text>
            <v-col cols="12">
                <v-combobox
                v-model="tagSelect"
                :items="tags"
                
                label="Elige las etiquetas a asociar"
                multiple
                outlined
                dense
                item-value="idEtiqueta"
                item-text="cEtiqueta"
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
            @click="fnAsocia()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
import NuevaEtiqueta from './dlgNuevaEtiqueta.vue'
export default {
    name: "dlgCboEtiquetas",
    props: ["titulo", "idDoc"],
    components:{
        NuevaEtiqueta
    },
    data: () => ({
        cboTags: true,
        dlgNuevaEtiqueta: false,
        tags: [],
        tagSelect: [],
        newTagNombre: ""
    }),
    mounted(){
         let Data = new FormData();
        Data.append('idDoc' , this.idDoc);
        axios
          .post("/traetagsnoasocia", Data)
          .then((res) => {
              this.tags = res.data
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
        fnAsocia(){

         

           if(this.VerificaPermiso('agregar') == true){
            let InstFormData = new FormData();

            var cDesc = "";
            for(var i=0; i<this.tagSelect.length; i++){
              cDesc = cDesc + this.tagSelect[i].cEtiqueta + "; ";
            }
            cDesc.substr(-2);

            InstFormData.append('cDesc' , "Con: " + cDesc);
            InstFormData.append('idDocumento' , this.idDoc);
            InstFormData.append('tipo' , 'tags');
            InstFormData.append('tags' , JSON.stringify(this.tagSelect));
              axios
              .post("/addrel", InstFormData)
              .then((res) => {
                this.$emit('nTags', this.tagSelect);
              })
              .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para agregar elementos";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }
           
           
           
            
        },
        fnRegTag(){
          

          if(this.VerificaPermiso('agregar') == true){

            if(this.newTagNombre == ""){
            this.$store.state.snack.text = "Proporciona un nombre de la etiqueta";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.centered = true;
          this.$store.state.snack.model = "true";
          }

          let data = new FormData();
          data.append('name' , this.newTagNombre);
          
            axios
            .post("/regtag", data)
            .then((res) => {
             this.tags.push(res.data);
             this.dlgNuevaEtiqueta = false
              this.$store.state.snack.text = "Se guardo la etiqueta";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para agregar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
          this.$store.state.snack.centered = false;
        }

        },
        AddTag(){
          this.newTagNombre = "";
          this.dlgNuevaEtiqueta = true
        }
    }
}
</script>
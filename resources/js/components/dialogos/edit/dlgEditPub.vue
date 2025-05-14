<template>
    <v-dialog
      v-model="dlg"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Editar publicación
           
           
        </v-card-title>

        <v-card-text>
            <v-col cols="12">
                <v-combobox
                v-model="publicacion"
                :items="items"
                label="Publicación"
                outlined
                dense
                >
             
                </v-combobox>
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
    name: "dlgEditPub",
    props: ["item", "idDoc"],
    components:{

    },
    data: () => ({
        items: [],
        publicacion: [],
        dlg: true,
    }),
    mounted(){
        
        this.publicacion = {"value":this.item.idPublicacion, "text":this.item.cPublicacion};
        axios
          .get("/perindex/" + this.item.idTipoDoc)
          .then((res) => {
              this.items = res.data;

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
        fnGuardar(){


if(this.VerificaPermiso('editar') == true){


          //console.log(this.publicacion);
          //console.log(typeof this.publicacion);
          

          if(typeof this.publicacion == "string"){
             this.$store.state.snack.text = "Eliga una publicación";
                this.$store.state.snack.color = "info";
                this.$store.state.snack.centered = true;
                this.$store.state.snack.model = "true";
              return;
          }

           if(this.item.idPublicacion == this.publicacion.value){
              this.$store.state.snack.text = "Eliga una publicación diferente";
                this.$store.state.snack.color = "info";
                this.$store.state.snack.centered = true;
                this.$store.state.snack.model = "true";
              return;
            }


            

         

          let InstFormData = new FormData();
          InstFormData.append('idElemento' , this.publicacion.value);
          InstFormData.append('cDesc' , "De " + this.item.cPublicacion + " a " + this.publicacion.text);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'publicacion');
          InstFormData.append('item' , JSON.stringify(this.publicacion));
            axios
            .post("/edit", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "Se editó la publicación";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
              this.$emit('itemEdit', this.publicacion);
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
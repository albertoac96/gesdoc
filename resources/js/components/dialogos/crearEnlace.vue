<template>
     <div>
       
    <v-dialog
      v-model="dlgCrearEnlace"
      width="600"
      persistent
    >
     

      <v-card>
       

        <v-card-title class="text-h5 grey lighten-2">
           
          Crear enlace 
       </v-card-title>

       

        
        <v-card-text>

          <v-card-subtitle>
          Crear un enlace para el documento {{titulo}}
        </v-card-subtitle>

          <v-text-field
       v-model="enlace.nombre"
      label="Titulo"
      
     
    ></v-text-field>

    <v-text-field
    v-model="enlace.url"
      label="URL"
     
    ></v-text-field>

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
            @click="fnRegistraEnlace()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

       


  </div>
</template>
<script>
export default {
    name: "crearEnlace",
    props: ["titulo", "idDoc"],
    components:{

    },
    data: () => ({
        dlgCrearEnlace: true,
        enlace:{
            nombre: "",
            url: "",
            idDoc: 0
        },
        
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
       
        fnRegistraEnlace(){
          this.enlace.idDoc = this.idDoc;

          if(this.VerificaPermiso('agregar') == true){
          let InstFormData = new FormData();
          InstFormData.append('cDesc' , this.enlace.nombre + ": " + this.enlace.url);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'enlace');
          InstFormData.append('titulo' , this.enlace.nombre);
          InstFormData.append('url' , this.enlace.url);
            axios
            .post("/addrel", InstFormData)
            .then((res) => {
              this.$emit('info', res.data);
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para eliminar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }


        }
           
        
    }
}
</script>
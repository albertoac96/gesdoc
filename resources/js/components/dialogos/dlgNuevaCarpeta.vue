<template>
    <v-dialog
      v-model="dlg"
      width="500"
      persistent
    >
     

      <v-card>
       

        <v-card-title class="text-h5 grey lighten-2">
           
          Crear carpeta
       </v-card-title>

        
        <v-card-text>

          <v-card-subtitle>
          {{msgAlert}} 
        </v-card-subtitle>

            <v-text-field v-model="item.cNombre" label="Nombre de la carpeta"></v-text-field>
             <v-textarea v-model="item.cDesc" label="Descripción de la carpeta"></v-textarea>

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
            @click="fnGuardar()"
          >
            Crear
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "dlgNuevaCarpeta",
    props: [],
    components:{

    },
    data: () => ({
        dlg: true,
        msgAlert: "",
        item:{
            cNombre: "",
            cDesc: "",
            idPadre: 0
        }

    }),
    mounted(){
        var URLactual = window.location;
        var ruta = URLactual.pathname;
        var posSlash = ruta.lastIndexOf("/");
        var nameRuta = ruta.slice(1, posSlash);
        if (nameRuta != "biblio") {
        this.msgAlert =
            "La carpeta se creará en la carpeta base 'Mi biblioteca'. Si quiere crearla en otra ubicación vaya a la carpeta destino en el panel de carpetas del lado izquierdo";
        this.item.idPadre = 0;
        } else {
        this.item.idPadre = ruta.slice(posSlash + 1);
        axios
          .get("/nombrede/" + this.item.idPadre)
          .then((res) => {
              this.msgAlert = "Crear carpeta en: " + res.data;
        })
        .catch((error) => {
        });
        
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
        fnGuardar(){
            axios
              .post("/regcarpeta", this.item)
              .then((res) => {
                  var node = { idCarpeta: res.data };
                                    var ruta = { name: "biblioteca", params: node };
                    this.$router.push(ruta);
                    this.$store.state.iNewCarpeta++;
                     this.$emit('close');
            })
            .catch((error) => {
            });
        }
    }
}
</script>
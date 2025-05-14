<template>
     <div>
    <h5 class="mt-4 md-4">Enlaces adjuntos
      <v-btn v-if="$canBtn('editar', 'No tiene permisos para agregar')" icon @click.stop="dlgCrearEnlace = true"><v-icon>mdi-plus</v-icon></v-btn>
    </h5>
    
    <v-row>
     
         


          <v-tooltip v-for="item in enlaces" :key="item.idRelEnlDoc" bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-chip
            class="ma-2"
            close
            
            @dblclick.stop="abreEnlace(item.cURL)"
            outlined
            @click:close="fnEliminar(item)"
            v-bind="attrs"
            v-on="on"
          >
            {{ item.cTitulo }}
          </v-chip>
        </template>

        <span>{{ item.cURL }}</span>
      </v-tooltip>
      
    </v-row>

    <crearEnlace @info="EnlaceNuevo($event)" v-if="dlgCrearEnlace" :titulo="titulo" :idDoc="idDoc" @close="dlgCrearEnlace = false"></crearEnlace>
  </div>
</template>
<script>
import crearEnlace from '../../dialogos/crearEnlace.vue'
export default {
    name: "verEnlaces",
    props: ["enlaces", "titulo", "idDoc"],
    components:{
      crearEnlace
    },
    data: () => ({
        dlgCrearEnlace: false,
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
      EnlaceNuevo(info){
        this.enlaces.push(info);
        this.dlgCrearEnlace = false;
        this.$store.state.snack.text = "Se ha creado el enlace";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
      },
      abreEnlace(url){
         
        var win = window.open(url, '_blank');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
      },
      fnEliminar(item){
         if(this.$canSnack('eliminar', 'No tienes permisos para eliminar')){
          let InstFormData = new FormData();
          InstFormData.append('idElemento' , item.idRelEnlDoc);
          InstFormData.append('cDesc' , item.cTitulo + ": " + item.cURL);
          InstFormData.append('idDocumento' , item.idDocumento);
          InstFormData.append('tipo' , 'enlace');
            axios
            .post("/deleterel", InstFormData)
            .then((res) => {
              //console.log(res.data);
              var pos = this.enlaces.map(function(e) { return e.idRelEnlDoc; }).indexOf(item.idRelEnlDoc);
              this.enlaces.splice(pos, 1);
              this.$store.state.snack.text = "Se elimino la relación con el elemento";
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
<template>
  <div>
    <h5 class="mt-4 md-4">Temas
       <v-btn icon @click.stop="Asociar()"><v-icon>mdi-plus</v-icon></v-btn>
       <AsociaTemas @nTemas="pushNewTemas($event)" v-if="dlgAsociaTemas" :titulo="titulo" :idDoc="idDoc" @close="dlgAsociaTemas=false"></AsociaTemas>
    </h5>
    <v-row>
      <v-tooltip v-for="tema in temas" :key="tema.idTema" :color="tema.cColor" bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-chip
            class="ma-2"
            close
            :color="tema.cColor"
            text-color="black"
            outlined
            @click:close="DeleteTema(tema)"
            v-bind="attrs"
            v-on="on"
          >
            {{ QuitarMedio(tema.cTema, 30) }}
          </v-chip>
        </template>

        <span>{{ tema.cDescTema }}</span>
      </v-tooltip>
    </v-row>
  </div>
</template>
<script>
import AsociaTemas from '../../dialogos/dlgCboTemas.vue'
export default {
  name: "verTemas",
  props: ["temas", "titulo", "idDoc"],
  components: {
    AsociaTemas
  },
  data: () => ({
    dlgAsociaTemas: false
  }),
  mounted() {},
  created() {},
  beforeMount() {},
  watch: {},
  computed: {},
  methods: {
    pushNewTemas(items){
      this.$store.state.snack.text = "Asociación completa";
      this.$store.state.snack.color = "success";
      this.$store.state.snack.centered = false;
      this.$store.state.snack.model = "true";  
      
      for(var i = 0; i < items.length; i++){
        
        this.temas.push(items[i]);
        //console.log(items[i]);
      }
      this.dlgAsociaTemas = false;
    },
    Asociar(){
      
      if(this.$canSnack('agregar', 'No tienes permisos para eliminar')){
        
        this.dlgAsociaTemas = true;
         } else {
          this.$store.state.snack.text = "No tienes permisos para agregar elementos";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }
    },
    DeleteTema(item){
        if(this.$canSnack('eliminar', 'No tienes permisos para eliminar')){
          let InstFormData = new FormData();
          InstFormData.append('idElemento' , item.idTema);
          InstFormData.append('cDesc' , item.cTema);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'temas');
            axios
            .post("/deleterel", InstFormData)
            .then((res) => {
             
              var pos = this.temas.map(function(e) { return e.idTema; }).indexOf(item.idTema);
              this.temas.splice(pos, 1);
              this.$store.state.snack.text = "Se elimino la relación con el tema";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
            })
            .catch((error) => {});
           
        }
    }
  },
};
</script>
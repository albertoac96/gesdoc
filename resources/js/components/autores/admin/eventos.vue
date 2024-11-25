<template>
    <v-card>

      <v-card-text>

      <v-btn @click="addEvento('E21', $route.params.id)">Agregar evento {{$route.params.id}}</v-btn>
      <add-evento v-if="dialogEvent" :clave="editedItem.cve" :id="editedItem.idCve" :item="0" :dlg="dialogEvent" @cerrar="cerrarDialog()" tipo="nuevo" :idPadre="0"></add-evento>
   
        <v-list two-line>
      <v-list-item-group
        v-model="selected"
        active-class="pink--text"
        multiple
      >
        <template v-for="(item, index) in items">
          <v-list-item :key="item.idEvento" @click="clickEvento(item)">
            <template v-slot:default="{ active }">
              <v-list-item-content>
                <v-list-item-title v-text="item.cEvento"></v-list-item-title>

                <v-list-item-subtitle
                  class="text--primary"
                  v-text="item.cEnlace"
                ></v-list-item-subtitle>

                <v-list-item-subtitle v-text="item.dFechaInicio"></v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-action>
                

                <v-icon
                  v-if="!active"
                  color="grey lighten-1"
                >
                  mdi-star-outline
                </v-icon>

                <v-icon
                  v-else
                  color="yellow darken-3"
                >
                  mdi-star
                </v-icon>
              </v-list-item-action>
            </template>
          </v-list-item>

          <v-divider
            v-if="index < items.length - 1"
            :key="index"
          ></v-divider>
        </template>
      </v-list-item-group>
    </v-list>

  </v-card-text>



  


</v-card>

</template>
<script>
import addEvento from './addEvento.vue';
export default {
    name: "eventos",
    props: [],
    components:{
      addEvento
    },
    data: () => ({
        selected: [],
        dialogEvent: false,
        editedIndex: -1,
          delItem: 0,
          tiposEventos: [],
          tiposRel: [],
          editedItem: {
            tipo: [],
            dInicio: null,
            dFin: null,
            relacion: '',
            nombre: '',
            enlace: '',
            desc: '',
            cDestino: ''
          },
          defaultItem: {
            tipo: [],
            dInicio: null,
            dFin: null,
            relacion: '',
            nombre: '',
            enlace: '',
           desc: '',
            cDestino: ''
          },
          menu1: false,
          menu2: false,
      items: [
       
      ],
      searchQuery: '',  // Entrada del usuario
      filteredOptions: [],  // Opciones filtradas,


    }),
    mounted(){
        this.initialize();
    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{
     
        dialogEvent(){
          if(this.dialogEvent == false){
            this.editedItem = this.defaultItem;
          }
        }
    },
    computed:{
    
    },
    methods:{
        initialize(){
          axios
          .get("/eventos/autorunico/" + this.$route.params.id)
          .then((res) => {
            this.items = res.data;
            
          })
          .catch((error) => {
          });
        },
        clickEvento(item){
        console.log(item);
          if(item.active){
            item.active = false;
            this.$store.state.red.item = null;
          } else {
            item.active = true;
            this.$store.state.red.item = item;
          }
          var idPadre = this.$route.params.id;
          if(item.idRelPadre == this.$route.params.id){
            idPadre = 0;
          }
         // console.log(item);
          var nodes = [];
          var links = [];
              nodes.push({
                  id: item.idEvento,
                  name: item.cEvento,
                  type: item.cEnlace,
                  cidoc: item.cDestino,
                  date: item.dFechaInicio + "-" + item.dFechaFin
              });

              links.push({
                source: idPadre,  // Suponiendo que el 'source' es un valor constante como en el código PHP
                target: item.idEvento,
                name: item.cEnlace
              });

              this.$emit('resetGrafo', nodes, links, item.active);
            //console.log(this.$store.state.red.nodes);
            //console.log(this.$store.state.red.links);
        },
      
    addEvento(cve, id){
      this.editedItem.cve = cve;
      this.editedItem.idCve = id;
      this.dialogEvent = true;
    },
    cerrarDialog(){
      this.dialogEvent=false;
      this.initialize();
    }
    }
}


</script>
<style>
.v-data-table__selected{
    background-color: orange;
}
</style>
<style scoped>
#timeline {
    width: 100%;
    height: 600px; /* Ajusta la altura según lo necesites */
}
</style>
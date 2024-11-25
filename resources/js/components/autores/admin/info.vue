<template>
  <v-card>
    <v-card-text v-if="$store.state.red != null">
  <label>Nombre del evento: {{ $store.state.red.item.name }}</label><br>
  <label>Tipo: {{ $store.state.red.item.type }}</label><br>
  <label>Estructura CIDOC: {{ $store.state.red.item.id }}</label><br>
  

 <v-btn @click="addEvento('nuevo')">Agregar Evento Hijo</v-btn>
 <v-btn @click="addEvento('editar')">Editar Evento</v-btn>

 <div v-for="(item, index) in items" :key="index">
      <label @click="verActividad(item)"><b>{{ item.cLabel }}</b></label>
  </div>

 <add-evento v-if="dialogEvent" :clave="$store.state.red.item.cDestino" :id="$store.state.red.item.idEvento" 
  :item="$store.state.red.item" :dlg="$store.state.red.item" @cerrar="cerrarDialog()" :tipo="tipoEvento"></add-evento>

</v-card-text>
</v-card>

</template>
<script>
import addEvento from './addEvento.vue';
export default {
  name: "adminAutor",
  props: [],
  components:{
    addEvento
  },
  data: () => ({
    dialogEvent: false,
    tipoEvento: 'nuevo',
    items: []

  }),
  mounted(){
      
  },
  created(){
      
  },
  beforeMount(){

  },
  watch:{
      '$store.state.red.item'(){
        this.initialize();
      }
  },
  computed:{
  
  },
  methods:{
      initialize(){
        console.log('info');
        console.log(this.$store.state.red.item);
        axios
          .get("/eventos/delevento/" + this.$store.state.red.item.id)
          .then((res) => {
            this.items = res.data;
            
          })
          .catch((error) => {
          });
      },
      addEvento(tipo){
        console.log(this.$store.state.red.item);
        this.tipoEvento = tipo;
        this.dialogEvent = true;
      },
      cerrarDialog(){
        this.dialogEvent = false;
        this.initialize();
      },
      verActividad(item){
        console.log(item);
        axios
          .get("/eventos/nodo/" + item.cveTarget + "_" + item.idTarget)
          .then((res) => {
            console.log(res.data);
            var nodes = [];
            var links = [];
              nodes.push({
                  id: item.cveTarget + "_" + item.idTarget,
                  idItem: res.data.idEvento,
                  cidoc: item.cveTarget,
                  type: item.cTipo,
                  name: res.data.cNombre,
              });
              links.push({
                source: this.$store.state.red.item.id,  
                target: item.cveTarget + "_" + item.idTarget,
                name: item.cLabel,
                type: item.cTipo
              });

              console.log(nodes);
              console.log(links);

              this.$emit('resetGrafo', nodes, links, true);
            
          })
          .catch((error) => {
          });
       
       
      }
  }
}

</script>

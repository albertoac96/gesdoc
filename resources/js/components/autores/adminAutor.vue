<template>
    <v-card>
   <v-tabs
    fixed-tabs
    background-color="indigo"
    dark
    v-model="tab"
  >
    <v-tab>
      Eventos
    </v-tab>
    <v-tab>
      Red
    </v-tab>
    <v-tab>
      Linea de tiempo
    </v-tab>
  </v-tabs>


  <v-tabs-items v-model="tab">
     <v-tab-item>
        <info></info>
      </v-tab-item>

      <v-tab-item>
        <grafo :eventos="$store.state.eventos" :idPrincipal="$route.params.id" nombrePrincipal="MOM"></grafo>
      </v-tab-item>
      <v-tab-item>
        <linea :eventos="$store.state.eventos"></linea>
      </v-tab-item>
    </v-tabs-items>

</v-card>

</template>
<script>
import info from './admin/info.vue';
import red from './admin/red.vue';
import grafo from './admin/grafo.vue';
import timeline from './admin/timeline.vue';
import linea from './admin/linea_tiempo.vue';
export default {
    name: "adminAutor",
    props: [],
    components:{
        info, red, timeline, grafo, linea
    },
    data: () => ({
        tab: null,
        idAutor: 0

    }),
    mounted(){
        this.initialize();
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
        initialize(){
          this.idAutor = this.$route.params.id;
          console.log(this.idAutor);
        }
    }
}

</script>
<style>
.v-data-table__selected{
    background-color: orange;
}
</style>
<template>
<div class="aviso-cookies">
    <strong class="text-subtitle-2 text-center">Archivos subiendo</strong>
    <v-card class="mt-2" v-for="item in $store.state.UploadProgress.infoArchivos" :key="item.name">
    <v-card-subtitle>{{item.name}}</v-card-subtitle>
    <v-card-text>
    <v-progress-linear 
      :value="item.progress"
      height="25"
      striped
    >
      <strong>{{Math.ceil(item.progress)}}%</strong>
    </v-progress-linear>
    </v-card-text>
    </v-card>
   

</div>
    
</template>
<script>
import { mapGetters } from 'vuex'
export default {
    name: "UploadProgress",
    props: [],
    components:{

    },
    data: () => ({
        knowledge: 33,
    }),
    mounted(){

    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{
        UploadProgressArchivos(nValue, lValue){
            
            
            ////console.log(nValue, lValue);
        }
    },
    computed:{
        ...mapGetters([
      'UploadProgressArchivos'
    ])
    },
    methods:{

    }
}
</script>

<style>
.aviso-cookies {
  background: #fff;
  padding: 20px;
  width: calc(100% - 80px);
  max-width: 300px;
  line-height: 150%;
  border-radius: 10px;
  position: fixed;
  bottom: 20px;
  left: 20px;
  z-index: 100;
  padding-top: 60px;
  box-shadow: 0px 2px 20px 10px rgba(222, 222, 222, 0.25);
  text-align: center;
}
</style>
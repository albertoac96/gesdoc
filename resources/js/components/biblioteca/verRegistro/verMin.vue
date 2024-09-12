<template>
<v-card>
 


   <v-card-subtitle v-if="archivo != 0">{{archivo.cNombre}}</v-card-subtitle>
    <v-card-subtitle v-else>No hay archivos adjuntos</v-card-subtitle>


<iframe v-if="archivo.cExtension == 'mp4' || archivo.cExtension == 'mp3'" width="100%" height="100%"
:src="traeRecurso()">
</iframe>




    <v-img
        v-else
          max-width="500px"
          max-height="400px"
       :src="traeMin()"
    ></v-img>


</v-card>
</template>
<script>
export default {
    name: "verMin",
    props: ["archivo"],
    components:{

    },
    data: () => ({
       
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
    traeMin(){
      if(this.archivo == 0 || this.archivo.cImagen == null){
          return ("/images/archivos/docDefault.jpg");
      } else {
           return ("/images/archivos/" + this.archivo.cImagen);
      }
      
           
         
       
    },
    traeRecurso(){
        return ("/archivos/" + this.archivo.idDocumento + "-" + this.archivo.idRelArDoc + "." + this.archivo.cExtension);
    }
    }
}
</script>
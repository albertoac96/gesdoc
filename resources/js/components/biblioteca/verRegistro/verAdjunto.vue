<template>
<v-card>
   

    <iframe 
       v-if="archivo.idExt >= 2 && archivo.idExt <= 7"
        style="overflow:hidden;height:100vh;width:100%"
        :src="traeRecurso()">
    </iframe>
<div v-else>
<vue-photo-zoom-pro :url="img" :high-url="img">
   
</vue-photo-zoom-pro>

 <!--<v-img
    
    contain
        max-height="96vh"
        max-width="100%"
        :src="img"
    ></v-img>-->
</div>

   

</v-card>
</template>
<script>
import VuePhotoZoomPro from 'vue-photo-zoom-pro'
import 'vue-photo-zoom-pro/dist/style/vue-photo-zoom-pro.css'
export default {
    name: "verAdjunto",
    props: ['archivo'],
    components:{
        VuePhotoZoomPro
    },
    data: () => ({
        img: "",
        loaded: false,
        width: {
            type: Number,
            default: 168
        },
    }),
    mounted(){

       
       
    },
    created(){
        const img = new Image()
        img.src = this.traeRecurso();
        img.addEventListener('load', ()=>{
        this.loaded = true
        })
        
    },
    beforeMount(){

    },
    watch:{
        
    },
    computed:{
   
    },
    methods:{
      traeRecurso(){
           var LcDoc = "/archivos/" + this.archivo.idDocumento + "-" + this.archivo.idRelArDoc + "." + this.archivo.cExtension;
            if(this.archivo.idExt >= 2 && this.archivo.idExt <= 7){//Office
      
            return ("https://docs.google.com/gview?url=" + "http://172.16.4.61" + LcDoc + "&embedded=true");

        }
        if(this.archivo.idExt >= 10 && this.archivo.idExt <= 15){ //Imagen
       
            this.img = LcDoc;
            return LcDoc;
        }
          
      }
    }
}
</script>
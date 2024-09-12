<template>
<div>
    <resultados :id="$route.params" tipo="B" > </resultados>
</div>
</template>
<script>
import resultados from '../biblioteca/resultados.vue';
export default {
    name: "resultBuscar",
    props: [""],
    components:{
        resultados
    },
    data: () => ({
        params: "",
    }),
    mounted(){
        //this.params = this.$route.params
        //console.log(this.$route.params);
       
    },
    created(){
        //this.params = this.$route.params
        ////console.log(this.params)
        //this.fnTraerResultados(this.$route.params)
    },
    beforeMount(){

    },
    watch:{
    "$route.params": function (params) {
      ////console.log(params);
      //this.fnTraerResultados(params);
      
    },
    },
    computed:{

    },
    methods:{
       
    }
}
</script>
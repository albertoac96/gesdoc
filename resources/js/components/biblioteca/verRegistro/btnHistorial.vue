<template>
   

            <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
         
           <v-btn icon  v-bind="attrs"
            v-on="on">
            <v-icon>mdi-history</v-icon>
           
            </v-btn>
        </template>

        <span>Ver historial</span>
      </v-tooltip>
</template>
<script>
export default {
    name: "verHistorial",
    props: ["historial"],
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

    }
}
</script>
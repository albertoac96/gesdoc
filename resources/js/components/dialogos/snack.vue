<template>
    <v-snackbar :centered="$store.state.snack.centered" v-model="$store.state.snack.model"  :timeout="3000" :color="$store.state.snack.color">
      {{ $store.state.snack.text }} 

      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="$store.state.snack.model = false">
          CERRAR
        </v-btn>
      </template>
    </v-snackbar>
</template>
<script>
export default {
    name: "snack",
    props: [],
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
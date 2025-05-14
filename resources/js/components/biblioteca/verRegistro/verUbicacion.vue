<template>
    <v-row>
        
        <v-chip
            v-for="(carpeta, index) in ubicacion"
            :key="index"
            class="mt-2"
            color="black"
            x-small
            outlined
            label
            @dblclick="fnIrCarpeta(carpeta)"
        >
            {{carpeta.cCarpeta}}
        </v-chip>
        <v-btn  v-if="$canBtn('editar', 'No tiene permisos para editar')" icon small color="info" 
             @click="dlgMoveDoc = true"><v-icon small>mdi-pencil</v-icon></v-btn>

             <dlgMove @newUbi="ubicacion = $event" v-if="dlgMoveDoc" :idDoc="idDoc" @close="dlgMoveDoc=false"></dlgMove>
    </v-row>
</template>
<script>
import dlgMove from '../../dialogos/edit/dlgEditUbicacion.vue'
export default {
    name: "verUbicacion",
    props: ["ubicacion", "idDoc"],
    components:{
        dlgMove
    },
    data: () => ({
        dlgMoveDoc: false
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
        fnIrCarpeta(carpeta){
            var node = { cCarpeta: carpeta.cCarpeta, idCarpeta: carpeta.idCarpeta };
            var ruta = { name: "biblioteca", params: node };
            this.$router.push(ruta);
        }
    }
}
</script>
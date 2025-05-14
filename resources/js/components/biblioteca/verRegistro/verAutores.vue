<template>
<div>
    <v-list-item-title>Autores 
        <v-btn v-if="$canBtn('editar', 'No tiene permisos para editar')" icon small @click.stop="dlgEditAutores = true"><v-icon small>mdi-pencil</v-icon></v-btn>
    </v-list-item-title>

    <dlgEditAutores v-if="dlgEditAutores" :autores="autores2" :idDoc="idDoc" @close="dlgEditAutores=false" @itemEdit="EditAutoresOK($event)"></dlgEditAutores>

    <div v-if="autores2.length > 0">
       
       
         <v-tooltip v-for="autor in autores2" :key="autor.idOrden" bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-chip
            class="mt-1 mr-1"
            :color="autor.cColor"
            text-color="white"
            small
            @dblclick.stop="irA(autor.idAutor)"
            v-bind="attrs"
            v-on="on"
          >
            {{autor.cNombre}}
          </v-chip>
        </template>

        <span>{{ autor.cTipoAutor }}</span>
      </v-tooltip>


        
    </div>
    <div v-else>
         <v-chip 
            color="indigo"
            text-color="white"
            small
        >
           No hay autores asociados
        </v-chip>
    </div>
</div>
</template>
<script>
import dlgEditAutores from "../../dialogos/edit/dlgEditAutores.vue"
export default {
    name: "verAutores",
    props: ["autores", "idDoc"],
    components:{
      dlgEditAutores
    },
    data() {
      return{
         dlgEditAutores: false,
        autores2: this.autores,
      }
      
       
    },
    mounted(){
      
      
    },
    created(){
         
    },
    beforeMount(){

    },
    watch:{
      autores(newVal){
        this.autores2 = this.autores;
      },
      autores2(newVal){
       
      }
    },
    computed:{

    },
    methods:{
        irA(idAutor){
            //console.log(idAutor);
        },
        EditAutoresOK(newVal){
          
          if(newVal == 0){
            this.dlgEditAutores = false;
            return;
          }
          if(newVal == 1){
            this.autores2 = [];
            this.dlgEditAutores = false;
            return;
          }
          this.autores2 = newVal;
          //console.log(newVal);
           //console.log(this.autores2.sort((a, b) => a.iOrden - b.iOrden));
          this.dlgEditAutores = false;
        }
    }
}
</script>
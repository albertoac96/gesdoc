<template>
 <v-card color="yellow lighten-4" outlined>
    
        <h5 class="ma-4" justify="center"><b>Notas</b>
          <v-btn icon small color="success" @click.stop="NewNota()"><v-icon small>mdi-plus</v-icon></v-btn>
          <NewNota @itemEdit="NewNotaOK($event)" v-if="dlgNewNota" :idDoc="idDoc" :tipo="tipo" :nota="notaedit" @close="dlgNewNota=false"></NewNota>
        </h5>
    <v-expansion-panels  v-if="notas.length > 0" focusable>
      <v-expansion-panel class="ml-4 mr-7"
        v-for="item in notas"
        :key="item.idNota"
      >
        <v-expansion-panel-header color="blue lighten-5">{{item.cTitulo}}</v-expansion-panel-header>
        <v-expansion-panel-content>
          <p class="mt-5"><v-btn color="info" outlined @click.stop="EditNota(item)"><v-icon small>mdi-pencil</v-icon> Editar</v-btn></p>
          <span class="ma-7" v-html="item.cNota"></span>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>

    <v-card-text v-else>No hay notas del documento</v-card-text>

 </v-card>
</template>
<script>
import NewNota from "../../dialogos/edit/dlgEditNota.vue";
export default {
    name: "verNotas",
    props: ["notas", "idDoc"],
    components:{
        NewNota
    },
    data: () => ({
        dlgNewNota: false,
        tipo: "",
        notaedit: "",
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
      NewNotaOK(item){
        if(this.tipo == 'new'){
          this.notas.push(item);
          
        } else {
          //console.log(item);
          var pos = this.notas.map(function(e) { return e.idNota; }).indexOf(item.idNota);
          //console.log(this.notas[pos]);
          this.notas[pos].cTitulo = item.cTitulo;
          this.notas[pos].cNota = item.cNota;
        }
        this.dlgNewNota = false;
      },
      EditNota(nota){
        if(this.VerificaPermiso('editar') == true){
          this.tipo = "edit";
        this.notaedit = nota;
        this.dlgNewNota = true;
        return;
        }
        this.$store.state.snack.text = "No tienes permisos para editar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
          this.$store.state.snack.centered = true;
        
        
      },
      NewNota(){
        if(this.VerificaPermiso('agregar') == true){
         this.tipo = "new";
        this.notaedit = "";
        this.dlgNewNota = true;
        return;
        }
        this.$store.state.snack.text = "No tienes permisos para editar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
          this.$store.state.snack.centered = true;
         
       
      }
    }
}
</script>
<template>
    <v-container class="pa-4 text-center">
        <h5 class="mt-4 md-4">Documentos relacionados

      <v-btn v-if="$canBtn('agregar', 'No tiene permisos para agregar')" icon @click.stop="addRel()"><v-icon>mdi-plus</v-icon></v-btn>
       
    </h5>
    <dlgNewDocRel v-if="dlgDocRel" :idDoc="idDoc" @close="dlgDocRel=false" @newItem="addDoc($event)" ></dlgNewDocRel>
    <span v-if="docs.length == 0">No hay documentos relacionados</span>
    <v-row
    v-else
      class="fill-height"
      align="center"
      justify="center"
    >
      <template v-for="item in docs">
        <v-col
          :key="item.idDocumento"
          cols="12"
          md="4"
        >
          <v-hover v-slot="{ hover }">
            <v-card
              :elevation="hover ? 12 : 2"
              :class="{ 'on-hover': hover }"
              dark
              link
              :to="'/veritem/' + item.idDocumento"
            >

            
              <v-img
                :src="traeImg(item.cImagen)"
                height="225px"
              >
                <v-card-text class="text-h6 white--text">
                  <v-row
                    class="fill-height flex-column"
                    justify="space-between"
                  >
                  <p class="mt-2 subtitle-1">
                  <v-icon small>{{item.cIcono}}</v-icon>
                  <span>{{item.cTipoDoc}}</span>
                  </p>
                    <p class="ma-2 font-weight-medium text-left">
                      {{ item.cTituloDoc }}
                    </p>

                    

                   
                  </v-row>
                </v-card-text>
              </v-img>
            </v-card>
          </v-hover>
        </v-col>
      </template>
    </v-row>
  </v-container>
</template>
<script>
import dlgNewDocRel from '../../dialogos/dlgNuevoDocRel.vue';

export default {
    name: "verDocsRel",
    props: ["docs", "idDoc"],
    components:{
dlgNewDocRel
    },
    data: () => ({
        dlgDocRel: false,
        icons: ['mdi-rewind', 'mdi-play', 'mdi-fast-forward'],
      items: [],
      transparent: 'rgba(255, 255, 255, 0)',
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
      addRel(){
         if(this.VerificaPermiso('agregar') == true){
            this.dlgDocRel = true
            return;
          }
           this.$store.state.snack.text = "No tienes permisos para agregar elementos";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
          this.$store.state.snack.centered = true;
        
      },
        traeImg(img){
            if(img == 'docDefault.jpg'){
                return ("/images/archivos/docDefaultOs.jpg");
            }
            return ("/images/archivos/" + img);
        },
        addDoc(item){
         
            //console.log("los traigo")
            for(var i = 0; i < item.length; i++){
                 var existe = this.docs.map(function(e) { return e.idDocumento; }).indexOf(item.idDocumento);
                 if(existe == -1){
                     this.docs.push(item[i]);
                 }
            }
            this.dlgDocRel = false;
        },
        toDocRel(item){
            //console.log(item);
       
            var node = { cDocumento: item.cTituloDoc, id: item.idDocumento };
            var ruta = { name: "veritem", params: node };
            //console.log(ruta);
            this.$router.push(ruta);
        }
    }
}
</script>

<style scoped>
.v-card {
  transition: opacity .4s ease-in-out;
}

.v-card:not(.on-hover) {
  opacity: 0.6;
 }

.show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>
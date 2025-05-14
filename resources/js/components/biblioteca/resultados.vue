<template>
  <v-card>
    <v-card-title>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Buscar en esta carpeta nuevo"
        single-line
        hide-details
      ></v-text-field>
    
    </v-card-title>


    




    <v-data-table
 
      :headers="dessertHeaders"
      :items="items"
      :search="search"
      :expanded.sync="expanded"
      @item-expanded="onExpand"
      item-key="id"
      show-expand
      class="elevation-1"
      @dblclick:row="doble"
      :loading="dataLoad"
      loading-text="Cargando..."
    >
      <!-- Negritas la fecha -->
      <template v-slot:item.cTipoDoc="{ item }">
        <v-icon v-if="item.cTipoDoc == 'C'">{{ item.cIcono }}</v-icon>

        <v-tooltip v-else bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-badge
              bordered
              bottom
              v-if="item.cExtension == 'pdf'"
              :color="fnTieneContenido(item.cContenido)"
              dot
              offset-x="10"
              offset-y="10"
            >
              <v-icon :color="item.cExtColor" dark v-bind="attrs" v-on="on">
                {{ item.cIconExt }}
              </v-icon>
            </v-badge>

            <v-icon
              v-else
              :color="item.cExtColor"
              dark
              v-bind="attrs"
              v-on="on"
            >
              {{ item.cIconExt }}
            </v-icon>
          </template>
          <v-icon color="white" dark>
            {{ item.cIcono }}
          </v-icon>
          <span>{{ item.cTipoDoc }} ({{ item.cExtension }})</span>
        </v-tooltip>
      </template>


      <!--<template v-slot:item.cTitulo="{ item }">
        
        <v-tooltip max-width="40%" color="indigo lighten-1" bottom>
          <template v-slot:activator="{ on, attrs }">
            
              <span  v-bind="attrs" v-on="on">
                {{ item.cTitulo }}
              </span>
            

           
          </template>
          
          <span v-if="item.cDesc" v-html="item.cDesc"></span>
           <span v-else>No hay resumen</span>
        </v-tooltip>
       

      </template>-->

       <template v-slot:item.cDesc="{ item }">
       
       <span  v-if="item.cDesc" v-html="AcortarString(item.cDesc, 200, 1)"></span>
        <span v-else>No hay resumen</span>
        <v-tooltip v-if="item.cTipoDoc != 'C'" bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn small color="" icon v-bind="attrs" v-on="on" @click="fnAbrirNuevaPestaña(item.id)">
                    <v-icon small >mdi-open-in-new</v-icon>
                  </v-btn>
                </template>

                <span>Abrir en nueva pestaña</span>
              </v-tooltip>
       
       

      </template>


        <template v-slot:item.dInicio="{ item }">
        <span v-if="item.dInicio == null" >Sin fecha</span>
        <span v-else-if="item.cTipoDoc == 'C'" v-html="MiTiempo(item.dInicio)"></span>
       <span v-else v-html="cFechaCompare(item.dInicio, item.dFin)"></span>
       
       

      </template>




      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">
            
            <!--<div class="text-right mt-2">
              <v-menu offset-y>
                <template v-slot:activator="{ on, attrs }">
                 <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item>
                    <v-list-item-title>Editar resumen</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-title>Mover</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-title>A Papelera</v-list-item-title>
                  </v-list-item>
                 
                  <v-list-item>
                    <v-list-item-title>Adjuntar enlace URL</v-list-item-title>
                  </v-list-item>
                   <v-list-item>
                    <v-list-item-title>Adjuntar archivo</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>-->
          
          
          
          
          
        

          <label class="d-flex d-sm-none mt-3">Mas información del documento: <b> {{ item.cTitulo }} </b></label>
          <v-row class="mt-3 mb-3">
            <v-col cols="2" class="d-none d-md-flex">
              <v-img width="250" height="200" :src="traeImagen(item.cImagen)" @dblclick.stop="fnClickImg()"></v-img>
                <v-dialog
                  v-model="dlgVerImagen"
                  width="50%"
                  height="80%"
                >
                  <v-card>

                    <verImagen :imagen="traeImagen(item.cImagen)"></verImagen>

                    
                  </v-card>
                </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" sm="12" md="10">
             
                <div v-if="item.cDesc" class="text-justify mr-5" v-html="item.cDesc"></div>
                <div v-else>No hay resumen</div>
              
            </v-col>
          </v-row>

          <v-col class="mb-3">
            <v-row v-if="item.cTipoDoc != 'C'">
              <!--<v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="red" icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-note</v-icon>
                  </v-btn>
                </template>

                <span>Notas del documento</span>
              </v-tooltip>

              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="red" icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-folder-star-multiple</v-icon>
                  </v-btn>
                </template>

                <span>Temas relacionados</span>
              </v-tooltip>

              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="red" icon v-bind="attrs" v-on="on" @click="dlgVerDoc=false">
                    <v-icon>mdi-file-eye</v-icon>
                  </v-btn>
                </template>

                <span>Ver documento</span>
              </v-tooltip>
               <v-dialog
                  v-model="dlgVerDoc"
                 fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
                >
                  <v-card>
                    

                    <verPDF2 pdf="/pdf/archivo.pdf" @close="dlgVerDoc = false"></verPDF2>

                    
                  </v-card>
                </v-dialog>

              <v-spacer></v-spacer>

              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="" icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-open-in-new</v-icon>
                  </v-btn>
                </template>

                <span>Abrir en nueva pestaña</span>
              </v-tooltip>-->
            </v-row>

            <v-row v-else>
              <!--<v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="red" icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                </template>

                <span>Editar carpeta</span>
              </v-tooltip>

              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="red" icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-folder-information</v-icon>
                  </v-btn>
                </template>

                <span>Mas info la carpeta</span>
              </v-tooltip>-->

              <v-spacer></v-spacer>

              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn @click="fnEntrarCarpeta(item)" icon v-bind="attrs" v-on="on">
                    <v-icon>mdi-arrow-right</v-icon>
                  </v-btn>
                </template>

                <span>Entrar</span>
              </v-tooltip>
            </v-row>
          </v-col>
        </td>
      </template>
    </v-data-table>
  </v-card>
</template>
<script>
import verImagen from '../dialogos/verImagen.vue';
import verPDF from '../dialogos/verPDF.vue';
import verPDF2 from '../dialogos/verPDF2.vue';
export default {
  name: "resultados",
  props: ["id", "tipo"],
  components:{
    verImagen,
    verPDF,
    verPDF2
  },
  data: () => ({
    verComo: false,
    search: "",
    dataLoad: true,
    dlgVerImagen: false,
    dlgVerDoc: false,
    expanded: [],
    singleExpand: false,
    dessertHeaders: [
      {
        text: "",
        align: "start",
        sortable: false,
        value: "cTipoDoc",
        width: 10,
      },
      { text: "Nombre", value: "cTitulo" },
      { text: "Resumen", value: "cDesc" },
      { text: "Autores", value: "cAutores" },
      { text: "Años", value: "dInicio" },

      { text: "", value: "data-table-expand" },
    ],
    items: [],
  }),
  watch: {
    id: function (id) {
     if(this.tipo == "B"){
       this.dataLoad = true;
      this.fnTraerResultados(id);
    } else {
      this.dataLoad = true;
      this.fnLlenaTabla(id)
    }
    },
   
  },
  mounted() {
    if(this.tipo == "B"){
      this.fnTraerResultados(this.id);
    } else {
      this.fnLlenaTabla(this.id)
    }
    
  },
  methods: {
    fnLlenaTabla(id) {
      axios.get("/listadocs/" + id).then((res) => {
        this.items = res.data;
        this.dataLoad = false;
        //console.log(res.data);
      });
    },
     fnTraerResultados(params){
            axios
              .post("/resultados", params)
              .then((res) => {
                  this.items = res.data;
                   this.dataLoad = false;
        //console.log(res.data);
            })
            .catch((error) => {
            });
        },
   
    doble(event, { item }) {
      if (item.cTipoDoc == "C") {
        var node = { cCarpeta: item.cTitulo, idCarpeta: item.id };
        ////console.log(node);
        //return;
        var ruta = { name: "biblioteca", params: node };
        this.$router.push(ruta);
        return;
      }
      var node = { cDocumento: item.cTitulo, idDocumento: item.id };
      var ruta = { name: "veritem", params: item };
      this.$router.push(ruta);
    },
    fnAbrirNuevaPestaña(id){
     var url = "/veritem/" + id;
        var win = window.open(url, '_blank');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
    },
    fnEntrarCarpeta(item){
     
       var node = { cCarpeta: item.cTitulo, idCarpeta: item.id };
        
        var ruta = { name: "biblioteca", params: node };
        this.$router.push(ruta);
    
    },
    fnTieneContenido(data) {
      if (data > 0) {
        return "succes";
      }
      return "error";
    },
    fnClickImg(){
      //console.log("click en la imagen");
      this.dlgVerImagen = true;
    },
    onExpand({ item, value }) {
    ////console.log(item, value);
  },
  traeImagen(imagen){
    return ("/images/archivos/" + imagen);
  }
  },
};
</script>


<style scoped>
.v-card2 {
  transition: opacity .4s ease-in-out;
}

.v-card2:not(.on-hover) {
  opacity: 0.6;
 }

.show-btns {
  color: rgba(255, 255, 255, 1) !important;
}
</style>
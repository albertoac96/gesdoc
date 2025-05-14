<template>
  <div>
    <v-row>
      <v-col cols="12" md="4">
        <verMin :archivo="archivoMin"></verMin>
        <v-card class="mt-5" outlined>
          <v-card-text>
          <verArchivos :archivos="archivos" :documento="info" @newMin="archivoMin = $event"></verArchivos>
            <v-divider></v-divider>
          <verEnlaces :enlaces="enlaces" :titulo="info.Documento.cTituloDoc" :idDoc="info.idDoc"></verEnlaces>
            <v-divider></v-divider>
          <verTemas :temas="temas" :titulo="info.Documento.cTituloDoc" :idDoc="info.idDoc"></verTemas>
            <v-divider></v-divider>
          <verEtiquetas :tags="tags" :titulo="info.Documento.cTituloDoc" :idDoc="info.idDoc"></verEtiquetas>
          </v-card-text>
          <v-card-actions>
            
              <!--<btnCrearBiblio></btnCrearBiblio>
              <v-spacer></v-spacer>
              <btnExportar></btnExportar>
            <v-spacer></v-spacer>-->
            
            <btnBuscar :titulo="info.Documento.cTituloDoc" :atrs="atributos"></btnBuscar>
            <v-spacer></v-spacer>
            <btnPapelera :doc="info.idDoc"></btnPapelera>
            <!--<v-spacer></v-spacer>
            <btnHistorial :titulo="info.Documento.cTituloDoc" :idDoc="info.idDoc"></btnHistorial>-->
            
          </v-card-actions>
        </v-card>
      </v-col>

      <v-col cols="12" md="8">
        <v-card outlined>
          <v-card-text>
          <v-card-text>
            <h3>{{ info.Documento.cTituloDoc }} <v-btn  v-if="$canBtn('editar', 'No tiene permisos para editar')" icon small color="info" 
             @click.stop="openEditTitulo()"><v-icon small>mdi-pencil</v-icon></v-btn></h3>
             
          </v-card-text>
          <v-divider></v-divider>
          <v-card-subtitle>
            <verInfo class="ml-2" :info="info"></verInfo>
            <verUbicacion class="ml-2"  :idDoc="info.idDoc" :ubicacion="info.Ubicacion"></verUbicacion>
          </v-card-subtitle>
          <verAutores class="ml-4"  :autores="autores" :idDoc="info.idDoc"></verAutores>
          <v-divider></v-divider>
          
          <v-card color="yellow lighten-5" outlined>
          <v-list-item-title class="ml-4 mt-4"><h5><b>Resumen</b>
            <v-btn  v-if="$canBtn('editar', 'No tiene permisos para editar')" icon small color="info" @click.stop="dlgEditResumen = true"><v-icon small>mdi-pencil</v-icon></v-btn></h5>
            <dlgEditResumen @itemEdit="EditResumenOK($event)" v-if="dlgEditResumen" :resumen="info.Documento.cResumenDoc" :idDoc="info.idDoc" @close="dlgEditResumen=false"></dlgEditResumen>
          </v-list-item-title>
           
           
              <p class="ml-4 mt-4 font-weight-medium" v-if="info.Documento.cResumenDoc == ''">No hay resumen</p>
              <div v-else>
              <p class="ml-4 mt-4 font-weight-medium" v-if="vermax" v-html="cResumenVerMas(info.Documento.cResumenDoc, 500)"></p>
              <p class="ml-4 mt-4 font-weight-medium" v-if="vermin" v-html="info.Documento.cResumenDoc"></p>
              </div>
           
              
            
        


          
          <a class="ml-4" color="blue" @click="verMax()" v-if="vermax && LongitudMax(info.Documento.cResumenDoc, 500) == true">Ver más</a>
          <a class="ml-4" color="blue" @click="verMin()" v-if="vermin">Ver menos</a>

          </v-card>

          <v-divider></v-divider>
          <verAtributos :atributos="atributos" :idDoc="info.idDoc"></verAtributos>
          <v-divider></v-divider>
           <verNotas :notas="notas" :idDoc="info.idDoc"></verNotas>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
     <v-divider></v-divider>
    <v-row>
      
      <v-col cols="12">
      
        <verDocsRel :docs="docsrel" :idDoc="info.idDoc"></verDocsRel>
      </v-col>
    </v-row>


    <v-dialog
      v-model="dlgEditTitulo"
      width="500"
      persistent
    >
     

      <v-card>
       

        <v-card-title class="text-h5 grey lighten-2">
           
          Edita título
       </v-card-title>

        
        <v-card-text>

        

            
             <v-textarea v-model="editTitulo" label="Título del documento"></v-textarea>

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn
            color="red"
            text
            @click="closeEditTitulo()"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="saveEditTitulo()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-overlay
          :absolute="absolute"
          :value="overlay"
        >
        <v-card>
          <v-card-text>
            Este documento se encuentra en papelera.
          </v-card-text>
        </v-card>
         <!-- <v-btn
          v-if="$canBtn('/papelera', 'No tiene permisos para editar')"
            color="success"
            @click="overlay = false"
          >
            Restaurar documento
          </v-btn>-->
        </v-overlay>
   


  </div>
</template>
<script>
import dlgEditResumen from "../dialogos/edit/dlgEditResumen.vue";
import verAutores from "./verRegistro/verAutores.vue";
import verAtributos from "./verRegistro/verAtributos.vue";
import verTemas from "./verRegistro/verTemas.vue";
import verUbicacion from "./verRegistro/verUbicacion.vue";
import verInfo from "./verRegistro/verNombre.vue";
import verArchivos from "./verRegistro/verArchivos.vue";
import verMin from "./verRegistro/verMin.vue";
import verEnlaces from "./verRegistro/verEnlaces.vue";
import verEtiquetas from "./verRegistro/verEtiquetas.vue";
import verNotas from "./verRegistro/verNotas.vue";
import verDocsRel from "./verRegistro/verDocsRel.vue";
import btnBuscar from "./verRegistro/btnBuscar.vue";
import btnCompartir from "./verRegistro/btnCompartir.vue";
import btnCrearBiblio from "./verRegistro/btnCrearBiblio.vue";
import btnExportar from "./verRegistro/btnExportar.vue";
import btnHistorial from "./verRegistro/btnHistorial.vue";
import btnPapelera from "./verRegistro/btnPapelera.vue";
export default {
  name: "verItem",
  props: ["idDocumento"],
  components: {
    verAutores,
    verAtributos,
    verTemas,
    verUbicacion,
    verInfo,
    verMin,
    verArchivos,
    verEnlaces,
    verEtiquetas,
    verNotas,
    verDocsRel,
    btnBuscar,
    btnCompartir,
    btnCrearBiblio,
    btnExportar,
    btnHistorial,
    btnPapelera,
    dlgEditResumen
  },
  data: () => ({
    
    info: {
      Documento: "",
      Ubicacion: "",
      idDoc: "",
      fechas: "",
      dates: "",
    },
    autores: "",
    temas: "",
    notas: "",
    atributos: "",
    archivos: "",
    enlaces: "",
    tags: "",
    docsrel: "",
    imgMin: "",
    dlgEditResumen: false,
    vermax: true,
    vermin: false,
    archivoMin: [],
    dlgEditTitulo: false,
    editTitulo: "",
    overlay:false,
    absolute: true,
  }),
  mounted() {},
  created() {
    this.info.idDoc = this.$route.params.id;
    this.traeinfo(this.$route.params.id);
    
  },
  beforeMount() {},
  watch: {
    "$route.params.id": function (id) {
   
      this.traeinfo(id);
    },
  },
  computed: {
   
  },
  methods: {
    traeinfo(id){
      window.scrollTo(0,0);
      axios
      .get("/infodoc/" + id)
      .then((res) => {
        if(res.data.info[0].cEstatus == 'B'){
          this.overlay = true;
        }
        this.total = res.data;
        this.info.dates = res.data.fechas[0];
        this.info.Documento = res.data.info[0];
        
        if(res.data.fechas[0].dInicio == null){
          this.info.fechas = "No hay fecha"
        }
        
        
        
        this.info.Ubicacion = res.data.ubi;
        this.autores = res.data.autores;
        this.temas = res.data.temas;
        this.notas = res.data.notas;
        this.atributos = res.data.atributos;
        this.archivos = res.data.archivos;
        
        this.enlaces = res.data.enlaces;
        this.tags = res.data.etiquetas;
        this.docsrel = res.data.relacionados;
        this.imgMin = this.traeMin(res.data.archivos);
         this.arhivoMinInicial();
        this.info.fechas = this.sacarFechas(res.data.fechas[0]);
       

        

        
      })
      .catch((error) => {});
    },
    verMax(){
      this.vermax = false;
      this.vermin = true;
    },
    verMin(){
      this.vermax = true;
      this.vermin = false;
    },
    arhivoMinInicial(){
      if(this.archivos.length == 0){
        this.archivoMin = 0;
      } else {
        for(var i=0; i < this.archivos.length; i++){
          if(this.archivos[i].iMostrar == 1){
            this.archivoMin = this.archivos[i];
          }
        }
      }
      //console.log(this.archivoMin);
      return this.archivoMin;
    },
    
    sacarFechas(fechas) {
       return this.cFechaCompare(fechas.dInicio, fechas.dFin);
    },
    traeMin(archivos){
      if(archivos.length == 0) return ("/images/archivos/docDefault.jpg");
      for(var i = 0; i < archivos.length; i++){
        if(archivos[i].iMostrar == 1){
          if(archivos[i].cImagen == null || archivos[i].cImagen == ""){
            return ("/images/archivos/docDefault.jpg"); 
          } 
          ////console.log(archivos[i].idRelArDoc);
          return ("/images/archivos/" + archivos[i].cImagen);
        }
      }
    },
    EditResumenOK(newItem){
      this.info.Documento.cResumenDoc = newItem;
      this.dlgEditResumen = false;
    },
    openEditTitulo(){
      this.editTitulo = this.info.Documento.cTituloDoc;
      this.dlgEditTitulo = true;
    }, 
    saveEditTitulo(){

      let InstFormData = new FormData();
                InstFormData.append('idDoc' , this.info.idDoc);
                 InstFormData.append('titulo' , this.editTitulo);

      
       axios
            .post("/editTitulo", InstFormData )
            .then((res) => {
               this.info.Documento.cTituloDoc = this.editTitulo;
               this.dlgEditTitulo = false;
            })
            .catch((error) => {
       }); 

      
    },
    closeEditTitulo(){
      this.dlgEditTitulo = false;
    }
  },
};
</script>



<template>
  <div style="overflow: hidden">
    <v-system-bar window dark>
      <v-btn-toggle dense v-model="toggle_exclusive">
        <v-btn :id="idConfig.cursorHandTool">
          <v-icon>mdi-hand</v-icon>
        </v-btn>

        <v-btn :id="idConfig.cursorSelectTool">
          <v-icon>mdi-format-text</v-icon>
        </v-btn>
      </v-btn-toggle>

      <v-divider class="mx-4" vertical></v-divider>

      
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.documentProperties" v-on="{ ...onTooltip }">
             <v-icon>mdi-file-document</v-icon>
          </v-btn>
        </template>
          <span>Propiedades del archivo</span>
      </v-tooltip>
      
      <v-tooltip v-if="$canBtn('descargar', 'No tiene permisos para agregar')" bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn  class="d-none d-sm-flex" icon small :id="idConfig.download" v-on="{ ...onTooltip }">
              <v-icon>mdi-download</v-icon>
          </v-btn>
        </template>
          <span>Descargar archivo</span>
      </v-tooltip>
       <button v-else v-show="mostrar" :id="idConfig.download" type="button">
      viewThumbnail
    </button>


      <v-spacer></v-spacer>

      <v-btn icon small :id="idConfig.zoomOut">
        <v-icon>mdi-magnify-minus-outline</v-icon>
      </v-btn>
      <v-btn icon small :id="idConfig.zoomIn">
        <v-icon>mdi-magnify-plus-outline</v-icon>
      </v-btn>

      <v-divider class="mx-4" vertical></v-divider>

     
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.firstPage" v-on="{ ...onTooltip }">
             <v-icon>mdi-page-first</v-icon>
          </v-btn>
        </template>
          <span>Ir a la primera página</span>
      </v-tooltip>

      
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.previousPage" v-on="{ ...onTooltip }">
             <v-icon>mdi-chevron-left</v-icon>
          </v-btn>
        </template>
          <span>Página anterior</span>
      </v-tooltip>

      <v-text-field
        class="mt-2"
        dense
        :id="idConfig.pageNumber"
        style="width: 50px"
        type="number"
      ></v-text-field>
      <span :id="idConfig.numPages"></span>

      
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.nextPage" v-on="{ ...onTooltip }">
             <v-icon>mdi-chevron-right</v-icon>
          </v-btn>
        </template>
          <span>Siguiente página</span>
      </v-tooltip>

      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.lastPage" v-on="{ ...onTooltip }">
            <v-icon>mdi-page-last</v-icon>
          </v-btn>
        </template>
          <span>Ir a la última página</span>
      </v-tooltip>

      <v-divider class="mx-4" vertical></v-divider>

    
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.pageRotateCcw" v-on="{ ...onTooltip }">
            <v-icon>mdi-restore</v-icon>  
          </v-btn>
        </template>
          <span>Girar a la izquierda</span>
      </v-tooltip>

     
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn class="d-none d-sm-flex" icon small :id="idConfig.pageRotateCw" v-on="{ ...onTooltip }">
            <v-icon>mdi-reload</v-icon>  
          </v-btn>
        </template>
          <span>Girar a la derecha</span>
      </v-tooltip>

      <v-spacer></v-spacer>

      <v-tooltip  v-if="$canBtn('agregar', 'No tiene permisos para agregar')" bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn icon small v-on="{ ...onTooltip }" @click.stop="fnCreaNota()">
            <v-icon>mdi-plus</v-icon>  
          </v-btn>
        </template>
          <span>Nueva nota</span>
      </v-tooltip>
         
      <v-menu offset-y>
        <template #activator="{ on: onMenu }">
          <v-tooltip bottom>
            <template #activator="{ on: onTooltip }">
              <v-btn icon small v-on="{ ...onMenu, ...onTooltip }">
                <v-icon>mdi-format-list-bulleted</v-icon>
              </v-btn>
            </template>

            <span>Ordenar</span>
          </v-tooltip>
        </template>

        <v-list>
          <v-list-item link @click="fnOrdenarXFecha()">
            <v-list-item-title>Por fecha de modificación</v-list-item-title>
          </v-list-item>
          <v-list-item link @click="fnOrdenarXPags()">
            <v-list-item-title>Por página</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
          
      <!--<v-menu offset-y>
        <template #activator="{ on: onMenu }">
          <v-tooltip bottom>
            <template #activator="{ on: onTooltip }">
              <v-btn icon small v-on="{ ...onMenu, ...onTooltip }">
                <v-icon>mdi-export</v-icon>
              </v-btn>
            </template>

            <span>Exportar notas</span>
          </v-tooltip>
        </template>

        <v-list>
          <v-list-item link @click="fnEnlaceTitulo()">
            <v-list-item-title>Exportar a Word</v-list-item-title>
          </v-list-item>
          <v-list-item link @click="fnEnlaceWebID()">
            <v-list-item-title>Exportar a Excel</v-list-item-title>
          </v-list-item>
          <v-list-item link @click="fnEnlaceWebID()">
            <v-list-item-title>Copiar al portappeles</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>-->

      
      <v-divider class="mx-4" vertical></v-divider>

      <v-btn icon dark @click="$emit('close')">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-system-bar>

    <v-row>
      <v-col cols="12" xl="9" lg="8" md="9" sm="12" xs="12">
        <vue-pdf-app
          class="barrasO"
          style="height: 95vh"
          :pdf="fnTraePDF()"
          page-scale="auto"
          :config="config"
          :id-config="idConfig"
          @open="OpenPDF"
          @after-created="afterCreatedHandler"
        >
        </vue-pdf-app>
      </v-col>

      <v-col
        class="d-none d-sm-flex"
        cols="12"
        xl="3"
        lg="4"
        md="3"
        sm="0"
        xs="0"
      >
      
       
          <v-list
            
            v-show="showTodas"
            v-if="items.length > 0"
            three-line
            class="mr-5"
            style="
              overflow-y: scroll;
              scroll-behavior: [ auto | smooth ];
              max-height: 95vh;
            "
            
          >
            <template v-for="item in items">
              <v-divider></v-divider>
              <v-list-item
                :key="item.idNota"
                link
                @click.stop="fnEntraNota(item)"
              >

              <v-list-item-avatar>
            <v-img v-if="item.avatar == null" src="https://cdn.vuetifyjs.com/images/lists/1.jpg"></v-img>
            <v-img v-else :src="item.avatar"></v-img>
          </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>
                    {{ item.titulo }}
                  </v-list-item-title>
                  <span v-if="item.created_at == item.updated_at" v-html="MiTiempo(item.created_at)"></span>
                  <span v-else v-html="MiTiempo(item.updated_at)"></span>
                  <v-list-item-subtitle class="font-italic"
                    >{{ item.user }} - pág.{{item.pagina}}</v-list-item-subtitle
                  >
                  <v-list-item-subtitle class="font-weight-medium"
                    v-html="item.contenido"
                  > </v-list-item-subtitle>
                </v-list-item-content>
               
              </v-list-item>
            </template>
          </v-list>

        
    <div v-else v-show="showTodas" width="100%">
  <v-alert
         class="mt-10"
         width="100%"
         elevation="15"
      border="top"
      color="red lighten-2"
      dark
    >
     No hay notas en este archivo
    </v-alert>
      </div>
   

        <v-card color="yellow lighten-4" v-if="showNota" width="100%" class="mt-5 mb-5 mr-5">
          <v-card-actions>
            <v-btn icon small @click.stop="cpOcultarNota()">
              <v-icon>mdi-arrow-left</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn icon small @click.stop="fnIrPagina()">
              <v-icon>mdi-album</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn icon small @click.stop="fnDeleteNota()">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
            <v-btn icon small @click.stop="fnEditNota()">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </v-card-actions>
          <v-card-title class="font-weight-medium">{{ nota.titulo }}</v-card-title>
           <v-card-subtitle class="font-italic" v-html="fnInfoNota()"></v-card-subtitle>
          <v-card-text v-html="nota.contenido"></v-card-text>
        </v-card>

        <v-card color="yellow lighten-4" v-if="showEdit" width="100%" class="mt-5 mb-5 mr-5">
          <v-card-actions>
            <v-btn v-if="tipo == 'N'" icon small @click.stop="fnCancelarNew()">
              <v-icon>mdi-arrow-left</v-icon>
            </v-btn>
            <v-btn success icon small @click.stop="cpOcultarEdit()">
              <v-icon>mdi-check</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <span v-if="tipo == 'N'">Nota en pág. {{nota.pagina}}</span>
          </v-card-actions>
          <v-card-title>
            <v-text-field v-model="nota.titulo" label="Titulo de la Nota">
            </v-text-field>
          </v-card-title>
          <v-card-text>
            <v-textarea
              v-model="nota.contenido"
              label="Contenido de la nota"
            ></v-textarea>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-snackbar v-model="snackbar"  :timeout="3000" :color="snackColor">
      {{ textSnack }} 

      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar = false">
          X
        </v-btn>
      </template>
    </v-snackbar>

    <button v-show="mostrar" :id="idConfig.openFile" type="button">
      openFile
    </button>

    <button
      v-show="mostrar"
      :id="idConfig.presentationMode"
      type="button"
    ></button>

    <button v-show="mostrar" :id="idConfig.print" type="button">print</button>
    <button v-show="mostrar" :id="idConfig.scrollHorizontal" type="button">
      scrollHorizontal
    </button>
    <button v-show="mostrar" :id="idConfig.scrollVertical" type="button">
      scrollVertical
    </button>
    <button v-show="mostrar" :id="idConfig.scrollWrapped" type="button">
      scrollWrapped
    </button>
    <button v-show="mostrar" :id="idConfig.sidebarToggle" type="button">
      sidebarToggle
    </button>
    <button v-show="mostrar" :id="idConfig.spreadEven" type="button">
      spreadEven
    </button>
    <button v-show="mostrar" :id="idConfig.spreadNone" type="button">
      spreadNone
    </button>
    <button v-show="mostrar" :id="idConfig.spreadOdd" type="button">
      spreadOdd
    </button>

    <button v-show="mostrar" :id="idConfig.viewAttachments" type="button">
      viewAttachments
    </button>
    <a v-show="mostrar" :id="idConfig.viewBookmark"></a>
    <button v-show="mostrar" :id="idConfig.viewOutline" type="button">
      viewOutline
    </button>
    <button v-show="mostrar" :id="idConfig.viewThumbnail" type="button">
      viewThumbnail
    </button>
   

     
  </div>
</template>
<script>
import VuePdfApp from "vue-pdf-app";
import "vue-pdf-app/dist/icons/main.css";
export default {
  name: "",
  props: ["pdf"],
  components: {
    VuePdfApp,
  },
  data: () => ({
    hoy: "",
    items: [],

    snackbar: false,
      textSnack: "",
      snackColor: "",

    showTodas: true,
    showNota: false,
    showEdit: false,
    tipo: "",
    nota: {
      idNota: 0,
      avatar: "",
      titulo: "",
      contenido: "",
      created_at: "",
      updated_at: "",
      user: "",
      idUsr: 0,
      pagina: 0,
      idArchivo: 0,
    },
    pages: {
      total: 0,
      actual: 0,
    },
    toggle_exclusive: 1,
    colsColumn: {
      xl: 2,
    },
    drawer: false,
    mini: true,
    mostrar: false,
    pagina: 1,
    config: {
      secondaryToolbar: false,
      sidebar: false,
    },
    idConfig: {
      cursorHandTool: "cursorHandToolId", // <button> is recommended
      cursorSelectTool: "cursorSelectToolId", // <button> is recommended
      documentProperties: "documentPropertiesId", // <button> is recommended
      download: "downloadId", // <button> is recommended
      /*findbar: "findbarId", //string, <div> is recommended
            findEntireWord: "findEntireWordId", // string; // <input type="checkbox"> is recommended
            findHighlightAll: "findHighlightAllId", //string; // <input type="checkbox"> is recommended
            findMessage: "findMessageId", // string; // <span> is recommended
            findInput: "findInputId", // string; // <input type="text"> is recommended
            findMatchCase: "findMatchCaseId", // string; // <input type="checkbox"> is recommended
            findNext: "findNextId", //string; // <button> is recommended
            findPrevious: "findPreviousId", //string; // <button> is recommended
            findResultsCount: "findResultsCountId", // string; // <span> is recommended*/

      firstPage: "firstPageId", // string; // <button> is recommended
      lastPage: "lastPageId", // string; // <button> is recommended
      nextPage: "nextPageId", //string; // <button> is recommended
      numPages: "numPagesId", // string; // total pages qty. <span> is recommended
      openFile: "openFileId", // string; // <button> is recommended
      pageNumber: "pageNumberId", // string; // input for page number. <input type="number"> is recommended
      pageRotateCcw: "pageRotateCcwId", // string; // <button> is recommended
      pageRotateCw: "pageRotateCwId", // string; // <button> is recommended
      presentationMode: "presentationModeId", // string; // <button> is recommended
      previousPage: "previousPageId", // string; // <button> is recommended
      print: "printId", //string; // <button> is recommended
      scrollHorizontal: "scrollHorizontalId", // string; // <button> is recommended
      scrollVertical: "scrollVerticalId", // string; // <button> is recommended
      scrollWrapped: "scrollWrappedId", // string; // <button> is recommended
      sidebarToggle: "sidebarToggleId", // string; // <button> is recommended
      spreadEven: "spreadEvenId", //string; // <button> is recommended
      spreadNone: "spreadNoneId", //string; // <button> is recommended
      spreadOdd: "spreadOddId", // string; // <button> is recommended
      //toggleFindbar: "toggleFindbarId", // string; // <button> is recommended
      viewAttachments: "viewAttachmentsId", // string; // <button> is recommended
      viewBookmark: "viewBookmarkId", // string; // <a> tag is recommended
      viewOutline: "viewOutlineId", // string; // <button> tag is recommended
      viewThumbnail: "viewThumbnailId", //string; // <button> tag is recommended*/
      zoomIn: "zoomInId", //string; // <button> tag is recommended
      zoomOut: "zoomOutId", //string; // <button> tag is recommended
    },
  }),
  mounted() {
    this.fnTraeNotas();
  },
  created() {},
  beforeMount() {},
  watch: {},
  computed: {},
  methods: {
    fnTraePDF(){
      return ("/archivos/" + this.pdf.idDocumento + "-" + this.pdf.idRelArDoc + ".pdf");
    },
    fnTraeNotas(){
      
      axios
        .get("/notasarchivo/" + this.pdf.idRelArDoc)
        .then((res) => {
          
          this.items = res.data;
          //console.log(this.items.length);
          this.nota.idArchivo = this.pdf.idRelArDoc;
      })
      .catch((error) => {
      });
    },
    fnCreaNota() {
      var page = document.getElementById("pageNumberId").value;
      this.tipo = "N";
      this.showTodas = false;
      this.showNota = false;
      this.nota.titulo = "";
      this.nota.pagina = page;
      this.nota.contenido = "";
      this.nota.created_at = "";
      this.nota.user = "";
      this.nota.idUsr = "";
      this.showEdit = true;
      //console.log(page);
    },
    fnEntraNota(item) {
      //console.log(item);
      this.tipo = "E";
      this.nota.avatar = item.avatar;
      this.nota.idNota = item.idNota;
      this.nota.titulo = item.titulo;
      this.nota.pagina = item.pagina;
      this.nota.contenido = item.contenido;
      this.nota.created_at = item.created_at;
      this.nota.updated_at = item.updated_at;
      this.nota.user = item.user;
      this.nota.idUsr = item.idUsrAlta;
      this.showTodas = false;
      this.showNota = true;
    },
    fnEditNota() {
      this.showNota = false;
      this.showEdit = true;
    },
    fnInfoNota(){
      var creado = this.nota.created_at;
      var creadoLargo = this.cFechaYMDlarga(creado);
      var modificado = this.nota.updated_at;
      var LcResp = "";
      //console.log(creado);
      //console.log(modificado);
      if(modificado == creado){
        
        var LcResp = "Creado por " + this.nota.user + " " + this.MiTiempo(creado) + " el " + creadoLargo;
        return LcResp;
      }   
      
      var modificadoLargo = this.cFechaYMDlarga(modificado);
      var LcResp = "Creado por " + this.nota.user + ", última modifcación " + this.MiTiempo(modificado) + ", creado el " + modificadoLargo;
      return LcResp;
      
      
    },
    fnIrPagina() {
      //document.getElementById("pageNumberId").focus();
      var inputPage = document.getElementById("pageNumberId");
      inputPage.value = this.nota.pagina;
      //$("#pageNumberId").trigger({type: 'keypress', which: 13, keyCode: 13});

/*
      const ke = new KeyboardEvent("keydown", {
          bubbles: true, cancelable: true, keyCode: 13
      });
      document.getElementById("pageNumberId").dispatchEvent(ke);*/


      /*
      $(document).ready(function () {
        // Store the input field as a variable
        // NOTE :: Change "inputID" to your input field's ID
        var input = document.getElementById("pageNumberId");

        // If the input field exists
        if ($(input).length > 0) {
          // Add a listener for the release of a key
          input.addEventListener("keyup", function (event) {
            // Prevent the default action, just in case
            event.preventDefault();
            // When the "Enter" key is released
            // NOTE :: The number 13 is the keyboard's "Enter" key
            if (event.keyCode === 13) {
              // Simulate a click on the button
              // NOTE :: Change "buttonID" to your button's ID
              document.getElementById("buttonID").click();
            }
          });
        }
      });*/
    },
    fnDeleteNota() {
      axios
        .get("/dnota/")
        .then((res) => {
      })
      .catch((error) => {
      });
    },
    cpOcultarNota() {
      this.showTodas = true;
      this.showNota = false;
    },
    cpOcultarEdit() {
      if (this.tipo == "N") {
        
        axios
          .post("/rnota", this.nota)
          .then((res) => {
            //console.log(res.data);
            this.nota.avatar = this.$store.state.info.avatar;
            this.nota.idNota = res.data;
            this.nota.created_at = this.Hoy();
            this.nota.updated_at = this.Hoy();
            this.nota.user = this.$store.state.info.user;
            this.nota.idUsr = this.$store.state.info.Cve;
            //console.log(this.nota);
           
        })
        .catch((error) => {
        });
        
        this.items.push(this.nota);
        this.snackbar = true;
        this.snackColor = "success";
        this.textSnack = "La nota fue agregada";

        this.showTodas = true;
        this.showEdit = false;
        //console.log(this.items);
        return;
      }
      
      var itemLength = this.items.length;
      itemLength = itemLength - 1;
      for(var i = 0; i <= itemLength; i++){
        if(this.items[i].idNota == this.nota.idNota){
          if(this.items[i].titulo == this.nota.titulo){
            if(this.items[i].contenido == this.nota.contenido){
              this.snackbar = true;
              this.snackColor = "info";
              this.textSnack = "No hubo cambios en la nota";
              ////console.log("No hubo cambios");
              this.showNota = true;
              this.showEdit = false;
              return;
            }
          }

          axios
            .post("/enota", this.nota)
            .then((res) => {
              if(res.data == 0){
                alert("No tiene permisos para editar esta nota");
                return;
              }
              
          })
          .catch((error) => {
          });

          this.snackbar = true;
          this.snackColor = "success";
          this.textSnack = "La nota fue actualizada";
          this.nota.updated_at = this.Hoy();
          this.items[i].titulo = this.nota.titulo;
          this.items[i].contenido = this.nota.contenido;
          this.items[i].updated_at = this.Hoy();
          
        }
      }

      this.showNota = true;
      this.showEdit = false;
    },
    fnCancelarNew() {
      this.showTodas = true;
      this.showEdit = false;
    },
    fnOrdenarXPags(){
      
       this.items.sort((a, b) => a.pagina - b.pagina);
     
    },
    fnOrdenarXFecha(){
      
      this.items.sort(function(a,b){ // Turn your strings into dates, and then subtract them // to get a value that is either negative, positive, or zero. 
      return new Date(b.updated_at) - new Date(a.updated_at); });

      //this.items.sort((a, b) => new Date(a.created_at).getTime() < new Date(b.created_at).getTime());
    },

    OpenPDF(pdfApp) {

      
    },
    afterCreatedHandler(pdfApp) {},
  },
};
</script>

<style type="text/css">
barrasO {
  overflow: hidden;
}
</style>
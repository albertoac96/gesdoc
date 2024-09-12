<template>
  <v-dialog v-model="dlgNuevoDoc" width="500" persistent>
    <v-card>
      <v-card-title class="text-h5 grey lighten-2">
        Crear registro de documento digital
      </v-card-title>

      <v-card-text>
        <v-card-subtitle>
          {{ msgAlert }}
        </v-card-subtitle>

        <v-combobox
          v-model="doc.TipoDoc"
          :items="cboTipoDoc"
          label="Tipo de documento"
          @change="fnVeTipoDoc()"
        >
          <template slot="selection" slot-scope="data">
            <span
              ><v-icon>{{ data.item.cIcono }}</v-icon>
              {{ data.item.text }}</span
            >
          </template>
          <template slot="item" slot-scope="data">
            <span
              ><v-icon>{{ data.item.cIcono }}</v-icon>
              {{ data.item.text }}</span
            >
          </template>
        </v-combobox>

        <v-combobox
          v-if="
            doc.TipoDoc.value == 4 ||
            doc.TipoDoc.value == 14 ||
            doc.TipoDoc.value == 16 ||
            doc.TipoDoc.value == 1 ||
            doc.TipoDoc.value == 15
          "
          v-model="doc.Periodica"
          :items="cboPeriodica"
          :label="lblCboPeriodica"
        ></v-combobox>

        <v-text-field
          v-if="
            doc.TipoDoc.value == 1 ||
            doc.TipoDoc.value == 2 ||
            doc.TipoDoc.value == 3 ||
            doc.TipoDoc.value == 15
          "
          v-model="doc.cIDWeb"
          :label="lblWebID"
          append-outer-icon="mdi-card-search"
          @click:append-outer="BuscarIDWeb()"
        ></v-text-field>

        <v-text-field
          v-if="doc.TipoDoc != ''"
          v-model="doc.titulo"
          label="Titulo"
        ></v-text-field>

        <span> {{ lblInfo }} </span>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red" text @click="$emit('close')"> Cancelar </v-btn>
        <v-btn color="primary" text @click="fnCreaDoc()">
          Crear Documento
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "dlgNuevoDoc",
  props: [],
  components: {},
  data: () => ({
    dlgNuevoDoc: true,
    msgAlert: "",
    doc: {
      idCarpeta: 0,
      TipoDoc: "",
      cIDWeb: "",
      titulo: "",
      Periodica: "",
      info: "",
      typePeriodica: "",
    },
    cboTipoDoc: [],
    cboPeriodica: [],
    lblWebID: "",
    lblInfo: "",
    lblCboPeriodica: "",
  }),
  mounted() {
    var URLactual = window.location;
    var ruta = URLactual.pathname;
    var posSlash = ruta.lastIndexOf("/");
    var nameRuta = ruta.slice(1, posSlash);
    if (nameRuta != "biblio") {
      this.msgAlert =
        "El documento se creará en la carpeta base 'Mi biblioteca'. Si quiere crearlo en otra ubicación vaya a la carpeta destino en el panel de carpetas del lado izquierdo";
      this.doc.idCarpeta = 0;
    } else {
      this.doc.idCarpeta = ruta.slice(posSlash + 1);
      axios
          .get("/nombrede/" + this.doc.idCarpeta)
          .then((res) => {
             this.msgAlert = "Crear documento en: " + res.data;
        })
        .catch((error) => {
        });
      
    }
    axios
      .get("/cbotipodoc")
      .then((res) => {
        this.cboTipoDoc = res.data;
      })
      .catch((error) => {});
  },
  created() {},
  beforeMount() {},
  watch: {
    "doc.TipoDoc"(newVal) {
      if (newVal.value == 2 || newVal.value == 3) {
        this.lblWebID = "Número ISBN de la publicación";
      }
      if (newVal.value == 1 || newVal.value == 15) {
        this.lblWebID = "Número DOI del artículo";
        this.lblCboPeriodica  = "Elige una revista";
        this.fnTraePeriodica(newVal.value);
      }
      if (newVal.value == 14) {
        this.lblWebID = "Número ISSN de la memoria";
        this.lblCboPeriodica  = "Elige una memoria";
        this.fnTraePeriodica(newVal.value);
      }
      if (newVal.value == 16) {
        this.lblWebID = "Número ISSN de la revista";
        this.lblCboPeriodica  = "Elige una revista";
        this.fnTraePeriodica(newVal.value);
      }
      if (newVal.value == 4) {
        this.lblCboPeriodica  = "Elige un periódico";
        this.fnTraePeriodica(newVal.value);
      }

    },
    "doc.Periodica"(newVal){
      var type = typeof newVal;
      ////console.log(type);
    }
  },
  computed: {},
  methods: {
    fnVeTipoDoc() {
      this.lblWebID = "";
      this.lblInfo = "";
      this.doc.titulo = "";
      this.doc.cIDWeb = "";
      this.doc.Periodica = "";
      this.doc.info = "";
      this.doc.typePeriodica = "";
    },
    fnTraePeriodica(idTipoDoc){
      axios
        .get("/perindex/" + idTipoDoc)
        .then((res) => {
          this.cboPeriodica = res.data;
      })
      .catch((error) => {
      });
    },
    BuscarIDWeb() {
      if (this.doc.TipoDoc.value == 2 || this.doc.TipoDoc.value == 3) {
        //Google Books

        this.traeGoogle();
      }
      if (this.doc.TipoDoc.value == 1 || this.doc.TipoDoc.value == 15) {
        //CrossRef
        this.traeCrossRefWork();
      }
      if (this.doc.TipoDoc.value == 14 || this.doc.TipoDoc.value == 16) {
        //CrossRef
        this.traeCrossRefJournal();
        
      }
    },
    traeCrossRefWork(){
        var book = 0;
      var cDOI = this.doc.cIDWeb;
      if (cDOI == "") {
       this.$store.state.snack.model = true;
            this.$store.state.snack.color = "error";
            this.$store.state.snack.text =
              "Proporcione un número DOI";
        return;
      }
      
      const url = "https://api.crossref.org/v1/works/" + cDOI;
       this.$store.state.overlay = true;

      axios
        .get(url)
        .then((res) => {
         // //console.log(res);
          var art = res.data.message;
          this.doc.info = art;

          if (art.type == "reference-book") {
            book = 1;
            this.$store.state.snack.model = true;
            this.$store.state.snack.color = "error";
            this.$store.state.snack.text =
              "Es un libro";
            this.doc.cIDWeb = art.ISBN[0];
            this.cboTipoDoc = {
              text: "Libro",
              value: 1,
            };
          }

          var title = art.title[0];
          var subtitle = art.subtitle[0];
          if (subtitle == undefined) {
            var titulo = title;
          } else {
            var titulo = title + " " + subtitle;
          }
          

         // //console.log("años");
          if (art.hasOwnProperty("published-print")) {
            var anyoP = art["published-print"]["date-parts"];
            anyoP = anyoP.join();
            anyoP = anyoP.substring(0, 4);
          } else {
            var anyoP = undefined;
          }

          if (art.hasOwnProperty("published-online")) {
            var anyoO = art["published-online"]["date-parts"];
            anyoO = anyoO.join();
            anyoO = anyoO.substring(0, 4);
          } else {
            var anyoO = undefined;
          }

          if (anyoP != undefined) {
            var Anyo = anyoP;
          } else {
            var Anyo = anyoO;
          }
        

          if (book == 0) {
            var revista = art["container-title"];

            revista = revista.join(); //Convierte a string el array

            this.revisaRevistas(revista);

           
          }


          var authors = art["author"];
          var length = authors.length - 1;
         // //console.log("autores2");
        var cAutores = "";
          for (var contador = 0; contador <= length; contador++) {
            var name = authors[contador].given;
            var apellido = authors[contador].family;
            var autor = name + " " + apellido;
            cAutores = cAutores + autor + ", ";
            
                 
                }
                cAutores = cAutores.slice(0, -2);

                
              if (book == 1) {
              this.doc.titulo = titulo;
              if (authors.length == 1) {
                this.lblInfo =
                  "Del autor: " + cAutores + "; publicado en: " + publishedDate;
              } else {
                this.lblInfo = "De los autores: " + cAutores + "; publicado en: " + publishedDate;
              }
            } else {
              
              this.doc.titulo = titulo;
              

              if (authors.length == 1) {
                this.lblInfo = "En la revista: " +
                  revista +
                  "; del autor: " +
                  cAutores +
                  "; publicado en: " +
                  Anyo;
              } else {
                this.lblInfo =
                  "En la revista: " +
                  revista +
                  "; de los autores: " +
                  cAutores +
                  "; publicado en: " +
                  Anyo;
              }
            }

            this.$store.state.overlay = false;
            
        

        })
        .catch((error) => {
          var errors = error.response;

          
        });
         
    },
    traeCrossRefJournal(){

      var cISSN = this.doc.cIDWeb;
      if (cISSN == "") {
       this.$store.state.snack.model = true;
            this.$store.state.snack.color = "error";
            this.$store.state.snack.text =
              "Ingrese un número ISSN";
        return;
      }
      
      const url = "https://api.crossref.org/v1/journals/" + cISSN;
       this.$store.state.overlay = true;

      axios
        .get(url)
        .then((res) => {

         

          var journal = res.data.message;
          this.doc.info = journal;
          this.doc.titulo = journal.title;

      })
      .catch((error) => {
        var errors = error.response;
         if(errors){
            this.$store.state.snack.model = true;
            this.$store.state.snack.color = "error";
            this.$store.state.snack.text =
              "No se encontro el ISSN en CrossRef";
        return;
          }
      });

    },
    revisaRevistas(revista){
            

            var oRevistas = this.cboPeriodica;
            var nRevistas = oRevistas.length - 1;
            var existeR = 0;
            ////console.log(oRevistas);
            for (var iR = 0; iR <= nRevistas; iR++) {
              if (oRevistas[iR].text == revista) {
                ////console.log(oRevistas[iR].text);
                this.doc.Periodica = {
                  text: oRevistas[iR].text,
                  value: oRevistas[iR].value,
                };
                existeR = 1;
              }
            }
            if (existeR == 1) {
               this.$store.state.snack.model = true;
            this.$store.state.snack.color = "success";
            this.$store.state.snack.text =
              "La revista ya esta registrada";
            return;
            } else {
              
              this.$store.state.snack.model = true;
            this.$store.state.snack.color = "info";
            this.$store.state.snack.text =
              "La revista se registrará cuando cree el documento";
              
              this.doc.Periodica = revista;
            }
    },
    traeGoogle() {
      var isbn = this.doc.cIDWeb;
      if (isbn == "") {
        this.$store.state.snack.color = "error";
        this.$store.state.snack.text = "Ingrese un ISBN";
        this.$store.state.snack.model = true;
        return;
      }
      const url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
      axios
        .get(url)
        .then((res) => {
          this.$store.state.overlay = true;
          var items = res.data.totalItems;

          if (items == 0) {
            this.$store.state.snack.model = true;
            this.$store.state.snack.color = "error";
            this.$store.state.snack.text =
              "No se encontro el ISBN en Google Books";
          } else {
            var book = res.data.items[0];
            this.doc.info = book;

            var title = book.volumeInfo.title;
            var subtitle = book["volumeInfo"]["subtitle"];

            var authors = book["volumeInfo"]["authors"]; //Autores del isbn
            var length = authors.length - 1; //numero de autores del isbn
            var cAutores = "";

            for (var contador = 0; contador <= length; contador++) {
              var cNameAutor = authors[contador];
              cAutores = cAutores + cNameAutor + ", ";
            }

            cAutores = cAutores.slice(0, -2);

            var publishedDate = book["volumeInfo"]["publishedDate"];
            publishedDate = publishedDate.substring(0, 4);

            if (this.doc.TipoDoc.value == 3) {
              if (subtitle == undefined) {
                this.doc.titulo = title;
              } else {
                this.doc.titulo = title + " " + subtitle;
              }
              if (authors.length == 1) {
                this.lblInfo =
                  "Del autor: " + cAutores + "; publicado en: " + publishedDate;
              } else {
                this.lblInfo = "De los autores: " + cAutores + "; publicado en: " + publishedDate;
              }
            } else {
              var tituloLibro = "";
              if (subtitle == undefined) {
                tituloLibro = title;
              } else {
                tituloLibro = title + " " + subtitle;
              }

              if (authors.length == 1) {
                this.lblInfo = "Del libro: " +
                  tituloLibro +
                  "; del autor: " +
                  cAutores +
                  "; publicado en: " +
                  publishedDate;
              } else {
                this.lblInfo =
                  "Del libro: " +
                  tituloLibro +
                  "; de los autores: " +
                  cAutores +
                  "; publicado en: " +
                  publishedDate;
              }
            }

            this.$store.state.overlay = false;
          }
        })
        .catch((error) => {
          var errors = error.response;

          if (errors.status === 404) {
          }
        });
    },
    fnCreaDoc(){

     

      if(this.doc.titulo == ""){
        this.$store.state.snack.text = "Proporciona un título del documento";
          this.$store.state.snack.color = "info";
          this.$store.state.snack.centered = true;
          this.$store.state.snack.model = "true";
          return;
      }

      if(this.doc.TipoDoc.value == 1 || this.doc.TipoDoc.value == 4 || this.doc.TipoDoc.value == 14 || this.doc.TipoDoc.value == 15 || this.doc.TipoDoc.value == 16 || this.doc.TipoDoc.value == 17){
       
        if(this.doc.Periodica != ""){
        
        
        var type = typeof this.doc.Periodica;
        this.doc.typePeriodica = type;
      } else {
        
          this.$store.state.snack.text = "Elige una publicación o escribe una";
          this.$store.state.snack.color = "info";
          this.$store.state.snack.centered = true;
          this.$store.state.snack.model = "true";
          return;
        }
      }

      

    
      
     
      axios
        .post("/uploaddoc", this.doc)
        .then((res) => {
          //console.log(res.data);
          var node = { id: res.data };
          var ruta = { name: "veritem", params: node };

          this.$router.push(ruta);
          this.$emit('close');
      })
      .catch((error) => {
      });
    }
  },
};
</script>
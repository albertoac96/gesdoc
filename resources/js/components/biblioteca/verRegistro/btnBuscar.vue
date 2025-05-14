<template>
  <v-menu offset-y>
    <template #activator="{ on: onMenu }">
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn icon v-on="{ ...onMenu, ...onTooltip }">
            <v-icon>mdi-magnify</v-icon>
          </v-btn>
        </template>

        <span>Buscar</span>
      </v-tooltip>
    </template>

    <v-list>
      <v-list-item link @click="fnEnlaceTitulo()">
        <v-list-item-icon><v-icon>mdi-google</v-icon></v-list-item-icon>
        <v-list-item-title >Buscar por titulo</v-list-item-title>
      </v-list-item>
      <v-list-item link @click="fnEnlaceWebID()">
        <v-list-item-icon><v-icon>mdi-google</v-icon></v-list-item-icon>
        <v-list-item-title>Buscar por ID bibliografico</v-list-item-title>
      </v-list-item>
      <v-list-item link @click="fnEnlaceLibGen()">
        <v-list-item-icon><v-icon>mdi-library-shelves</v-icon></v-list-item-icon>
        
        <v-list-item-title>Buscar en LibGen</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
<script>
export default {
  name: "",
  props: ["titulo", "atrs"],
  components: {},
  data: () => ({
      enlace: ""
  }),
  mounted() {},
  created() {},
  beforeMount() {},
  watch: {},
  computed: {},
  methods: {
      fnEnlaceTitulo(){
        var titulo = this.titulo.replace(/ /g,'+');  
        this.fnLlevaA(titulo);
      },
      fnEnlaceWebID(){
          var WebID = "";
          var tipo = "";
          var atributos = this.atrs;
         var length = atributos.length - 1;
          for (var contador = 0; contador <= length; contador++) {
            var idAtributo = atributos[contador].idAtributo;
            if (idAtributo == 11) { //Tiene un ISBN
              WebID = atributos[contador].cValor;
              tipo = "ISBN"
            } 
            if (idAtributo == 47) { //Tiene un DOI
              WebID = atributos[contador].cValor;
              tipo = "DOI"
            }
          }
          if(WebID == ""){
              //console.log("No hay WEB ID registrado en sus atributos");
          } else {
              WebID = WebID.replace(/ /g,'+');
              WebID = WebID + "+" + tipo;
              this.fnLlevaA(WebID);
          }
      },
      fnEnlaceLibGen(){
        var titulo = this.titulo.replace(/ /g,'+');  
        var url = "https://libgen.is/search.php?req=" + titulo +"&open=0&res=25&view=simple&phrase=1&column=def";
        var win = window.open(url, '_blank');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
      },
      fnLlevaA(texto){
        var url = "https://www.google.com/search?q=";
        var win = window.open(url + texto, '_blank');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
      }
  },
};
</script>
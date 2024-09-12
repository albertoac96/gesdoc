<template>
  <div  v-if="$canBtn('agregar', 'No tiene permisos para agregar')" class="text-center">
    <v-menu offset-y v-if="!mini">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          rounded
          color="primary"
          dark
          x-large
          outlined
          v-bind="attrs"
          v-on="on"
        >
          <v-icon left> mdi-plus </v-icon>
          Nuevo
        </v-btn>
      </template>
      <v-list>
        <v-list-item link @click="dlgNuevoDoc = true">
          <v-list-item-title>Documento</v-list-item-title>
        </v-list-item>
        <v-list-item link @click="dlgNuevaCarpeta = true">
          <v-list-item-title>Carpeta</v-list-item-title>
        </v-list-item>
        <v-list-item link @click="dlgSubirArchivo = true">
          <v-list-item-title>Subir Archivo</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
    <dlgSubirArchivo v-if="dlgSubirArchivo" tipo="soloACarpeta" @info="newArchivo($event)" @close="dlgSubirArchivo=false"></dlgSubirArchivo>
    <dlgNuevoDoc v-if="dlgNuevoDoc" @close="dlgNuevoDoc=false"></dlgNuevoDoc>
    <dlgNuevaCarpeta v-if="dlgNuevaCarpeta" @close="dlgNuevaCarpeta=false"></dlgNuevaCarpeta>
  </div>
</template>

<script>

import dlgSubirArchivo from '../../dialogos/subirArchivo.vue';
import dlgNuevoDoc from '../../dialogos/dlgNuevoDoc.vue';
import dlgNuevaCarpeta from "../../dialogos/dlgNuevaCarpeta.vue";

export default {
  name: "botonNew",
  props: ["mini"],
  components:{
    dlgSubirArchivo,
    dlgNuevoDoc,
    dlgNuevaCarpeta
  },
  data: () => ({
    dlgSubirArchivo: false,
    dlgNuevoDoc: false,
    dlgNuevaCarpeta:false,
    items: [
      { title: "Documento" },
      { title: "Carpeta" },
      { title: "Subir archivo" },
    ],
  }),
  methods:{
    newArchivo(info){
      var node = { cDocumento: info.cNombre, id: info.idDocumento };
      var ruta = { name: "veritem", params: node };
      this.$router.push(ruta);
      this.dlgSubirArchivo = false;
    }
  }
};
</script>
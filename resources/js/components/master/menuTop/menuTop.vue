<template>
  <v-app-bar color="blue darken-4" app >


    
      <buscador></buscador>

    <v-spacer></v-spacer>
    <perfil></perfil>


   



  </v-app-bar>
</template>
<script>
import buscador from './buscador.vue';
import perfil from './menuPerfil.vue';
export default {
  name: "menuTop",
  components:{
      buscador,
      perfil
  }
  
};
</script>
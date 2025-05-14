<template>
  <v-app>
    <navDrawer></navDrawer>

    <menuTop></menuTop>

    <!-- Sizes your content based upon application components -->
    <v-main>
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view></router-view>
        <UploadProgress v-if="$store.state.UploadProgress.infoArchivos.length > 0"></UploadProgress>
        <snack></snack>

       


      </v-container>
    </v-main>

    <v-footer app>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
import navDrawer from "./barra/navDrawer.vue";
import menuTop from "./menuTop/menuTop.vue";
import UploadProgress from "../dialogos/UploadProgress.vue"
import snack from '../dialogos/snack.vue'
export default {
  name: "app-vue",
  components: {
    navDrawer,
    menuTop,
    UploadProgress,
    snack
  },
  mounted() {
   
  },
};
</script>

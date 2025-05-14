<template>
  <v-card>
    
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant.sync="mini"
      permanent
      app
      width="350"
      color="blue-grey lighten-5"
    >
     <v-list-item class="px-2" >
        <!--<v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
        </v-list-item-avatar>-->

        <v-list-item-title v-if="!mini">GesDoc</v-list-item-title>

        <v-btn icon @click.stop="mini = !mini">
          <v-icon v-if="!mini">mdi-chevron-left</v-icon>
           <v-icon v-if="mini">mdi-dots-vertical</v-icon>
        </v-btn>
      </v-list-item>

      <v-divider></v-divider>
      <botonNew class="mb-2" :mini="mini" />
      <carpetas :mini="mini"></carpetas>
       <v-divider></v-divider>
        <menuItems />
     
    </v-navigation-drawer>
  </v-card>
</template>
<script>
import menuItems from './menuItems.vue';
import carpetas from './carpetas.vue';
import botonNew from './botonNew.vue';
export default {
    
  name: "navDrawer",
  components: {
      menuItems,
      carpetas,
      botonNew
  },
 data: () => ({
      drawer: true,
      mini: false,
 }),
};
</script>
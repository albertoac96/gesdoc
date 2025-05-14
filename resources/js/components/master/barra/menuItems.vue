<template>
  <v-list nav dense>
    <v-list-group
   
      v-for="item in menu"
      :key="item.idMenu"
      v-model="item.active"
      :prepend-icon="item.cLogo"
      no-action
      :to="item.cURL"
    
      
    >
      <template v-slot:activator>
        <v-list-item-content  >
          <v-list-item-title v-text="item.cNombre" ></v-list-item-title>
        </v-list-item-content>
      </template>

      <v-list-item
      
        v-for="child in item.children"
        :key="child.idMenu"
        link
        :to="child.cURL"
        
      >
        <v-list-item-content>
          <v-list-item-title v-text="child.cNombre" ></v-list-item-title>
        </v-list-item-content>
        <v-list-item-icon>
          <v-icon v-text="child.cLogo"></v-icon>
        </v-list-item-icon>
      </v-list-item>
    </v-list-group>
  </v-list>
</template>
<script>
export default {
  name: "menuItems",
  data: () => ({
    selectedItem: 0,

    menu: [],
  }),
  mounted() {
    axios.get("/traemenu").then((res) => {
      this.menu = res.data;
    });
  },
  methods: {
    menuItem() {
      //console.log("dio click");
    },
  },
};
</script>
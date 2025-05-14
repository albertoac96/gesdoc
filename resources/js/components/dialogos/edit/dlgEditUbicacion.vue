<template>
 <v-dialog v-model="dialog" max-width="800" persistent>
    <v-card>
      <v-card-title class="text-h5 grey lighten-2">
        Mover documento
      </v-card-title>

      <v-card-text>

        Eliga la carpeta destino 

          
          <v-treeview
            
            dense
           
            :items="carpetas"
            item-key="idCarpeta"
            item-text="cCarpeta"
            color="warning"
            rounded
    hoverable
    activatable
   @update:active="getActiveValue"
           
          >
            <template v-slot:prepend="{ item, open }">
              <v-icon v-if="!item.file">
                {{ open ? "mdi-folder-open" : "mdi-folder" }}
              </v-icon>
            </template>

           
          </v-treeview>
        
        
      </v-card-text>
      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" text @click="$emit('close')"> Cancelar </v-btn>
        <v-btn color="success" text @click="Guardar()"> Mover </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
    name: "cambiaUbicacion",
    props: ["idDoc"],
    components:{

    },
    data: () => ({
         tree: [],
      active:[],
      open:[0],
       initiallyOpen: ['0'],
      show: false,
       dialog: true,
        carpetas: [],
    }),
    mounted(){
       axios.get("/traecarpetas", 0).then((res) => {
            this.carpetas = res.data;
          
            
        });
    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{
        
    },
    computed:{
   
    },
    methods:{
      Guardar(){
          let InstFormData = new FormData();

            

            InstFormData.append('idDoc' , this.idDoc);
            InstFormData.append('carpeta' , this.tree);
              axios
              .post("/movedoc", InstFormData)
              .then((res) => {
                console.log(res.data);
                this.$emit('newUbi', res.data);
                this.$emit('close');
              })
              .catch((error) => {});

      },
      getActiveValue(value){
          this.tree = value;
       
     }
    }
}
</script>
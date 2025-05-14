<template>
<div>
<v-icon class="ml-4" v-if="mini" color="orange darken-2">
mdi-library

</v-icon>

  <v-treeview
    v-if="!mini"
    dense
   
    v-model="tree"
  
    :items="carpetas"
   
    item-key="idCarpeta"
    item-text="cCarpeta"
    color="warning"
    hoverable
    :open.sync="open"
    :active.sync="active"
    
   
    
   
    
    
  >
    <template v-slot:prepend="{ item, open }" >
      <v-icon v-if="!item.file">
        {{ open ? "mdi-folder-open" : "mdi-folder" }}
      </v-icon>
      
      
    </template>


<template slot="label" slot-scope="{ item }" >

      

     
        <a @click="SelectedNode(item)">{{ item.cCarpeta }}</a>
      


    </template>

 

  </v-treeview>
</div>

</template>
<script>
export default {
    name:"carpetas",
    props: ['mini'],
    data: () => ({
      initiallyOpen: ['public'],
      show: false,
     
      tree: [],
      active:[],
      open:[],
    
   

carpetas: []



    }),
    mounted(){
        var idPadre = 0;
        axios.get("/traecarpetas", idPadre).then((res) => {
            this.carpetas = res.data;
            //this.$store.state.carpetas = res.data;
            
        });
        ////console.log(this.mini);
    },
    watch:{
      '$store.state.iNewCarpeta'(newVal){
        var idPadre = 0;
        axios.get("/traecarpetas", idPadre).then((res) => {
            this.carpetas = res.data;
            //this.$store.state.carpetas = res.data;
            
        });
      }
    },
    methods:{

        SelectedNode(node){
           
      ////console.log(node);
     
        var ruta = { name: 'biblioteca', params: node };
      
     
      
      
      this.$router.push(ruta);
        }
    }
}
</script>
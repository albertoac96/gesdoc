<template>
<v-card>
  <v-card-title>
    
    <v-toolbar-title>Asociar atributos</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-combobox
            v-model="TipoDoc"
            :items="TiposDoc"
            item-value="idTipoDoc"
            item-text="cTipoDoc"
            label="Tipo de documento"
            @change="TraeAtributos()"
            outlined
            dense
        ></v-combobox>
   
    <v-col cols="12">
       <v-spacer></v-spacer>
      <v-btn v-if="$canBtn('editar', 'No tiene permisos para agregar')">Asignar atributos</v-btn>
    </v-col>
  </v-card-title>
  <v-card-text>
    <v-data-table v-if="mostrar != 0"
      v-model="selected"
      :headers="headers"
      :items="desserts"
      item-key="idAtributo"
      show-select
      class="elevation-1"
      checkbox-color="orange"
    >
      
    
    </v-data-table>
    <v-alert  border="top"
      color="blue-grey"
      dark v-else >Eliga un tipo de documento para ver o editar sus atributos</v-alert>
  </v-card-text>
</v-card>
</template>
<script>
export default {
    name: "asociaAtrs",
    props: [],
    components:{

    },
    data: () => ({
        TipoDoc: [],
        TiposDoc: [],
        selected: [],
         headers: [
        {
          text: '',
          align: 'start',
          sortable: false,
          value: 'idAtributo',
        },
        { text: 'Atributo', value: 'cAtributo' },
      ],
      desserts: [],
      mostrar: 0,
    }),
    mounted(){
       this.$can('/asociaatrs', 'No tiene permisos para ver esta pantalla')
    },
    created(){
        this.initialize();
    },
    beforeMount(){

    },
    watch:{
        
    },
    computed:{
   
    },
    methods:{
       initialize () {
        axios
             .get("/atrshowaso")
             .then((res) => {
               //console.log(res.data);
              this.desserts = res.data.atributos;
              this.TiposDoc = res.data.docs;
        })
             .catch((error) => {
        });
      },
      TraeAtributos(){
        this.mostrar = 1;
         axios
              .get("/atrtipo/" + this.TipoDoc.idTipoDoc)
              .then((res) => {
                 this.selected = res.data;
              })
              .catch((error) => {
         });
      }
    }
}

</script>
<style>
.v-data-table__selected{
  background-color: orange;
}
</style>
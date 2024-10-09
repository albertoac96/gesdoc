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
           
            <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
       
       
      </v-card-title>
      <v-card-text>
        <v-data-table
          v-model="selected"
          :headers="headers"
          :items="desserts"
          :search="search"
          item-key="idAutor"
          show-select
          class="elevation-1"
          checkbox-color="orange"
          @dblclick:row="onRowDblClick"
        >
          
        
        </v-data-table>
       
      </v-card-text>
    </v-card>
    </template>
    <script>
    export default {
        name: "verAutores",
        props: [],
        components:{
    
        },
        data: () => ({
            headers: [
                { text: 'id', value: 'idAutor' },
                { text: 'Nombre', value: 'cFirstNombre' },
                { text: 'Apellido', value: 'cApellido' },
            ],
            desserts: [],
            search: '',
            selected: []

        }),
        mounted(){
            this.initialize();
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
            initialize(){
                axios
             .get("/autores/show")
             .then((res) => {
                this.desserts = res.data;
             })
             .catch((error) => {
        });
            },
            onRowDblClick(event, { item }) {
                // Maneja el evento de doble clic aquí
                console.log(item);
                this.$router.push({ name: 'verautor', params: { id: item.idAutor } });
             
            },
        }
    }
    
    </script>
    <style>
    .v-data-table__selected{
      background-color: orange;
    }
    </style>
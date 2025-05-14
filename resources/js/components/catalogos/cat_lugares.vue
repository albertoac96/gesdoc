<template>
    <v-data-table
        :headers="headers"
        :items="desserts"
        sort-by="calories"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar
            flat
          >
            <v-toolbar-title>Lugares</v-toolbar-title>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-spacer></v-spacer>
    
    
            <v-dialog
              v-model="dialog"
              max-width="500px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                 v-if="$canBtn('agregar', 'No tiene permisos para agregar')"
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  Nuevo
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>
    
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                        
                      >
                        <v-text-field
                          v-model="editedItem.cNombre"
                          label="Nombre del lugar"
                        ></v-text-field>
                      </v-col>

                      <v-col
                        cols="12"
                        
                      >
                        <v-text-field
                          v-model="editedItem.cLat"
                          label="Latitud"
                        ></v-text-field>
                      </v-col>

                      <v-col
                        cols="12"
                        
                      >
                        <v-text-field
                          v-model="editedItem.cLong"
                          label="Longitud"
                        ></v-text-field>
                      </v-col>

                    <v-col
                        cols="12"
                        
                      >
                           
			<iframe id="idMapaSitio" src="https://maps.google.com/maps?width=100%&height=200&hl=es&q=&ie=UTF8&t=&z=7&iwloc=B&output=embed" style="border:0;width:100%;height:500px"></iframe>
		
            </v-col>
                     
                      
    
                     
                     
                     
                   
                      
                    </v-row>
                  </v-container>
                </v-card-text>
    
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="close"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="save"
                  >
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            
            
            <v-dialog v-model="dialogDelete" max-width="600px">
              <v-card>
                <v-card-title class="text-h5">{{dlgTitle}}</v-card-title>
                <v-card-text>{{dlgSubTitle}}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
    
    
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon
           v-if="$canBtn('editar', 'No tiene permisos para agregar')"
            small
            class="mr-2"
            @click="editItem(item)"
          >
            mdi-pencil
          </v-icon>
    
  
    
        </template>
        <template v-slot:no-data>
          <v-btn
            color="primary"
            @click="initialize"
          >
            Reset
          </v-btn>
        </template>
    
    
    
    
        <template v-slot:item.created_at="{ item }">
            <span v-if="item.dInicio == null" >Sin fecha</span>
          <span v-else v-html="MiTiempo(item.created_at)"></span>
        </template>
    
    
      </v-data-table>
    </template>
    <script>
    export default {
        name: "catLugares",
        props: [],
        components:{
    
        },
        data: () => ({
            dialog: false,
          dialogDelete: false,
          headers: [
            {
              text: '',
              align: 'start',
              sortable: false,
              value: 'idLugar',
            },
            { text: 'cNombre', value: 'cNombre' },
            { text: 'Latitud', value: 'cLat' },
            { text: 'Longitud', value: 'cLong' },
    
          ],
          desserts: [],
          editedIndex: -1,
          delItem: 0,
          editedItem: {
            idGrupo: '',
            cNombre: '',
        
          },
          defaultItem: {
            idGrupo: '',
            cNombre: '',
        
          }
          
        }),
        mounted(){
           
        },
        created(){
          //this.$can('/verinst', 'No tiene permisos para ver esta pantalla')
            this.initialize()
        },
        beforeMount(){
    
        },
        watch:{
            dialog (val) {
            val || this.close()
          },
          dialogDelete (val) {
            val || this.closeDelete()
          },
          'editedItem.cLat' (val) {
            this.changeUbi();
          },
          'editedItem.cLong' (val) {
            this.changeUbi();
          },
        },
        computed:{
            formTitle () {
            return this.editedIndex === -1 ? 'Nuevo lugar' : 'Editar lugar'
          },
          dlgTitle () {
            return this.delItem === 1 ? '¿Esta seguro de dar de baja esta grupo?' : '¿Esta seguro de dar de alta esta insitución?'
          },
          dlgSubTitle () {
            return this.delItem === 1 ? 'Al dar de baja la grupo las relaciones con los documentos seguirán registradas, pero no podrá ser asignada a nuevos documentos.' : 'Al dar de alta la insitución podrá ser asignada a nuevos documentos'
          },
        },
        methods:{
          initialize () {
            axios
                 .get("/lugares/list")
                 .then((res) => {
                    this.desserts = res.data;
                 })
                 .catch((error) => {
            });
          },
    
          editItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
          },
    
          deleteItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.delItem = 1;
            this.dialogDelete = true
          },
          altaItem(item){
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.delItem = 0;
            this.dialogDelete = true
          },
    
          deleteItemConfirm () {
            //this.desserts.splice(this.editedIndex, 1)
            if(this.delItem === 0){
                this.desserts[this.editedIndex].cEstatus = 'A';
            } else {
                this.desserts[this.editedIndex].cEstatus = 'B';
            }
            
            this.closeDelete()
          },
    
          close () {
            this.dialog = false
            this.$nextTick(() => {
              this.editedItem = Object.assign({}, this.defaultItem)
              this.editedIndex = -1
            })
          },
    
          closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
              this.editedItem = Object.assign({}, this.defaultItem)
              this.editedIndex = -1
            })
          },
    
          save () {
            if (this.editedIndex > -1) {
              // Actualizar un registro existente
              axios
                .put(`/lugares/nueva/${this.editedItem.idInstitucion}`, this.editedItem)
                .then((response) => {
                  Object.assign(this.desserts[this.editedIndex], response.data.data);
                  this.$toast.success('Grupo actualizada exitosamente');
                })
                .catch((error) => {
                  console.error(error);
                  //this.$toast.error('Error al actualizar la institución');
                });
            } else {
              // Crear un nuevo registro
              axios
                .post('/lugares/nueva', this.editedItem)
                .then((response) => {
                  this.desserts.push(response.data.data);
                  this.initialize();
                  //this.$toast.success('Institución creada exitosamente');
                })
                .catch((error) => {
                  console.error(error);
                  //this.$toast.error('Error al crear el grupo');
                });
            }
            this.close();
          },
          changeUbi(){
             var LcLat = this.editedItem.cLat;
            var LcLong = this.editedItem.cLong;
            var LcURL = "https://maps.google.com/maps?width=100%&height=200&hl=es&q=" + LcLat + "," + LcLong + "&ie=UTF8&t=&z=7&iwloc=B&output=embed";
            document.getElementById("idMapaSitio").src=LcURL;
        
          }
        }
    }
    </script>
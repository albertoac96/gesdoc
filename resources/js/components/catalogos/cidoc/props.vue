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
            <v-toolbar-title>Propiedades</v-toolbar-title>
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
                          v-model="editedItem.CvePropiedad"
                          label="Clave de propiedad"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        
                      >
                        <v-text-field
                          v-model="editedItem.cNombre"
                          label="Nombre"
                        ></v-text-field>
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
    
    
       
    
    
      </v-data-table>
    </template>
    <script>
    export default {
        name: "catPubs",
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
              value: 'idPropiedad',
            },
            { text: 'Propiedad', value: 'CvePropiedad' },
            { text: 'Nombre', value: 'cNombre' },
            { text: 'Actions', value: 'actions', sortable: false },
          ],
          desserts: [],
          editedIndex: -1,
          delItem: 0,
          editedItem: {
            idEntidad: '',
            CveEntidad: '',
            cNombre: '',
          },
          defaultItem: {
            idEntidad: '',
            CveEntidad: '',
            cNombre: '',
          },
          TipoPub: [
            {idCatTipoPub: "1", cTipoPub: "Revista"},
            {idCatTipoPub: "2", cTipoPub: "Periódico"}
          ]
        }),
        mounted(){
           
        },
        created(){
          //this.$can('/entidades', 'No tiene permisos para ver esta pantalla')
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
        },
        computed:{
            formTitle () {
            return this.editedIndex === -1 ? 'Nueva' : 'Editar'
          },
          dlgTitle () {
            return this.delItem === 1 ? '¿Esta seguro de dar de baja?' : '¿Esta seguro de dar de alta?'
          },
          dlgSubTitle () {
            return this.delItem === 1 ? 'Al dar de baja la publicación las relaciones con los documentos seguirán registradas, pero no podrá ser asignada a nuevos documentos.' : 'Al dar de alta la publicación podrá ser asignada a nuevos documentos'
          },
        },
        methods:{
          initialize () {
            axios
                 .get("/cidoc/propiedad/show")
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
              Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
              this.desserts.push(this.editedItem)
            }
            this.close()
          },
        }
    }
    </script>
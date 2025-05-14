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
        <v-toolbar-title>Atributos de documentos</v-toolbar-title>
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
                      v-model="editedItem.cTipoDoc"
                      label="Nombre del tipo"
                    ></v-text-field>
                  </v-col>

                  <v-col
                    cols="10"
                    
                  >
                    <v-text-field
                      v-model="editedItem.cIcono"
                      label="Icono"
                    ></v-text-field>
                  </v-col>

                  <v-col
                    cols="2"
                    
                  >
                    <v-icon>
                      {{editedItem.cIcono}}
                    </v-icon>
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

        <v-icon
         v-if="$canBtn('editar', 'No tiene permisos para agregar')"
      v-show="item.cEstatus == 'A'"
        small
        @click="deleteItem(item)"
      >
        mdi-close
      </v-icon>

      <v-icon
       v-if="$canBtn('editar', 'No tiene permisos para agregar')"
       v-show="item.cEstatus == 'B'"
        small
        @click="altaItem(item)"
      >
        mdi-check
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


     <template v-slot:item.idTipoDoc="{ item }">
       <v-icon>
        {{item.cIcono}}
      </v-icon>
    </template>

    <template v-slot:item.cEstatus="{ item }">
      <v-chip
        v-if="item.cEstatus == 'A'"
        color="success"
        dark
      >
      {{item.cEstatus}}
      </v-chip>

      <v-chip
        v-else
        color="error"
        dark
      >
      {{item.cEstatus}}
      </v-chip>
    </template>

    <template v-slot:item.created_at="{ item }">
        <span v-if="item.dInicio == null" >Sin fecha</span>
      <span v-else v-html="MiTiempo(item.created_at)"></span>
    </template>


  </v-data-table>
</template>
<script>
export default {
    name: "catAtr",
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
          value: 'idAtributo',
        },
        { text: 'Tema', value: 'cAtributo' },
        { text: 'Tema', value: 'cTipo' },
        { text: 'cEstatus', value: 'cEstatus' },
        { text: 'Creado por', value: 'name' },
        { text: 'Fecha de creación', value: 'created_at' },
        { text: 'nDocs', value: 'nDocs' },
        { text: 'nTipos', value: 'nTipos' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedIndex: -1,
      delItem: 0,
      editedItem: {
        idAtributo: '',
        cAtributo: '',
        cTipo: '',
      },
      defaultItem: {
        idAtributo: '',
        cAtributo: '',
        cTipo: '',
      },
    }),
    mounted(){
       this.$can('/atributos', 'No tiene permisos para ver esta pantalla')
    },
    created(){
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
        return this.editedIndex === -1 ? 'Nuevo atributo' : 'Editar atributo'
      },
      dlgTitle () {
        return this.delItem === 1 ? '¿Esta seguro de dar de baja este atributo?' : '¿Esta seguro de dar de alta este atributo?'
      },
      dlgSubTitle () {
        return this.delItem === 1 ? 'Al dar de baja el atributo las relaciones con los documentos seguirán registradas, pero no aparecerá en la biblioteca ni podrá ser asignado a nuevos documentos.' : 'Al dar de alta este atributo podrá ser asignado a nuevos documentos y aparecerá en su biblioteca'
      },
    },
    methods:{
      initialize () {
        axios
             .get("/atrshow")
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
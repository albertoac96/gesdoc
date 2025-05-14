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
        <v-toolbar-title>Temas</v-toolbar-title>
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
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
              v-if="$canBtn('agregar', 'No tiene permisos para agregar')"
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
                      v-model="editedItem.cTema"
                      label="Nombre del tema"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    
                  >
                     <v-textarea
                     v-model="editedItem.cDescTema"
          name="input-7-1"
          label="Descripción del tema"
        ></v-textarea>
                  </v-col>
                 
                  <v-col
                    cols="12"
                   
                 
                  >
                   <v-color-picker
                    dot-size="25"
                    swatches-max-height="200"
                     v-model="editedItem.cColor"
                    ></v-color-picker>
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
        
        
        <v-dialog v-model="dialogDelete" max-width="500px">
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


     <template v-slot:item.idTema="{ item }">
      <v-chip
        :color="item.cColor"
        dark
      >
      
      </v-chip>
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
    name: "catTemas",
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
          value: 'idTema',
        },
        { text: 'Tema', value: 'cTema' },
        { text: 'Descripción', value: 'cDescTema' },
        { text: 'cEstatus', value: 'cEstatus' },
        
        { text: 'Creado por', value: 'name' },
        { text: 'Fecha de creación', value: 'created_at' },
        { text: 'Docs', value: 'nDocs' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedIndex: -1,
      delItem: 0,
      editedItem: {
        idTema: '',
        cTema: 0,
        cDescTema: 0,
        cEstatus: 0,
        cColor: 0,
      },
      defaultItem: {
        idTema: '',
        cTema: '',
        cDescTema: '',
        cEstatus: 'A',
        cColor: '',
      },
    }),
    mounted(){
       this.$can('/temas', 'No tiene permisos para ver esta pantalla')
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
        return this.editedIndex === -1 ? 'Nuevo tema' : 'Editar tema'
      },
      dlgTitle () {
        return this.delItem === 1 ? '¿Esta seguro de dar de baja este tema?' : '¿Esta seguro de dar de alta este tema?'
      },
      dlgSubTitle () {
        return this.delItem === 1 ? 'Al dar de baja el tema las relaciones con los documentos seguirán registradas, pero el tema no aparecerá en búsquedas ni podrá ser asignado a nuevos documentos.' : 'Al dar de alta este tema, volverá a aparacer en búsquedas y podrá ser asignado a nuevos documentos'
      },
    },
    methods:{
      initialize () {
        axios
             .get("/traetemas")
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
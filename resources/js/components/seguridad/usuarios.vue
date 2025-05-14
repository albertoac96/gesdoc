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
        <v-toolbar-title>Usuarios </v-toolbar-title>
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
                      v-model="editedItem.name"
                      label="Nombre"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                   
                  >
                    <v-text-field
                      v-model="editedItem.email"
                      label="Correo"
                    ></v-text-field>
                  </v-col>

                  


                 <v-col cols="12">
                    <v-combobox
                    v-model="editedItem.cRol"
                    :items="cboRoles"
                    label="Rol"
                    item-text="cRol"
                    value-text="idRol"
                    outlined
                    dense
                    ></v-combobox>
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
                Cancelar
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Crear
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
              <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
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
        
      <span v-html="cFechaYMDlarga(item.created_at)"></span>
    </template>

    <template v-slot:item.cRol="{ item }">
        <span v-if="typeof item.cRol == 'object'">{{item.cRol.cRol}}</span>
      <span v-else>{{item.cRol}}</span>
    </template>



    


  </v-data-table>
</template>
<script>
export default {
    name: "verUsuarios",
    props: [],
    components:{

    },
    data: () => ({
      
       
         dialog: false,
      dialogDelete: false,
     
      headers: [
        {
          text: 'ID',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'Nombre', value: 'name' },
        { text: 'Correo', value: 'email' },
        { text: 'Creado por', value: 'creado' },
        { text: 'Fecha de creación', value: 'created_at' },
        { text: 'Rol', value: 'cRol' },
        { text: 'Estatus', value: 'cEstatus' },
        { text: 'Acciones', value: 'actions', sortable: false },
        
      ],
      desserts: [],
      editedIndex: -1,
      delItem: 0,
      editedItem: {
        name: '',
        email: '',
        oRol: '',
        cRol: '',
        idRol: '',
        id: '',
        creado: '',
        created_at: '',
        cEstatus: 'A'
      },
      defaultItem: {
        oRol: '',
        name: '',
        email: '',
        cRol: '',
        idRol: '',
        id: '',
        creado: '',
        created_at: '',
        cEstatus: 'A'
      },
      cboRoles: []
    }),
    mounted(){
      this.$can('/segusers', 'No tiene permisos para ver esta pantalla')
        
      
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
        return this.editedIndex === -1 ? 'Nuevo usuario' : 'Editar usuario'
      },
      dlgTitle () {
        return this.delItem === 1 ? '¿Esta seguro de dar de baja este usuario?' : '¿Esta seguro de dar de alta este usuario?'
      },
      dlgSubTitle () {
        return this.delItem === 1 ? 'Al dar de baja al usuario no se le permitira el acceso al sistema' : 'Al dar de alta al usuario podrá volver a ingresar al sistema'
      },
    },
    methods:{
        initialize () {
        axios
             .get("/showusers")
             .then((res) => {
                this.desserts = res.data.users;
                this.cboRoles = res.data.roles;
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
         this.$store.state.overlay=true;
        if (this.editedIndex > -1) {
            axios
                 .post("/user/editar", this.editedItem)
                 .then((res) => {
                   if(res.data == 1){
                      this.$store.state.model = true;
                      this.$store.state.color = "error";
                      this.$store.state.text = "El correo ya esta registrado en el sistema con otro usuario";
                      this.$store.state.centered = true;
                       this.$store.state.overlay=false;
                   } else {
                    Object.assign(this.desserts[this.editedIndex], this.editedItem)
                     this.$store.state.model = false;
                      this.$store.state.color = "success";
                      this.$store.state.text = "La información del usuario ha sido modificada";
                      this.$store.state.centered = true;
                       this.$store.state.overlay=false;
                   }
                 })
                 .catch((error) => {
            });
          
        } else {

          var URLactual = window.location;
          var URL = URLactual.protocol + "//" + URLactual.host;
          let InstFormData = new FormData();
        InstFormData.append('item' , JSON.stringify(this.editedItem));
        InstFormData.append('url' , URL);
          this.$store.state.overlay=true;
          axios
                .post("/user/nuevo", InstFormData)
                .then((res) => {
                   console.log(res.data); 
                   if(res.data == 1){
                      this.$store.state.model = true;
                      this.$store.state.color = "error";
                      this.$store.state.text = "El correo ya esta registrado en el sistema";
                      this.$store.state.centered = true;
                       this.$store.state.overlay=false;
                   } else {
                     this.desserts.push(res.data);
                     this.$store.state.model = true;
                      this.$store.state.color = "success";
                      this.$store.state.text = "El usuario ha sido registrado y se le envió un correo con su información de inicio de sesión";
                      this.$store.state.centered = true;
                       this.$store.state.overlay=false;
                   }
                   
                })
                .catch((error) => {
           });
        }
        this.close()
      },
    }
}
</script>
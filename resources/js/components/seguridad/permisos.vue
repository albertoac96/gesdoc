<template>
<v-card>
    <v-card-title>
        Asignar permisos a rol
        <v-spacer></v-spacer>
        <v-combobox 
        v-model="rolSelected"
            label="Rol"
            :items="cboRoles"
            item-text="cRol"
            item-value="idRol"
            @change="permRol()"
        >
        </v-combobox>
        <v-col cols="12">
            <v-btn v-if="$canBtn('editar', 'No tiene permisos para agregar') && mostrar != 0" @click="savePermisos()">Asignar permisos</v-btn>
        </v-col>
    </v-card-title>
    <v-card-text>
        <div v-if="mostrar != 0">
        Eliga a que pantallas del menú tendra acceso el rol
        
        <v-treeview
            v-model="selectedMenu"
            selectable
            :items="itemsMenu"
            item-key="cURL"
            item-text="cNombre"
        ></v-treeview>
        Eliga las acciones que puede hacer el rol 
        <v-checkbox
            v-model="chk.agregar"
            label="Agregar elementos"
        ></v-checkbox>
        <v-checkbox
            v-model="chk.editar"
            label="Editar elementos"
        ></v-checkbox>
        <v-checkbox
            v-model="chk.papelera"
            label="Enviar a papelera elementos"
        ></v-checkbox>
        <v-checkbox
            v-model="chk.eliminar"
            label="Eliminar elementos"
        ></v-checkbox>
        <v-checkbox
            v-model="chk.descargar"
            label="Descargar elementos"
        ></v-checkbox>

        </div>
        <div v-else>
            <v-alert
            border="top"
      color="blue-grey"
      dark>Eliga un rol para modificar sus permisos</v-alert>
        </div>
    </v-card-text>
</v-card>
</template>
<script>
export default {
    name: "verPermisos",
    props: [],
    components:{

    },
    data: () => ({
        selectedMenu: [],
        itemsMenu: [],
        acciones: "",
        cboRoles: [],
        rolSelected: "",
        chk: {
            editar: false,
            agregar: false,
            papelera: false,
            eliminar: false,
            descargar: false,
        },
        mostrar: 0
    }),
    mounted(){
       this.$can('/segpermisos', 'No tiene permisos para ver esta pantalla')
    },
    created(){
        this.inicio();
    },
    beforeMount(){

    },
    watch:{
        
    },
    computed:{
   
    },
    methods:{
      inicio () {
           axios
                .get("/showpermisos")
                .then((res) => {
                  
                   this.acciones = res.data.acciones;
                   this.cboRoles = res.data.cboRoles;
                  
                })
                .catch((error) => {
           });
            axios
                 .get("/traemenu")
                 .then((res) => {
                    this.itemsMenu = res.data;
                 })
                 .catch((error) => {
            });
      },
      permRol(){
          this.mostrar = 1;
          this.chk.agregar = false;
          this.chk.editar = false;
          this.chk.papelera = false;
          this.chk.eliminar = false;
          this.chk.descargar = false;
           axios
                .get("/pmenu/" + this.rolSelected.idRol)
                .then((res) => {
                   this.selectedMenu = res.data;
                   
                   
                })
                .catch((error) => {
           });
            axios
                 .get("/paccions/" + this.rolSelected.idRol)
                 .then((res) => {
                   var acciones = res.data;
                   if(acciones.length > 0){
                    for(var i=0; i < acciones.length; i++){
                       if(acciones[i].name == 'agregar') this.chk.agregar = true;
                       if(acciones[i].name == 'eliminar') this.chk.eliminar = true;
                       if(acciones[i].name == 'editar') this.chk.editar = true;
                       if(acciones[i].name == 'papelera') this.chk.papelera = true;
                       if(acciones[i].name == 'descargar') this.chk.descargar = true;
                    }
                   }
                 })
                 .catch((error) => {
            });
      },
      savePermisos(){
      
          let InstFormData = new FormData();
        InstFormData.append('acciones' , JSON.stringify(this.chk));
        InstFormData.append('menu' , JSON.stringify(this.selectedMenu));
        InstFormData.append('rol' , JSON.stringify(this.rolSelected));
         axios
              .post("/editpermisos", InstFormData)
              .then((res) => {
                 this.$infoUser(); 
              })
              .catch((error) => {
         });
      }
    }
}
</script>
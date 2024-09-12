<template>
     <v-dialog
      v-model="dlg"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 lime lighten-5">
          Editar autores
        </v-card-title>

        

        <v-card-text class="mt-3">
            <v-btn v-if="$canBtn('agregar', 'No tiene permisos para agregar')" small color="success" @click.stop="dlgNuevo = true"><v-icon small>mdi-plus</v-icon> Nuevo</v-btn>

            <draggable v-model="list" @start="drag=true" @end="drag=false">
               
            
                <v-card class="pa-3 mt-2" color="blue-grey lighten-5" elevation="2" v-for="element in list" :key="element.idAutor">
                   
                        <span><b>{{element.cNombre}}</b> <label class="caption">({{element.cTipoAutor}})</label></span>
                        
                        <v-btn v-if="$canBtn('editar', 'No tiene permisos para editar')" icon small color="info" @click="edit(element)"><v-icon small>mdi-pencil</v-icon></v-btn>
                        <v-btn v-if="$canBtn('papelera', 'No tiene permisos para eliminar')" icon small color="error" @click="borrar(element)"><v-icon>mdi-close</v-icon></v-btn>

                </v-card>
               
            
            </draggable>

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions class="lime lighten-5">
          <v-spacer></v-spacer>
           <v-btn
            color="error"
            text
            @click="fnCancelar()"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="success"
            text
            @click="fnGuardar()"
          >
            Guardar
          </v-btn>
         
        </v-card-actions>
      </v-card>


       <v-dialog
      v-model="dlgNuevo"
      width="500"
    >
      
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Agregar autores
           <v-btn small color="success" @click.stop="fnAdd()"><v-icon small>mdi-plus</v-icon> Registrar autor</v-btn>
        </v-card-title>

        <v-card-text>
            <v-combobox
                v-model="autoresSelect"
                
                :items="cboAutores"
               
                hide-selected
                label="Elige autores"
                multiple
                small-chips
                solo
                item-value="idAutor"
                item-text="cNombre"
               
              >
                 <template v-slot:selection="{ attrs, item, parent, selected }">
                  <v-chip
                    v-if="item === Object(item)"
                    v-bind="attrs"
                    color="blue lighten-5"
                    :input-value="selected"
                    label
                    small
                  >
                    
                    

                    <span class="pr-2">
                      {{ item.cNombre }}
                    </span>
                    <v-icon small @click="parent.selectItem(item)">
                      $delete
                    </v-icon>
                  </v-chip>
                </template>

                <template slot="item" slot-scope="data">
                  {{ data.item.cNombre }} 
                </template>

              </v-combobox>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="fnGuardarNuevos()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


    <v-dialog
      v-model="dlgAdd"
      width="500"
    >
      
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
         Registro de nuevo autor
          
        </v-card-title>

        <v-card-text>
            <v-text-field
            label="Nombre"
            v-model="newAutor.cNombre">
            </v-text-field>
            <v-text-field
            label="Apellido"
            v-model="newAutor.cApellido">
            </v-text-field>
            <v-text-field
            label="ORCID"
            v-model="newAutor.cORCID">
            </v-text-field>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="error"
            text
            @click="dlgAdd = false"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnGuardarAddOK()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

 <v-dialog
      v-model="dlgEdit"
      width="500"
    >
      
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
         Editar tipo de autor
          
        </v-card-title>

        <v-card-text>
            <v-combobox
            label="Tipo de autor"
            v-model="tipoSelect"
            :items="cboTipoAutor"
            item-value="idTipoAutor"
            item-text="cTipoAutor">
            </v-combobox>
           
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="error"
            text
            @click="dlgEdit = false"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnEditTipoAutor()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


    </v-dialog>
</template>
<script>
import draggable from 'vuedraggable';
export default {
    name: "dlgEditAutores",
    props: ["autores", "idDoc"],
    components:{
        draggable
    },
    data: () => ({
        dlg: true,
        enabled: true,
      list: [],
      drag: false,
      dlgNuevo: false,
      dlgAdd: false,
      dlgEdit: false,
      tipoSelect: [],
      cboTipoAutor: [],
      autoresSelect: [],
      cboAutores: [],
      newAutor:{
          cNombre: "",
          cApellido: "",
          cORCID: ""
      },
      autoresOriginal: [],
idEdit: 0,
cEdit: "",

      oAutores: [],
colors: ['blue'],
nonce: 1,
response: [],

   

      search: null,


    }),
    mounted(){
        this.list = this.autores.slice();
    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{
        dlgNuevo(value){
            if(value == true){
               
                if(this.cboAutores.length == 0){
                   //console.log("Lleno combo");
                    axios
                    .get("/autorindex")
                    .then((res) => {
                        this.cboAutores = res.data;
                        this.QuitaAutoresRegistrados();
                        
                    })
                    .catch((error) => {
                    });
                } else {
                    this.QuitaAutoresRegistrados();
                }
                   
                
                
            }
        },
       
        autoresSelect (val, prev) {
      if (val.length === prev.length) return;

      this.autoresSelect = val.map((v) => {
        if (typeof v === "string") {
          
         return;
        }

        return v;
      });
    },
    },
    computed:{
        
    },
    methods:{
        fnGuardar(){
            /*"cColor": "blue-grey darken-2",
                "cNombre": item.cNombre,
                "cTipoAutor": item.cTipoAutor,
                "iOrden": null,
                "idAutor": item.idAutor,*/
                
                ////console.log(JSON.stringify(this.autores));
                ////console.log(JSON.stringify(this.list));

                if(this.list.length == 0){
                 this.$emit('itemEdit', 1);
                } else {
                   for(var i = 0; i < this.list.length; i++){
                        ////console.log(i);
                        this.list[i].iOrden = i;
                    }
                }

                


                    if(this.VerificaPermiso('editar') == true){

                                    if(JSON.stringify(this.autores) == JSON.stringify(this.list)){
                                            //console.log("igual");
                                            this.$emit('itemEdit', 0);
                                        } else {
                                            
                                            
                                              let InstFormData = new FormData();
                                              InstFormData.append('idElemento' , this.idDoc);
                                              InstFormData.append('cDesc' , "Registro de autor");
                                              InstFormData.append('idDocumento' , this.idDoc);
                                              InstFormData.append('tipo' , 'autores');
                                              InstFormData.append('item' , JSON.stringify(this.list));
                                                axios
                                                .post("/edit", InstFormData)
                                                .then((res) => {
                                                  this.$store.state.snack.text = "Los autores fueron actualizados";
                                                  this.$store.state.snack.color = "success";
                                                  this.$store.state.snack.centered = false;
                                                  this.$store.state.snack.model = "true";
                                                  this.$emit('itemEdit', this.list);
                                                })
                                                .catch((error) => {});


                                            
                                            
                                        }

                
                
              } else {
                this.$store.state.snack.text = "No tienes permisos para editar este elemento";
                this.$store.state.snack.color = "error";
                this.$store.state.snack.model = "true";
              }
                    
                     

               
        },
        fnCancelar(){
          this.$emit('itemEdit', 0);
        },
        edit(item){
            this.idEdit = item.idAutor;
            this.cEdit = item.cNombre;
            this.tipoSelect = {
                "idTipoAutor": item.idTipoAutor,
                "cTipoAutor": item.cTipoAutor,
                "cColor": item.cColor
            };
            if(this.cboTipoAutor.length == 0){
                axios
                .get("/tipoautor")
                .then((res) => {
                    this.cboTipoAutor = res.data;
                })
                .catch((error) => {
                });
            };
            this.dlgEdit = true;
            
        },
        fnEditTipoAutor(){

          if(this.VerificaPermiso('editar') == true){

          let InstFormData = new FormData();
          
          var pos = this.list.map(function(e) { return e.idAutor; }).indexOf(this.idEdit);
            if(this.list[pos].idTipoAutor == this.tipoSelect.idTipoAutor){
                //console.log("No hay cambios");
                return;
            }

          InstFormData.append('idElemento' , this.idEdit);
          InstFormData.append('cDesc' , "De " + this.list[pos].cTipoAutor + " a " + this.tipoSelect.cTipoAutor + " -> " + this.cEdit);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'autor');
          InstFormData.append('item' , JSON.stringify(this.tipoSelect));
            axios
            .post("/edit", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "El tipo de autor fue modificado";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";

              this.list[pos].idTipoAutor = this.tipoSelect.idTipoAutor;
            this.list[pos].cTipoAutor = this.tipoSelect.cTipoAutor;
            this.list[pos].cColor = this.tipoSelect.cColor;
            this.dlgEdit = false;

           
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para editar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }


            
            



        },
        QuitaAutoresRegistrados(){
         
           
            if(this.list.length == 0){
              
                return;
            }
                      
            this.autoresSelect = [];
            var idAutores = new Array();
           
            
            
            for(var i=0; i < this.list.length; i++){
                
                idAutores.push(this.list[i].idAutor);
               
                var pos = this.cboAutores.map(function(e) { return e.idAutor; }).indexOf(this.list[i].idAutor);
               
                this.autoresSelect.push(this.cboAutores[pos]);
                //this.cboAutores.splice(pos, 1);
            }
           

        },
         fnAdd(){
             this.newAutor.cNombre = "";
            this.newAutor.cApellido = "";
            this.newAutor.cORCID = "";
            this.dlgAdd = true;
           

        },
        fnGuardarAddOK(){


          if(this.VerificaPermiso('agregar') == true){

            if(this.newAutor.cNombre == "" || this.newAutor.cApellido == ""){
                this.$store.state.snack.text = "Llena los campos de nombre y apellido";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
                return;
            }

            

          
         
            axios
            .post("/regautor", this.newAutor)
            .then((res) => {
              var autor = {
                "idAutor": "1",
                "cNombre": this.newAutor.cNombre + " " + this.newAutor.cApellido,
                "idTipoAutor": "1",
                "cTipoAutor": "Autor",
                "cColor": "blue-grey darken-2"
            }
            this.cboAutores.push(autor);
            this.autoresSelect.push(autor);
            this.dlgAdd = false;

              this.$store.state.snack.text = "El autor fue agregado";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
         
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para agregar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }


            
            



        },
        fnGuardarNuevos(){
            //console.log(this.autoresSelect);
            this.list = this.autoresSelect; 
            this.dlgNuevo = false;
        },
        borrar(item){
            if(this.VerificaPermiso('borrar') == true){

          let InstFormData = new FormData();
          InstFormData.append('idElemento' , item.idAutor);
          InstFormData.append('cDesc' , "De " + item.cNombre);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'autor');
          InstFormData.append('item' , JSON.stringify(this.item));
            axios
            .post("/deleterel", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "El autor fue eliminado";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
               var pos = this.list.map(function(e) { return e.idAutor; }).indexOf(item.idAutor);
            this.list.splice(pos, 1);
              
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para eliminar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }
            
           
            
        },
         filter(item, queryText, itemText) {
      if (item.header) return false;

      const hasValue = (val) => (val != null ? val : "");

      const text = hasValue(itemText);
      const query = hasValue(queryText);

      return (
        text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) >
        -1
      );
    },
    }
}
</script>
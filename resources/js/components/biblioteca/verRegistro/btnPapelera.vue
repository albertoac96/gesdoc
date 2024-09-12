<template>
     <div>

            <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
         
           <v-btn icon  v-bind="attrs"
            v-on="on" @click="deleteItem()">
             <v-icon>mdi-delete-outline</v-icon>
           
            </v-btn>
        </template>

        <span>Enviar a la papelera</span>
      </v-tooltip>

     
    <v-dialog
      v-model="dlgDelete"
      width="500"
    >
    

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Eliminar documento
        </v-card-title>

        <v-card-text>
            ¿Seguro que quieres enviar a la papelera este documento?       
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="error"
            text
            @click="dlgDelete = false"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="delItemOK()"
          >
            SI
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
    name: "btnPapelera",
    props: ['doc'],
    components:{

    },
    data: () => ({
        dlgDelete: false,
    }),
    mounted(){

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
        deleteItem(){
            if(this.$canSnack('papelera', 'No tienes permisos para eliminar')){
                this.dlgDelete = true;
            }
        },
        delItemOK(){
            
           
            
            if(this.$canSnack('papelera', 'No tienes permisos para eliminar')){

                let InstFormData = new FormData();
                InstFormData.append('idDoc' , this.doc);


                 axios
                      .post("/deletItem", InstFormData)
                      .then((res) => {
                         console.log(res.data); 
                         this.dlgDelete = false;
                         var node = { cCarpeta: "", idCarpeta: res.data.idCarpeta };
                        var ruta = { name: "biblioteca", params: node };
                        this.$router.push(ruta);
                          
                      })
                      .catch((error) => {
                 });
            }
        }
    }
}
</script>
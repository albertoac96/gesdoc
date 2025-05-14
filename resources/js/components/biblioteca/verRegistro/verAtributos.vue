<template>
<div>
   
    <!--<v-list
      flat
      subheader
      three-line
      v-if="atributos.length > 0">
      <v-list-item-title class="ml-4">Atributos
         <v-btn icon small @click.stop="dlgEditAtr = true"><v-icon small>mdi-pencil</v-icon></v-btn>
      </v-list-item-title>
      <dlgEditAtr :idDoc="idDoc" @close="dlgEditAtr=false" @itemEdit="fnEditAtr($event)"></dlgEditAtr>

      <v-list-item v-for="(atr, index) in atributos" :key="index" class="mt-0">
        <v-list-item-content>
          <v-list-item-title>{{atr.cAtributo}}</v-list-item-title>
          <v-list-item-subtitle>{{atr.cValor}}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

    </v-list>


    <span v-else>No hay atributos del documento</span>-->


    <v-data-table
      :headers="headers"
      :items="items"
      dense
      disable-pagination
      :items-per-page="-1"
      hide-default-footer
    >
      <template  v-slot:item.value="props">
        <v-edit-dialog
          
          :return-value.sync="props.item.value"
          @save="save(props.item)"
          @cancel="cancel"
          large
          cancel-text="Cancelar"
          save-text="Guardar"
     
        >
          {{ props.item.value }}
          <template v-slot:input>
            <v-text-field
              v-model="props.item.value"
              label="Editar"
              single-line
            ></v-text-field>
          </template>
         
        </v-edit-dialog>
      </template>


      
    </v-data-table>



</div>
</template>
<script>
import dlgEditAtr from "../../dialogos/edit/dlgEditAtributos.vue";
export default {
    name: "verAtributos",
    props: ["atributos", "idDoc"],
    components:{
      dlgEditAtr
    },
    data: () => ({
        dlgEditAtr: false,
        headers:[
          {text:"Atributo", value: "name", width: "40px"},
          {text: "Valor", value:"value"}
        ],
        items: []
    }),
    mounted(){
      axios
        .get("/atrdoc/" + this.idDoc)
        .then((res) => {
          this.items = res.data;
      })
      .catch((error) => {
      });
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
      save(item){
            

       if(this.$canSnack('editar', 'No tienes permisos para editar este elemento')){
        
         if(item.idAtributo == 11 || item.idAtributo == 47){
            var atr = {
              "idAtributo": item.idAtributo,
              "cValor": item.value
            }
          
          this.atributos.push(atr);
        }
       
          let InstFormData = new FormData();
          InstFormData.append('idElemento' , item.idDocAtr);
          InstFormData.append('cDesc' , "Edición de atributo " + item.name);
          InstFormData.append('idDocumento' , item.idDocumento);
          InstFormData.append('tipo' , 'atributo');
          InstFormData.append('item' , JSON.stringify(item));
          
          axios
            .post("/edit", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "El atributo fue actualizado";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
              
            })
          .catch((error) => {

          });
         
       } else {
        
       }

          
       


      },
      cancel(){
        //console.log("cancel")
      }
    }
}
</script>
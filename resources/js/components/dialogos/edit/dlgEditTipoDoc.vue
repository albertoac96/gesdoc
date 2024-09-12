<template>
    <v-dialog
      v-model="dlg"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Editar tipo de documento
           
           
        </v-card-title>

        <v-card-text>
            <v-col cols="12">
                <v-combobox
                v-model="tipodoc"
                :items="items"
                label="Tipo de documento"
                outlined
                dense
                >
                
                <template slot="selection" slot-scope="data">
           <span><v-icon>{{data.item.cIcono}}</v-icon>  {{data.item.text}}</span>
           
          </template>
          <template slot="item" slot-scope="data">
           <span><v-icon>{{data.item.cIcono}}</v-icon>  {{data.item.text}}</span>
          </template>

                </v-combobox>
            </v-col>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="error"
            text
            @click="$emit('close')"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnCambiaTipoDoc()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "dlgEditTipoDoc",
    props: ["item", "idDoc"],
    components:{

    },
    data: () => ({
        items: [],
        tipodoc: [],
        dlg: true,
    }),
    mounted(){
        this.tipodoc = this.item;
        axios
          .get("/cbotipodoc")
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
        fnCambiaTipoDoc(){

          if(this.VerificaPermiso('editar') == true){

            if(this.item.value == this.tipodoc.value){
            this.$store.state.snack.text = "Edite el tipo de documento";
              this.$store.state.snack.color = "info";
              this.$store.state.snack.centered = true;
              this.$store.state.snack.model = "true";
            return;
          }


          let InstFormData = new FormData();
          InstFormData.append('idElemento' , this.tipodoc.value);
          InstFormData.append('cDesc' , "De " + this.item.text + " a " + this.tipodoc.text);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'tipodoc');
          InstFormData.append('item' , JSON.stringify(this.tipodoc));
            axios
            .post("/edit", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "Se editó el tipo de documento";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
              this.$emit('itemEdit', this.tipodoc);
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para editar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }

          
          

        }
    }
}
</script>
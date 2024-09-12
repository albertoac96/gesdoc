<template>
  <v-dialog v-model="dialog" max-width="800" persistent>
    <v-card>
      <v-card-title class="text-h5 grey lighten-2">
        Relacionar documentos
      </v-card-title>

      <v-card-text>

        

          <v-row dense class="mt-5">
        <v-col cols="4">
          <v-treeview
            
            dense
            v-model="tree"
            :items="carpetas"
            item-key="idCarpeta"
            item-text="cCarpeta"
            color="warning"
            hoverable
            :open.sync="initiallyOpen"
            :active.sync="active"
           
          >
            <template v-slot:prepend="{ item, open }">
              <v-icon v-if="!item.file">
                {{ open ? "mdi-folder-open" : "mdi-folder" }}
              </v-icon>
            </template>

            <template slot="label" slot-scope="{ item }">
              <a @click="traeDocs(item)">{{ item.cCarpeta }}</a>
            </template>
          </v-treeview>
        
        </v-col>
        <v-col cols="8">
            <v-card>
                <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                </v-card-title>
                <v-data-table
                :headers="headers"
                :items="items"
                :search="search"
                show-select
                v-model="selected"
                item-key="idDocumento"
                :items-per-page="5"
                >
                <template v-slot:item.cTipoDoc="{ item }">
       
       
        <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                 
                    <v-icon v-bind="attrs" v-on="on" small >{{item.cIcono}}</v-icon>
             
                </template>

                <span>{{item.cTipoDoc}}</span>
              </v-tooltip>
       
       

      </template>

       <template v-slot:item.cResumenDoc="{ item }">
       
       
       <p class="ml-4 mt-4 font-weight-medium" v-if="item.cResumenDoc == ''">No hay resumen</p>
             <span  v-else v-html="AcortarString(item.cResumenDoc, 150, 1)"></span>
       
       

      </template>
                
                </v-data-table>
            </v-card>
        </v-col>
          </v-row>

            <v-chip
        v-for="item in selected"
        :key="item.idDocumento"
        class="ma-2"
        close
        outlined
        @click:close="fnQuitarSelected(item.idDocumento)"
      >
        {{ item.cTituloDoc }}
      </v-chip>


      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" text @click="$emit('close')"> Cancelar </v-btn>
        <v-btn color="success" text @click="Guardar()"> Guardar </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "dlgNuevoDocRel",
  props: ["idDoc"],
  components: {},
  data: () => ({
      selected: [],
      dialog: true,
        carpetas: [],
        vermax: true,
        vermin: false,

      initiallyOpen: ['0'],
      show: false,
     
      tree: [],
      active:[],
      open:[0],


      search: '',
        headers: [
            {text:"Tipo Doc", value:"cTipoDoc", width: 20},
          {
            text: 'Documento',
            align: 'start',
            value: 'cTituloDoc',
          },
          { text: 'Resumen', value: 'cResumenDoc' },
        ],
        items: [],

  }),
  mounted() {
      axios.get("/traecarpetas", 0).then((res) => {
            this.carpetas = res.data;
          
            
        });
  },
  created() {},
  beforeMount() {},
  watch: {},
  computed: {},
  methods: {
      verMax(){
      this.vermax = false;
      this.vermin = true;
    },
    verMin(){
      this.vermax = true;
      this.vermin = false;
    },
    fnQuitarSelected(id){
        var pos = this.selected.map(function(e) { return e.idDocumento; }).indexOf(id);
        this.selected.splice(pos, 1)
    },
      traeDocs(item){
        axios
          .get("/traedocsarel/" + item.idCarpeta)
          .then((res) => {
              this.items = res.data;
              //console.log(res.data);
        })
        .catch((error) => {
        });
      },
      Guardar(){
          if(this.selected.length == 0){
               this.$store.state.snack.text = "Escoge minimo un documento a relacionar";
              this.$store.state.snack.color = "error";
              this.$store.state.snack.model = "true";
              this.$store.state.snack.centered = true;
              return;
          }

          let InstFormData = new FormData();

            var cDesc = "";
            for(var i=0; i<this.selected.length; i++){
              cDesc = cDesc + "; " + this.selected[i].cTituloDoc;
            }
            cDesc.substr(-2);

            InstFormData.append('cDesc' , "Con: " + cDesc);
            InstFormData.append('idDocumento' , this.idDoc);
            InstFormData.append('tipo' , 'docsrel');
            InstFormData.append('items' , JSON.stringify(this.selected));
              axios
              .post("/addrel", InstFormData)
              .then((res) => {
                this.$emit('newItem', this.selected);
              })
              .catch((error) => {});


          
      }
  },
};
</script>
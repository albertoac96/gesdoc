<template>
  <div>
    <h5 class="mt-4 md-4">Etiquetas
       <v-btn v-if="$canBtn('agregar', 'No tiene permisos para agregar')" icon @click.stop="Asocia()"><v-icon>mdi-plus</v-icon></v-btn>
       <AsociaTags @nTags="pushNew($event)" v-if="dlgAsociaTags" :titulo="titulo" :idDoc="idDoc" @close="dlgAsociaTags=false"></AsociaTags>
    </h5>
    <v-row>
      <v-chip
        v-for="tag in tags"
        :key="tag.idEtiqueta"
        class="ma-2"
        close
        outlined
        @click:close="fnDeleteTag(tag)"
      >
        {{ tag.cEtiqueta }}
      </v-chip>
    </v-row>





  </div>
</template>
<script>
import AsociaTags from '../../dialogos/dlgCboEtiquetas.vue'
export default {
  name: "verEtiquetas",
  props: ["tags", "titulo", "idDoc"],
  components: {
    AsociaTags
  },
  data: () => ({
    dlgAsociaTags: false,


    activator: null,
      attach: null,
      colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'orange'],
      editing: null,
      editingIndex: -1,
      items: [
        { header: 'Select an option or create one' },
        {
          text: 'Foo',
          color: 'blue',
        },
        {
          text: 'Bar',
          color: 'red',
        },
      ],
      nonce: 1,
      menu: false,
      model: [
        {
          text: 'Foo',
          color: 'blue',
        },
      ],
      x: 0,
      search: null,
      y: 0,


  }),
  mounted() {
    let InstFormData = new FormData();
        InstFormData.append('idDoc' , this.idDoc);
    axios
      .post("/tags", InstFormData)
      .then((res) => {
        this.items = res.data.tags;
        this.model = res.data.tagsDoc;
    })
    .catch((error) => {
    });
  },
  created() {},
  beforeMount() {},
  watch: {
    model (val, prev) {
        if (val.length === prev.length) return

        this.model = val.map(v => {
          if (typeof v === 'string') {
            v = {
              text: v,
              color: this.colors[this.nonce - 1],
            }

            this.items.push(v)

            this.nonce++
          }

          return v
        })
      },
  },
  computed: {},
  methods: {
    edit (index, item) {
        if (!this.editing) {
          this.editing = item
          this.editingIndex = index
        } else {
          this.editing = null
          this.editingIndex = -1
        }
      },
      filter (item, queryText, itemText) {
        if (item.header) return false

        const hasValue = val => val != null ? val : ''

        const text = hasValue(itemText)
        const query = hasValue(queryText)

        return text.toString()
          .toLowerCase()
          .indexOf(query.toString().toLowerCase()) > -1
      },


      Asocia(){

        if(this.VerificaPermiso('agregar') == true){
        
        this.dlgAsociaTags = true;
         } else {
          this.$store.state.snack.text = "No tienes permisos para agregar elementos";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }

      },

      fnDeleteTag(item){
         if(this.$canSnack('eliminar', 'No tienes permisos para eliminar')){
          let InstFormData = new FormData();
          InstFormData.append('idElemento' , item.idEtiqueta);
          InstFormData.append('cDesc' , item.cEtiqueta);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'tags');
            axios
            .post("/deleterel", InstFormData)
            .then((res) => {
             
              var pos = this.tags.map(function(e) { return e.idRel; }).indexOf(item.idRel);
              this.tags.splice(pos, 1);
              this.$store.state.snack.text = "Se elimino la relación con la etiqueta";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
            })
            .catch((error) => {});
           
        } 
      },
       pushNew(items){

       
      this.$store.state.snack.text = "Asociación completa";
      this.$store.state.snack.color = "success";
      this.$store.state.snack.centered = false;
      this.$store.state.snack.model = "true";  
      
      
      for(var i = 0; i < items.length; i++){
        
        this.tags.push(items[i]);
        ////console.log(items[i]);
      }
      this.dlgAsociaTags = false;
    }
  },
};
</script>
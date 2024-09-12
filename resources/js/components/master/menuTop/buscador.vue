<template>

     <v-col cols="6">
      
        <v-text-field
        v-model="params.cBusqueda"
          class="mt-8"
          solo
          label="Buscar"
          prepend-inner-icon="mdi-magnify"
          append-icon="mdi-filter"
          @click:prepend-inner="fnBuscar()"
          @click:append="dialog=true"
          @keydown.enter.prevent="fnBuscar"
        >
        </v-text-field>
      
        <v-dialog
      v-model="dialog"
      max-width="500"
    >
      <v-card>
        <v-card-title class="text-h5">
          Filtros de busqueda
        </v-card-title>

        <v-card-text>
        

        <v-col cols="12">
          <v-text-field
          v-model="params.cBusqueda"
            label="Contiene las palabras"
          ></v-text-field>
          <v-checkbox
      v-model="checkbox"
      :label="`Checkbox 1: ${checkbox.toString()}`"
    ></v-checkbox>
    <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Buscar en"
          placeholder="Puede especificar solo ciertos campos"
          outlined
          dense
          chips
          multiple
        ></v-combobox>
         <v-divider></v-divider>
          <span>Ubicacion</span><v-btn icon><v-icon>mdi-folder</v-icon></v-btn>
         <v-text-field
            label="Año o intervalo"
            placeholder="ej. 1954 | 1960-1980 | -1990"
          ></v-text-field>

          <v-divider></v-divider>
           
        <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Tipo(s) de documento"
          outlined
          dense
          chips
          multiple
        ></v-combobox>
        <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Publicacion(es)"
          outlined
          dense
          chips
          multiple
        ></v-combobox>
         <v-combobox
          v-model="params.temas"
          :items="combosItems.temas"
          item-text="cTema"
          item-value="idTema"
          label="Tema(s)"
          outlined
          dense
          chips
          multiple
        ></v-combobox>
        <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Autor(es)"
          small
          outlined
          dense
          chips
          multiple
        ></v-combobox>
        
           <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="¿Buscar en notas?"
          small
          outlined
          dense
          chips
          multiple
        ></v-combobox>
         <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="¿Buscar en el contenido PDF?"
          small
          outlined
          dense
          chips
          multiple
        ></v-combobox>

         <v-divider></v-divider>
        <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Por el usuario creador"
          small
          outlined
          dense
          chips
          multiple
        ></v-combobox>
         <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Fecha de modificacion"
          small
          outlined
          dense
          chips
          multiple
        ></v-combobox>

         <v-divider></v-divider>
         <v-combobox
          v-model="filtros.tipodoc"
          :items="combosItems.tipodoc"
          label="Busquedas guardadas"
          small
          outlined
          dense
          chips
          multiple
        ></v-combobox>

        
      </v-col>

      

     


        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            color="green darken-1"
            text
            @click="dialog = false"
          >
            Cancelar
          </v-btn>

          <v-btn
            color="green darken-1"
            text
            @click="fnBuscar()"
          >
            Buscar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

        
      
    </v-col>

   



</template>
<script>
export default {
    name: "buscador",
    props: [],
    components:{

    },
    data: () => ({
      checkbox: false,
      params: {
        cBusqueda: "",
        idCarpetas: "",
        temas: [],
        iNota: 1,
        iPDF: 1,
        iBusqueda: 1,
        iTxtCompleto: 0,
        idAutor: "",
        cAnyoDe: "",
        cAnyoA: "",
        idPublicacion: "",

      },
        dialog: false,
        filtros:{
          tipodoc: "",
          temas: []
        },
        combosItems:{
          tipodoc: [],
          temas: []
        }
    }),
    mounted(){
      axios
          .get("/cbotemas")
          .then((res) => {
              this.combosItems.temas = res.data;

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
      fnBuscar(){
       
        var ruta = { name: "buscar", params: this.params };
        this.$router.push(ruta);
      }
    }
}
</script>
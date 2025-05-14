<template>
  <v-container>
    <h5 class="mt-10">Bienvenido al Sistema de Gestión de Documentos DEH</h5>
    <h3 class="mt-10">Archivos agregados recientemente</h3>

    <v-row class="fill-height" align="center" justify="center">
      <template v-for="item in docs">
        <v-col :key="item.idDocumento" cols="12" md="4">
          <v-card
            class="mx-auto"
            max-width="400"
            :to="'/veritem/' + item.idDocumento"
          >
            <v-img
              class="white--text align-end"
              height="200px"
              :src="traeImg(item.cImagen)"
            >
              <v-card-title>{{ item.cTipoDoc }}</v-card-title>
            </v-img>

            <v-card-subtitle class="pb-0">
              {{ item.cTituloDoc }}
            </v-card-subtitle>

            <v-card-text class="text--primary">
              <span
                v-if="item.cDesc"
                v-html="AcortarString(item.cDesc, 150, 1)"
              ></span>
              <span v-else>No hay resumen</span>
            </v-card-text>

          </v-card>
        </v-col>
      </template>
    </v-row>
  </v-container>
</template>
<script>
import txtEditor from "../biblioteca/tiptap.vue";
export default {
  name: "cpDashboard",
  components: {
    txtEditor,
  },
  data: () => ({
    docs: [],
  }),
  mounted() {
    this.inicio();
  },
  methods: {
    inicio() {
      axios
        .get("/docsrecientes")
        .then((res) => {
          this.docs = res.data;
        })
        .catch((error) => {});

        console.log("HOLA");

        var enviar = {
          dtocIndexDoc: -1,
          palette: "default",
          categories: "auto",
          suppressTools: false,
          input: "hola como estas",
          tool: "corpus.CorpuesMetadata"
        };

        axios
        .post("http://voyant-tools.org/trombone", enviar)
        .then((res) => {
          this.docs = res.data;
        })
        .catch((error) => {});
    },
    traeImg(img) {
      if (img == "docDefault.jpg" || img == "" || img == null) {
        return "/images/archivos/docDefaultOs.jpg";
      }
      return "/images/archivos/" + img;
    },
  },
};
</script>
<template>
  <v-menu offset-y>
    <template #activator="{ on: onMenu }">
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn icon v-on="{ ...onMenu, ...onTooltip }">
            <v-icon>mdi-export-variant</v-icon>
          </v-btn>
        </template>

        <span>Exportar Metadatos</span>
      </v-tooltip>
    </template>

    <v-list>
      <v-list-item>
        <v-list-item-title>BibText</v-list-item-title>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Dublin Core RDF</v-list-item-title>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>MARC 21</v-list-item-title>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>EndNote XML</v-list-item-title>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Zotero RDF</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
<script>
export default {
  name: "btnExportar",
  props: [],
  components: {},
  data: () => ({}),
  mounted() {},
  created() {},
  beforeMount() {},
  watch: {},
  computed: {},
  methods: {},
};
</script>
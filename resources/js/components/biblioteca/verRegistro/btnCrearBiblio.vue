<template>
  <v-menu offset-y>
    <template #activator="{ on: onMenu }">
      <v-tooltip bottom>
        <template #activator="{ on: onTooltip }">
          <v-btn icon v-on="{ ...onMenu, ...onTooltip }">
            <v-icon>mdi-text-short</v-icon>
          </v-btn>
        </template>

        <span>Crear bibliografía</span>
      </v-tooltip>
    </template>

    <v-list>
      <v-list-item>
        <v-list-item-title>APA</v-list-item-title>
      </v-list-item>
      <v-list-item>
        <v-list-item-title>Chicago</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
<script>
export default {
  name: "btnCrearBiblio",
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
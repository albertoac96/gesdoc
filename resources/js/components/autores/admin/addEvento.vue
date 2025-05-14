<template>
    <v-dialog v-model="dlg" max-width="900px" persistent>
        <v-card>
            <v-card-title class="text-h5">Evento</v-card-title>
            {{ item }}
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-combobox
                                v-model="editedItem.tipo"
                                :items="tiposEventos"
                                item-value="idTipo"
                                item-text="cNombre"
                                label="Tipo de evento"
                                outlined
                                dense
                            ></v-combobox>
                        </v-col>

                        <v-col cols="6">
                            <v-text-field
                                v-model="editedItem.dInicio"
                                label="Inicio (aaaa-mm-dd)"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="6">
                            <v-text-field
                                v-model="editedItem.dFin"
                                label="Fin (aaaa-mm-dd)"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-combobox
                                v-model="editedItem.relacion"
                                :items="tiposRel"
                                item-value="idRel"
                                item-text="cNombre"
                                label="Tipo de relacion"
                                outlined
                                dense
                            ></v-combobox>
                        </v-col>

                        <v-col cols="3">
                            <v-text-field
                                v-model="editedItem.relacion.EntOrigen"
                                :label="editedItem.relacion.EntOrigen"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="3">
                            <v-text-field
                                v-model="editedItem.relacion.cPropiedad"
                                label="Propiedad"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="6">
                            <v-text-field
                                v-model="editedItem.cDestino"
                                :label="editedItem.relacion.EntDestino"
                            ></v-text-field>
                            <v-autocomplete
                                v-model="editedItem.cDestino"
                                :items="filteredOptions"
                                :search-input.sync="searchQuery"
                                :label="editedItem.relacion.EntDestino"
                                no-data-text="No hay sugerencias"
                                clearable
                                allow-overflow
                                :filter="customFilter"
                                @change="onValueChange"
                            ></v-autocomplete>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="editedItem.nombre"
                                label="Nombre del evento"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field
                                v-model="editedItem.enlace"
                                label="Texto de enlace"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12">
                            <v-textarea
                                v-model="editedItem.desc"
                                name="input-7-1"
                                label="Descripción"
                            ></v-textarea>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="$emit('cerrar')"
                    >Cancel</v-btn
                >
                <v-btn color="blue darken-1" text @click="guardar()">OK</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "addEvento",
    props: ["clave", "id", "item", "dlg", "tipo", "idPadre"],
    components: {},
    data: () => ({
        selected: [2],

        editedIndex: -1,
        delItem: 0,
        tiposEventos: [],
        tiposRel: [],
        editedItem: {
            tipo: [],
            dInicio: null,
            dFin: null,
            relacion: "",
            nombre: "",
            enlace: "",
            desc: "",
            cDestino: ""
        },
        defaultItem: {
            tipo: [],
            dInicio: null,
            dFin: null,
            relacion: "",
            nombre: "",
            enlace: "",
            desc: "",
            cDestino: ""
        },
        menu1: false,
        menu2: false,
        items: [],
        searchQuery: "", // Entrada del usuario
        filteredOptions: [] // Opciones filtradas
    }),
    mounted() {
        this.initialize();
    },
    created() {},
    beforeMount() {},
    watch: {
        searchQuery(newQuery) {
          if (newQuery.length > 2) {  // Consultar API solo si el usuario ha escrito más de 2 caracteres
            var EntDestino = this.editedItem.relacion.EntDestinoID;
            axios.get(`/eventos/sugerencias/?query=${newQuery}&entdestino=${EntDestino}`).then(response => {
              this.filteredOptions = response.data;
            });
          } else {
            this.filteredOptions = [];
          }
        },
    },
    computed: {},
    methods: {
        initialize() {
            axios
                .get("/eventos/tipos")
                .then(res => {
                    this.tiposEventos = res.data;
                    axios
                        .get("/cidoc/relacion/show")
                        .then(res => {
                            var nodes = res.data;

                            if (this.tipo === "nuevo") {
                                console.log("NUEVO");
                                if (this.item === 0) {
                                    this.editedItem.idPadre = 0;
                                    const filteredNodes = nodes.filter(
                                        node => node.EntOrigenID === "E21"
                                    );
                                    this.tiposRel = filteredNodes;
                                } else {
                                    this.editedItem.idPadre = this.$store.state.red.item.idEvento;
                                    var cDestino = this.item.cDestino;
                                    if(this.item.cDestino === 'E39'){
                                        cDestino = 'E21';
                                    }
                                    console.log(nodes);
                                    const filteredNodes = nodes.filter(
                                        node =>
                                            node.EntOrigenID === cDestino
                                    );
                                    this.tiposRel = filteredNodes;
                                }
                            } else {
                                console.log("Editar");
                                let foundItem = this.tiposEventos.find(
                                    tiposEvento =>
                                        tiposEvento.idTipo === this.item.idTipo
                                );
                                this.editedItem.tipo = foundItem;
                                foundItem = this.tiposRel.find(
                                    tiposRel =>
                                        tiposRel.idRel ===
                                        this.item.idRelacionCidoc
                                );
                                this.editedItem.relacion = foundItem;
                                console.log(foundItem);
                                this.editedItem.dInicio = this.item.dFechaInicio;
                                this.editedItem.dFin = this.item.dFechaFin;
                                this.editedItem.nombre = this.item.cEvento;
                                this.editedItem.enlace = this.item.cEnlace;
                                this.editedItem.desc = this.item.cDescripcion;
                                this.editedItem.cDestino = this.item.cDestino;
                            }
                        })
                        .catch(error => {});
                })
                .catch(error => {});
        },
        guardar() {
            console.log(this.$store.state.red.item)
            this.editedItem.idOrigen = this.$route.params.id;
            console.log(this.editedItem);

            axios
            .post("/eventos/new", this.editedItem)
            .then((res) => {
            this.initialize();
            this.$emit('cerrar');
            })
            .catch((error) => {
            });
        },
        // Maneja el texto ingresado, sin importar si está en las opciones
        handleInput(value) {
            this.editedItem.cDestino = value;
            console.log("Texto ingresado:", this.editedItem.cDestino);
        },
        customFilter(item, queryText, itemText) {
            // Permitir todos los valores, incluso los no presentes en las opciones
            return true;
        },
        onValueChange(value) {
            console.log(value);
            // Si el valor no está en la lista de opciones, agrégalo
            if (value && !this.filteredOptions.includes(value)) {
            this.filteredOptions.push(value);
            }
        }
    }
};
</script>

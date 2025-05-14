<template>
  <v-card>
    <v-card-text>
      <v-btn @click="addEvento('nuevo')">Agregar Evento Hijo</v-btn>
    

      <!-- Tabla de eventos -->
      <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-1"
        item-value="id"
        dense
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Eventos</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
        </template>

        <!-- Renderizar fuentes como chips -->
        <template v-slot:item.fuentes="{ item }">
          <v-chip-group column>
            <v-chip
              v-for="(fuente, index) in item.fuentes"
              :key="index"
              color="primary"
              dark
            >
              {{ fuente }}
            </v-chip>
          </v-chip-group>
        </template>

        <template v-slot:item.idTipoEvento="{ item }">
  <v-chip
    color="primary"
    outlined
    small
  >
    {{ getTipoNombre(item.idTipoEvento) }} <!-- Mostrar el nombre del tipo -->
  </v-chip>
</template>

 <template v-slot:item.actorRelacionado="{ item }">
  
  {{ item.actorRelacionado.nombre }}

  <v-chip
    color="primary"
    dark
    small
  >
    {{ getTipoActor(item.idTipoActorRel) }} <!-- Mostrar el nombre del tipo -->
  </v-chip>
</template>


        <!-- Botón para ver actividad y agregar evento -->
        <template v-slot:item.actions="{ item }">
          <v-btn small color="blue darken-1" @click="verActividad(item)">
            Ver Actividad
          </v-btn>
          <v-btn small color="green darken-1" @click="openAddEventModal(item)">
            Agregar Evento
          </v-btn>
        </template>
      </v-data-table>
    </v-card-text>

    <!-- Modal para agregar evento -->
    <v-dialog v-model="dialogEvent" max-width="600px">
      <v-card>
        <v-card-title class="text-h5">Agregar Evento</v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field
                  label="Fecha"
                  v-model="newEvent.fecha"
                  type="date"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                 
                            <v-select
                                v-model="newEvent.tipoEvento"
                                :items="tiposEventos"
                                item-value="idTipoEvento"
                                item-text="cNombre"
                                label="Tipo de evento"
                                
                                
                            ></v-select>
                        
              </v-col>
              <v-col cols="12">
                <v-text-field
                  label="Tipo de Relación"
                  v-model="newEvent.tipoRelacion"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                 
                            <v-select
                                v-model="newEvent.tipoActorRel"
                                :items="tiposActor"
                                item-value="idTipoActor"
                                item-text="cNombre"
                                label="Tipo de actor relacionado"
                                @change="fnLlenaComboActores"
                                
                            ></v-select>
                        
              </v-col>
              <v-col cols="12" sm="6">
                <v-combobox
                                v-model="newEvent.actorRelacionado"
                                :items="cboActores"
                                item-value="idActor"
                                item-text="cNombre"
                                label="Selecciona el actor"
                                
                                
                            ></v-combobox>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  label="Descripción"
                  v-model="newEvent.descripcion"
                ></v-textarea>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  label="Fuentes (separadas por comas)"
                  v-model="newEvent.fuentes"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="closeAddEventModal">
            Cancelar
          </v-btn>
          <v-btn color="green darken-1" text @click="saveEvent">
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>


export default {
  name: "adminAutor",
  props: [],
  components: {
    
  },
  data: () => ({
    dialogEvent: false,
    tiposEventos: [
      { idTipoEvento: 1, cNombre: 'Personal' },
      { idTipoEvento: 2, cNombre: 'Academico' },
      { idTipoEvento: 3, cNombre: 'Publicación' },
      // Agregar más tipos de eventos según sea necesario
    ],
    tiposActor: [
      { idTipoActor: 1, cNombre: 'Persona' },
      { idTipoActor: 2, cNombre: 'Institución' },
      { idTipoActor: 3, cNombre: 'Publicación periodica' },
      { idTipoActor: 4, cNombre: 'Grupo' },
      { idTipoActor: 5, cNombre: 'Lugar' },
      // Agregar más tipos de eventos según sea necesario
    ],
    cboActores: [],
    items: [], // Lista de eventos
    newEvent: {
      fecha: '',
      tipoEvento: '',
      tipoActorRel: '',
      tipoRelacion: '',
      actorRelacionado: '',
      descripcion: '',
      fuentes: '',
    },
    defEvent: {
      fecha: '',
      tipoEvento: '',
      tipoRelacion: '',
      actorRelacionado: '',
      descripcion: '',
      fuentes: '',
    },
    headers: [
      { text: 'Fecha', value: 'dFecha' },
      { text: 'Tipo de Evento', value: 'idTipoEvento' },
      { text: 'Tipo de Relación', value: 'cRelacion' },
      { text: 'Actor Relacionado', value: 'actorRelacionado' },
      { text: 'Descripción', value: 'cDescripcion' },
     
    ],
  }),
  mounted() {},
  created() {
    this.initialize();
  },
  watch: {
    '$store.state.red.item'() {
      this.initialize();
    },
  },
  methods: {
    initialize() {
      console.log('info');
      axios
        .get("/eventos/deactor/" + this.$route.params.id + "/1")
        .then((res) => {
          this.items = res.data;
          console.log(this.items);
          this.$store.state.eventos = res.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    getTipoNombre(id) {
    const tipo = this.tiposEventos.find(tipo => tipo.idTipoEvento === id);
    return tipo ? tipo.cNombre : 'Desconocido'; // Si no se encuentra el tipo, muestra 'Desconocido'
  },
  getTipoActor(id) {
    const tipo = this.tiposActor.find(tipo => tipo.idTipoActor === id);
    return tipo ? tipo.cNombre : 'Desconocido'; // Si no se encuentra el tipo, muestra 'Desconocido'
  },
    addEvento(tipo) {
      console.log(this.$store.state.red.item);
      this.tipoEvento = tipo;
      this.dialogEvent = true;
    },
    cerrarDialog() {
      this.dialogEvent = false;
      this.initialize();
    },
    
    closeAddEventModal() {
      this.newEvent = Object.assign({}, this.defEvent)
      this.dialogEvent = false;
    },
    saveEvent() {
      const fuentesArray = this.newEvent.fuentes.split(',').map(f => f.trim());
      const newEvent = {
        ...this.newEvent,
        fuentes: fuentesArray,
      };
      this.newEvent.idActor = this.$route.params.id;
      this.newEvent.idTipoActor = 1;
      console.log(newEvent);
      axios
        .post("/eventos/nuevo", this.newEvent)
        .then((res) => {
          this.initialize();
        })
        .catch((error) => {
          console.error(error);
        });
      //this.items.push(newEvent); // Agregar el evento a la tabla
      
      this.closeAddEventModal();
    },
    fnLlenaComboActores() {
      console.log(this.newEvent.tipoActorRel);
      if(this.newEvent.tipoActorRel.idTipoActor === null) return;
      axios
        .get("/eventos/actoresde/" + this.newEvent.tipoActorRel)
        .then((res) => {
          this.cboActores = res.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

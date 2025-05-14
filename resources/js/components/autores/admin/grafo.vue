<template>
  <div>
    <!-- FILTROS -->
    <div class="filtros">
      <label>
        Fecha inicio:
        <input type="date" v-model="filtro.fechaInicio">
      </label>
      <label>
        Fecha fin:
        <input type="date" v-model="filtro.fechaFin">
      </label>
      <label>
        Tipo de evento:
        <select v-model="filtro.idTipoEvento">
          <option value="">Todos</option>
          <option v-for="e in tiposEvento" :key="e" :value="e">{{ getTipoNombre(e) }}</option>
        </select>
      </label>
      <label>
        Tipo actor relacionado:
        <select v-model="filtro.idTipoActorRel">
          <option value="">Todos</option>
          <option v-for="t in tiposActorRel" :key="t" :value="t">{{ getTipoActor(t) }}</option>
        </select>
      </label>
      <label>
        Actor relacionado:
        <select v-model="filtro.actorRelacionado">
          <option value="">Todos</option>
          <option v-for="a in actoresRelacionadosUnicos" :key="a" :value="a">{{ a }}</option>
        </select>
      </label>
    </div>

    <!-- SVG -->
    <svg ref="svg" :width="width" :height="height"></svg>
  </div>
</template>

<script>
import * as d3 from 'd3';

export default {
  name: 'GraphComponent',
  props: {
    eventos: Array,
    idPrincipal: Number,
    nombrePrincipal: String
    
  },
  data() {
    return {
      width: 1000,
      height: 700,
      filtro: {
        fechaInicio: '',
        fechaFin: '',
        idTipoEvento: '',
        idTipoActorRel: '',
        actorRelacionado: ''
      },
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
    };
  },
  computed: {
    eventosFiltrados() {
      return this.eventos.filter(e => {
        const fecha = new Date(e.dFecha);
        const inicio = this.filtro.fechaInicio ? new Date(this.filtro.fechaInicio) : null;
        const fin = this.filtro.fechaFin ? new Date(this.filtro.fechaFin) : null;

        return (!inicio || fecha >= inicio) &&
               (!fin || fecha <= fin) &&
               (!this.filtro.idTipoEvento || e.idTipoEvento === this.filtro.idTipoEvento) &&
               (!this.filtro.idTipoActorRel || e.idTipoActorRel === this.filtro.idTipoActorRel) &&
               (!this.filtro.actorRelacionado || e.actorRelacionado?.nombre === this.filtro.actorRelacionado);
      });
    },
    tiposEvento() {
      return [...new Set(this.eventos.map(e => e.idTipoEvento).filter(Boolean))];
    },
    tiposActorRel() {
      return [...new Set(this.eventos.map(e => e.idTipoActorRel).filter(Boolean))];
    },
    actoresRelacionadosUnicos() {
      return [...new Set(this.eventos.map(e => e.actorRelacionado?.nombre).filter(Boolean))];
    }
  },
  watch: {
    eventosFiltrados() {
      this.dibujarGrafo(); // Redibuja cada vez que cambian los filtros
    }
  },
  mounted() {
    this.dibujarGrafo();
  },
  methods: {
    getTipoNombre(id) {
    const tipo = this.tiposEventos.find(tipo => tipo.idTipoEvento === id);
    return tipo ? tipo.cNombre : 'Desconocido'; // Si no se encuentra el tipo, muestra 'Desconocido'
  },
  getTipoActor(id) {
    const tipo = this.tiposActor.find(tipo => tipo.idTipoActor === id);
    return tipo ? tipo.cNombre : 'Desconocido'; // Si no se encuentra el tipo, muestra 'Desconocido'
  },


    transformarEventosAGrafo(eventos) {
      const nodesMap = new Map();
      const links = [];

      eventos.forEach(evento => {
        const { idActor, idActorRel, actorRelacionado, cRelacion, cDescripcion, dFecha } = evento;

        if (!nodesMap.has(idActor)) {
          nodesMap.set(idActor, { id: idActor, nombre: this.nombrePrincipal });
        }

        if (!nodesMap.has(idActorRel)) {
          nodesMap.set(idActorRel, {
            id: idActorRel,
            nombre: actorRelacionado?.nombre || 'Desconocido'
          });
        }

        links.push({
          source: idActor,
          target: idActorRel,
          tipo: cRelacion,
          descripcion: cDescripcion,
          fecha: dFecha
        });
      });

      const connectionCounts = {};
      links.forEach(link => {
        connectionCounts[link.source] = (connectionCounts[link.source] || 0) + 1;
        connectionCounts[link.target] = (connectionCounts[link.target] || 0) + 1;
      });

      const nodes = Array.from(nodesMap.values()).map(node => ({
        ...node,
        size: 6 + (connectionCounts[node.id] || 1) * 2
      }));

      return { nodes, links };
    },

    dibujarGrafo() {
      const svg = d3.select(this.$refs.svg);
      svg.selectAll('*').remove(); // limpiar

      const { nodes, links } = this.transformarEventosAGrafo(this.eventosFiltrados);

      const zoomGroup = svg.append('g');
      const zoom = d3.zoom()
        .scaleExtent([0.3, 5])
        .on('zoom', event => zoomGroup.attr('transform', event.transform));
      svg.call(zoom);

      const link = zoomGroup.append('g')
        .attr('stroke', '#999')
        .attr('stroke-opacity', 0.6)
        .selectAll('path')
        .data(links)
        .enter().append('path')
        .attr('fill', 'none')
        .attr('stroke-width', 1.5);

      const node = zoomGroup.append('g')
        .selectAll('circle')
        .data(nodes)
        .enter().append('circle')
        .attr('r', d => d.size)
        .attr('fill', d => d.id === this.idPrincipal ? '#ff6600' : '#69b3a2')
        .call(d3.drag()
          .on('start', dragstarted)
          .on('drag', dragged)
          .on('end', dragended));

      const label = zoomGroup.append('g')
        .selectAll('text')
        .data(nodes)
        .enter().append('text')
        .text(d => d.nombre)
        .attr('font-size', '12px')
        .attr('dx', 10)
        .attr('dy', 4);

      const simulation = d3.forceSimulation(nodes)
        .force('link', d3.forceLink(links).id(d => d.id).distance(150))
        .force('charge', d3.forceManyBody().strength(-300))
        .force('center', d3.forceCenter(this.width / 2, this.height / 2))
        .on('tick', ticked);

      function ticked() {
        link.attr('d', d => {
          const dx = d.target.x - d.source.x;
          const dy = d.target.y - d.source.y;
          const dr = Math.sqrt(dx * dx + dy * dy);
          return `M${d.source.x},${d.source.y}A${dr},${dr} 0 0,1 ${d.target.x},${d.target.y}`;
        });

        node.attr('cx', d => d.x).attr('cy', d => d.y);
        label.attr('x', d => d.x).attr('y', d => d.y);
      }

      function dragstarted(event, d) {
        if (!event.active) simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
      }

      function dragged(event, d) {
        d.fx = event.x;
        d.fy = event.y;
      }

      function dragended(event, d) {
        if (!event.active) simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
      }
    }
  }
};
</script>

<style scoped>
svg {
  border: 1px solid #ccc;
  background: #f9f9f9;
}
.filtros {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 10px;
}
.filtros label {
  display: flex;
  flex-direction: column;
  font-size: 14px;
}
</style>


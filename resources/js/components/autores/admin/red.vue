<template>
    <v-card>
      <div>
        <svg ref="graph" :width="width" :height="height"></svg>
      </div>
    </v-card>
  </template>
  
  <script>
  import * as d3 from "d3";
  export default {
    name: "red",
    data() {
      return {
        nodes: [
          { id: "1", name: "Miguel Othon de Mendizabal", type: "person" },
          { id: "2", name: "Ciudad de México", type: "place" },
          { id: "3", name: "Miguel Mendizabal Herrera", type: "person" },
          { id: "4", name: "Evento de Nacimiento", type: "event" },
          { id: "5", name: "Carmen Romani Quijano", type: "person" },
          { id: "6", name: "1890-07-02", type: "timespan" },
          { id: "7", name: "geneanet.org", type: "fuente" },
          { id: "8", name: "Evento Bautizo", type: "event" },
          { id: "9", name: "1890-07-13", type: "timespan" },
          { id: "10", name: "Certificacion", type: "event" },
          { id: "11", name: "1903-12-16", type: "timespan" },
          { id: "12", name: "AHUNAM", type: "fuente" },
          { id: "13", name: "Ingreso MN", type: "event" },
          { id: "14", name: "1910", type: "timespan" },
          { id: "15", name: "alumno pensionado", type: "note" },
          { id: "16", name: "Museo Nacional", type: "institucion" },
        ],
        links: [
          { source: "1", target: "4", name: "Nacimiento" },
          { source: "4", target: "2", name: "Lugar" },
          { source: "3", target: "4", name: "Padre" },
          { source: "5", target: "4", name: "Madre" },
          { source: "6", target: "4", name: "Fecha" },
          { source: "7", target: "4", name: "Fuente" },
          { source: "8", target: "1", name: "evento" },
          { source: "9", target: "8", name: "fecha" },
          { source: "8", target: "2", name: "lugar" },
          { source: "10", target: "1", name: "evento" },
          { source: "10", target: "11", name: "evento" },
          { source: "10", target: "12", name: "evento" },
          { source: "13", target: "1", name: "evento" },
          { source: "13", target: "14", name: "timespan" },
          { source: "13", target: "15", name: "has note" },
          { source: "13", target: "16", name: "lugar" },
          { source: "16", target: "2", name: "lugar" },
        ],
        width: 800,
        height: 600,
        currentNodes: [], // Nodos mostrados actualmente
        currentLinks: [], // Enlaces mostrados actualmente
        simulation: null, // Para almacenar la simulación y reiniciar cuando sea necesario
      };
    },
    mounted() {
      this.initialize("1"); // Inicializar con el nodo "1"
    },
    methods: {

        getColorByType(type) {
    const colorMap = {
      person: "#69b3a2",   // Verde
      place: "#ff6b6b",    // Rojo
      event: "#4dabf7",    // Azul
      timespan: "#ffd700", // Dorado
      default: "#d3d3d3"   // Gris (por defecto)
    };
    return colorMap[type] || colorMap.default;
  },
  // Inicializa el gráfico mostrando solo las relaciones del nodo seleccionado
  initialize(nodeId) {
    const initialLinks = this.links.filter(
      (link) => link.source === nodeId || link.target === nodeId
    );

    const initialNodeIds = [
      nodeId,
      ...initialLinks.map((link) => (link.source === nodeId ? link.target : link.source)),
    ];

    this.currentNodes = this.nodes.filter((node) => initialNodeIds.includes(node.id));
    this.currentLinks = initialLinks;

    this.renderGraph();
  },

  // Renderiza el gráfico con los nodos y enlaces actuales
  renderGraph() {
    const svg = d3.select(this.$refs.graph).html(""); // Limpiar el gráfico anterior
    const container = svg.append("g");

    // Configurar el zoom
    const zoom = d3
      .zoom()
      .scaleExtent([0.1, 4]) // Rango de zoom
      .on("zoom", (event) => {
        container.attr("transform", event.transform); // Aplicar zoom al grupo
      });

    svg.call(zoom); // Aplicar zoom al SVG

    // Crear una simulación de fuerza con D3 para los nodos y enlaces
    this.simulation = d3
      .forceSimulation(this.currentNodes)
      .force("link", d3.forceLink(this.currentLinks).id((d) => d.id))
      .force("charge", d3.forceManyBody().strength(-400))
      .force("center", d3.forceCenter(this.width / 2, this.height / 2));

    this.updateGraph(container);
  },

  // Actualiza el gráfico con los nuevos nodos y enlaces
  updateGraph(container) {
    // Dibujar los enlaces
    const link = container
      .append("g")
      .selectAll("line")
      .data(this.currentLinks)
      .enter()
      .append("line")
      .attr("stroke", "#aaa")
      .attr("stroke-width", 2);

    // Añadir texto a los enlaces
    /*const linkText = container
      .append("g")
      .selectAll("text")
      .data(this.currentLinks)
      .enter()
      .append("text")
      .attr("dy", -5)
      .attr("fill", "black")
      .style("font-size", "12px")
      .text((d) => d.name);*/

    // Dibujar los nodos y permitir que se puedan arrastrar
    const node = container
      .append("g")
      .selectAll("circle")
      .data(this.currentNodes)
      .enter()
      .append("circle")
      .attr("r", 10)
      .attr("fill", (d) => this.getColorByType(d.type)) // Asignar color basado en el tipo
      .call(
        d3
          .drag() // Permitir arrastrar nodos
          .on("start", (event, d) => this.dragStarted(event, d, this.simulation))
          .on("drag", (event, d) => this.dragged(event, d))
          .on("end", (event, d) => this.dragEnded(event, d, this.simulation))
      )
      .on("click", (event, d) => {
        if (!d.expanded) {
          this.expandNode(d.id); // Expande el nodo seleccionado
          console.log(d);
          d.expanded = true; // Marcar el nodo como expandido
        } else {
          this.closeNode(d.id); // Cierra el nodo si ya estaba expandido
          console.log(d);
          d.expanded = false; // Marcar el nodo como cerrado
        }
      });

    // Añadir etiquetas a los nodos
    const nodeLabels = container
      .append("g")
      .selectAll("text")
      .data(this.currentNodes)
      .enter()
      .append("text")
      .attr("dy", -15)
      .attr("text-anchor", "middle")
      .attr("fill", "black")
      .style("font-size", "12px")
      .text((d) => d.name);

    // Actualizar posiciones en cada tick de la simulación
    this.simulation.on("tick", () => {
      link
        .attr("x1", (d) => d.source.x)
        .attr("y1", (d) => d.source.y)
        .attr("x2", (d) => d.target.x)
        .attr("y2", (d) => d.target.y);

     

      node.attr("cx", (d) => d.x).attr("cy", (d) => d.y);

      nodeLabels.attr("x", (d) => d.x).attr("y", (d) => d.y);
    });
  },

  // Función para expandir un nodo seleccionado y agregar sus relaciones al gráfico
  expandNode(nodeId) {
    const newLinks = this.links.filter(
      (link) => (link.source === nodeId || link.target === nodeId) && !this.currentLinks.includes(link)
    );

    const newNodeIds = [
      ...newLinks.map((link) => (link.source === nodeId ? link.target : link.source)),
    ];

    const newNodes = this.nodes.filter((node) => newNodeIds.includes(node.id) && !this.currentNodes.includes(node));

    // Agregar los nuevos nodos y enlaces al gráfico
    this.currentNodes = [...this.currentNodes, ...newNodes];
    this.currentLinks = [...this.currentLinks, ...newLinks];
    console.log(this.currentLinks);
    // Reiniciar la simulación con los nuevos datos
    this.simulation.nodes(this.currentNodes);
    this.simulation.force("link").links(this.currentLinks);
    this.simulation.alpha(1).restart();

    // Actualizar el gráfico
    this.renderGraph();
  },

  // Función para cerrar un nodo y eliminar sus hijos
  closeNode(nodeId) {
    const linksToRemove = this.currentLinks.filter(
      (link) => link.source === nodeId || link.target === nodeId
    );

    const nodeIdsToRemove = linksToRemove.map(link => link.source === nodeId ? link.target : link.source);

    // Filtrar los nodos hijos que están conectados al nodoId y eliminarlos
    this.currentNodes = this.currentNodes.filter(node => !nodeIdsToRemove.includes(node.id));
    this.currentLinks = this.currentLinks.filter(link => !linksToRemove.includes(link));

    console.log(this.currentLinks);

    // Reiniciar la simulación con los datos restantes
    this.simulation.nodes(this.currentNodes);
    this.simulation.force("link").links(this.currentLinks);
    this.simulation.alpha(1).restart();

    // Actualizar el gráfico
    this.renderGraph();
  },

  // Funciones para manejar el arrastre de nodos
  dragStarted(event, d, simulation) {
    if (!event.active) simulation.alphaTarget(0.3).restart(); // Reiniciar la simulación
    d.fx = d.x;
    d.fy = d.y;
  },
  dragged(event, d) {
    d.fx = event.x; // Fijar la posición X mientras se arrastra
    d.fy = event.y; // Fijar la posición Y mientras se arrastra
  },
  dragEnded(event, d, simulation) {
    if (!event.active) simulation.alphaTarget(0); // Detener la simulación
    d.fx = null;
    d.fy = null; // Soltar el nodo cuando se suelta el mouse
  },
}



  };
  </script>
  
  <style scoped>
  svg {
    border: 1px solid #ccc;
  }
  </style>
  
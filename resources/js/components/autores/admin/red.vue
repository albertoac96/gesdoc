<template>
    <v-card>
        <div class="row">
            
            <!--<div class="row col-3">
               
                <eventos @resetGrafo="resetGrafo"></eventos>
            </div>-->
            <div class="row col-9">
                <v-btn @click="guardarRed()">Guardar</v-btn>
                <v-btn @click="AddActor()">Agregar actor</v-btn>
                <svg ref="graph" width="100%" height="80vh"></svg>
            </div>
            <div class="row col-3">
                <info @resetGrafo="resetGrafo"></info>
            </div>
        </div>


        <v-dialog v-model="dlgAddActor" max-width="400px" persistent>
            <v-card>
                <v-card-title>Agregar actor</v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row dense>
                            <v-col cols="12">
                                <v-combobox
                                    v-model="newActor.tipo"
                                    :items="tipoActores"
                                    item-value="id"
                                    item-text="name"
                                    label="Tipo de actor"
                                    outlined
                                    dense
                                    @change="traerActores()"
                                ></v-combobox>
                            </v-col>
                            <v-col cols="12">
                                <v-combobox
                                    v-model="newActor.select"
                                    :items="listActores"
                                    item-value="id"
                                    item-text="cNombre"
                                    label="Actor"
                                    outlined
                                    dense
                                ></v-combobox>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="error" @click="dlgAddActor=false">Cancel</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="agregarActor()">Agregar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import eventos from "./eventos.vue";
import * as d3 from "d3";
import info from "./info.vue";
export default {
    name: "red",
    components: {
        eventos,
        info
    },
    data() {
        return {
            nodes: [],
            links: [],
            tipoActores: [
                { id: "E21", name: "Persona", table: "cat_autores" },
                { id: "E39", name: "Institución", table: "cat_instituciones" },
                { id: "E74", name: "Publicación", table: "cat_publicaciones" },
                { id: "E53", name: "Lugar", table: "cat_lugares" }
            ],
            tipoEnlace: [
                { id: "1", name: "Dirigido" },
                { id: "2", name: "No Dirigido" },
                { id: "3", name: "Bidireccional" }
            ],
            listActores: [],
            newActor: {
                tipo: null
            },
            width: 800,
            height: 600,
            currentNodes: [], // Nodos mostrados actualmente
            currentLinks: [], // Enlaces mostrados actualmente
            simulation: null, // Para almacenar la simulación y reiniciar cuando sea necesario
            container: null,
            nodeClickeData: null,
            dlgAddActor: false
        };
    },
    mounted() {
        this.initialize(); // Inicializar con el nodo "1"
    },
    watch:{
        nodeClickeData(ahora, antes){
            if(ahora === null || antes === null) return;
            
            if(ahora.id === antes.id) return;

            console.log('antes:' + antes.id);
            console.log('ahora:' + ahora.id);

            var nodes = [];
            var links = [];
            
            links.push({
                  source: antes.id,
                  target: ahora.id,
                  name: 'cEnlace',
              });
              this.resetGrafo(nodes, links, true);

            this.nodeClickeData = null;
        }
    },
    methods: {
        getColorByType(type) {
            const colorMap = {
                person: "#69b3a2", // Verde
                place: "#ff6b6b", // Rojo
                event: "#4dabf7", // Azul
                timespan: "#ffd700", // Dorado
                default: "#d3d3d3" // Gris (por defecto)
            };
            return colorMap[type] || colorMap.default;
        },
        // Inicializa el gráfico mostrando solo las relaciones del nodo seleccionado
        initialize() {

            this.renderGraph();

            
            /*axios
                .get("/eventos/autor/" + this.$route.params.id)
                .then(res => {
                    this.$store.state.red.nodes = res.data.nodes;
                    this.$store.state.red.links = res.data.links;
                    //this.renderGraph();
                    this.resetGrafo(this.$store.state.red.nodes, this.$store.state.red.links, true);
                    
                })
                .catch(error => {});*/

                

            
        },

        // Renderiza el gráfico con los nodos y enlaces actuales
        renderGraph() {
            const svg = d3.select(this.$refs.graph).html(""); // Limpiar el gráfico anterior
            this.container = svg.append("g");

            // Configurar el zoom
            const zoom = d3
                .zoom()
                .scaleExtent([0.1, 4]) // Rango de zoom
                .on("zoom", event => {
                    this.container.attr("transform", event.transform); // Aplicar zoom al grupo
                });
                

            svg.call(zoom); // Aplicar zoom al SVG

            // Crear una simulación de fuerza con D3 para los nodos y enlaces
            this.simulation = d3
                .forceSimulation(this.$store.state.red.nodes)
                .force("link", d3.forceLink(this.$store.state.red.links).id(d => d.id).distance(100).strength(0.5)) // Ajustar la fuerza del enlace
                .force("charge", d3.forceManyBody().strength(-50)) // Reducir la fuerza de atracción/repulsión
                .force("center", d3.forceCenter(this.width / 2, this.height / 2).strength(0.05)); // Reducir la fuerza hacia el centro
                

           
            this.updateGraph(this.container);
        },



        updateGraph(container) {
  const self = this; // Guardamos el contexto del componente Vue

  // Resolver los enlaces para asegurar que source y target sean objetos reales, no solo índices
  this.$store.state.red.links.forEach(link => {
    if (typeof link.source === "string" || typeof link.source === "number") {
      link.source = this.$store.state.red.nodes.find(node => node.id === link.source);
    }
    if (typeof link.target === "string" || typeof link.target === "number") {
      link.target = this.$store.state.red.nodes.find(node => node.id === link.target);
    }
  });

  // Actualizar enlaces
  this.links = container
    .selectAll(".link")
    .data(this.$store.state.red.links, d => `${d.source.id}-${d.target.id}`);

  // Eliminar enlaces que ya no existan
  this.links.exit().remove();

  // Agregar nuevos enlaces (asegurarse de que siempre estén debajo de los nodos)
  this.links = this.links
    .enter()
    .append("line")
    .attr("class", "link")
    .style("stroke", "#aaa")
    .style("stroke-width", 2)
    .merge(this.links);

  // Reordenar los enlaces para garantizar que siempre estén al fondo
  this.links.lower();

  // Actualizar nodos
  this.nodes = container
    .selectAll(".node")
    .data(this.$store.state.red.nodes, d => d.id);

  // Eliminar nodos que ya no existan
  this.nodes.exit().remove();

  // Agregar nuevos nodos
  const nodeEnter = this.nodes
    .enter()
    .append("circle")
    .attr("class", "node")
    .attr("r", 10)
    .style("fill", "steelblue")
    .style("stroke", "white")
    .style("stroke-width", 1.5)
    .each(d => {
      // Asignar una posición almacenada si existe
      if (d.fx !== undefined && d.fy !== undefined) {
        d.x = d.fx;
        d.y = d.fy;
      }
    })
    .call(
      d3
      .drag()
        .on("start", function (event, d) {
          console.log("Drag started");
          // Iniciar el arrastre y fijar la posición para que se siga el cursor
          if (!event.active) self.simulation.alphaTarget(0.3).restart();
          d.fx = d.x;
          d.fy = d.y;
        })
        .on("drag", function (event, d) {
          console.log("Dragging");
          // Durante el arrastre, actualizar `fx` y `fy` con la posición del cursor
          d.fx = event.x;
          d.fy = event.y;
        })
        .on("end", function (event, d) {
          console.log("Drag ended");
          // Mantener el nodo en la posición donde fue soltado
          if (!event.active) self.simulation.alphaTarget(0);
          d.fx = d.x;
          d.fy = d.y;
        })
    )
    .on("mouseover", function (event, d) {
      self.highlightPathToRoot(d);
    })
    .on("mouseout", function () {
      self.resetHighlight();
    })
    .on("click", function (event, d) {
      console.log("Node clicked:", d); // Muestra en consola el nodo clicado
      self.nodeClicked(d); // Llama a una función de Vue y le pasa el nodo clicado
    });

  // Mezclar nodos nuevos y existentes
  this.nodes = nodeEnter.merge(this.nodes);

  // Reordenar los nodos para garantizar que siempre estén sobre los enlaces
  this.nodes.raise();

  // Actualizar etiquetas para los nodos
  let nodeLabel = container
    .selectAll(".node-label")
    .data(this.$store.state.red.nodes, d => d.id);

  // Eliminar etiquetas de nodos que ya no existan
  nodeLabel.exit().remove();

  // Agregar nuevas etiquetas para los nodos
  nodeLabel = nodeLabel
    .enter()
    .append("text")
    .attr("class", "node-label")
    .attr("dy", -15)
    .attr("text-anchor", "middle")
    .style("font-size", "10px")
    .style("pointer-events", "none")
    .text(d => d.name + "(" + d.cidoc + ")")
    .merge(nodeLabel);

  // Actualizar la simulación con los nodos y enlaces
  this.simulation
    .nodes(this.$store.state.red.nodes)
    .on("tick", () => {
      this.links
        .attr("x1", d => d.source.x)
        .attr("y1", d => d.source.y)
        .attr("x2", d => d.target.x)
        .attr("y2", d => d.target.y);

      this.nodes
        .attr("cx", d => d.x)
        .attr("cy", d => d.y);

      nodeLabel
        .attr("x", d => d.x)
        .attr("y", d => d.y);
    });

  this.simulation.force("link").links(this.$store.state.red.links);

  // Reiniciar la simulación con un alfa bajo para evitar movimientos grandes
  this.simulation.alpha(0.3).restart();
},


nodeClicked(nodeData) {
    this.nodeClickeData = nodeData;
  // Aquí puedes manejar lo que sucede cuando se hace clic en un nodo
 //if(this.$route.params.id === nodeData.id) return;
  axios
    .get("/eventos/info/" + nodeData.id)
    .then(res => {
      this.$store.state.red.item = nodeData;
    })
    .catch(error => {});
  console.log(nodeData.id);
  // Por ejemplo, podrías enviar la información del nodo a otro componente o actualizar algún estado en Vue
},


         // Funciones para manejar el arrastre de nodos
         dragStarted(event, d) {
            if (!event.active) this.simulation.alphaTarget(0.3).restart(); // Reiniciar la simulación
            d.fx = d.x;
            d.fy = d.y;
        },
        dragged(event, d) {
            d.fx = event.x; // Fijar la posición X mientras se arrastra
            d.fy = event.y; // Fijar la posición Y mientras se arrastra
        },
        dragEnded(event, d) {
            if (!event.active) this.simulation.alphaTarget(0); // Detener la simulación
            d.fx = null;
            d.fy = null; // Soltar el nodo cuando se suelta el mouse
        },



        ticked() {
  // Verificar si las referencias existen antes de acceder a sus atributos

  // Actualizar enlaces (lines)
  if (this.links) {
    this.links
      .attr("x1", (d) => d.source.x)
      .attr("y1", (d) => d.source.y)
      .attr("x2", (d) => d.target.x)
      .attr("y2", (d) => d.target.y);
  }

  // Actualizar etiquetas de enlaces (link labels)
  if (this.linkLabel) {
    this.linkLabel
      .attr("x", (d) => (d.source.x + d.target.x) / 2)
      .attr("y", (d) => (d.source.y + d.target.y) / 2);
  }

  // Actualizar nodos (circles)
  if (this.node) {
    this.node
      .attr("cx", (d) => d.x)
      .attr("cy", (d) => d.y);
  }

  // Actualizar etiquetas de nodos (node labels)
  if (this.nodeLabel) {
    this.nodeLabel
      .attr("x", (d) => d.x)
      .attr("y", (d) => d.y);
  }
},


        highlightPathToRoot(d) {
            const { pathNodes, pathLinks } = this.findPathToRoot(d);

            // Usar las referencias globales a `link` y `node` para actualizar el color
            this.links
                .style("stroke", l =>
                    pathLinks.includes(l) ? "orange" : "#aaa"
                )
                .style("stroke-width", l => (pathLinks.includes(l) ? 4 : 2));

            this.nodes
                .style("fill", n =>
                    pathNodes.includes(n) ? "orange" : "steelblue"
                )
                .style("stroke-width", n => (pathNodes.includes(n) ? 3 : 1.5));
        },

        resetHighlight() {
            // Restablecer los colores y anchos de las líneas y nodos
            this.links.style("stroke", "#aaa").style("stroke-width", 2);
            this.nodes.style("fill", "steelblue").style("stroke-width", 1.5);
        },

        findPathToRoot(node) {
            const pathNodes = [];
            const pathLinks = [];

            let currentNode = node;

            while (currentNode) {
                pathNodes.push(currentNode);

                // Si el nodo actual tiene el id que estamos buscando, detenemos la búsqueda
                if (currentNode.cidoc === 'E21' || currentNode.cidoc === 'E39') {
                    break;
                }

                const parentLink = this.$store.state.red.links.find(
                    l => l.target.id === currentNode.id
                );
                if (parentLink) {
                    pathLinks.push(parentLink);
                    currentNode = parentLink.source;
                } else {
                    currentNode = null;
                }
            }

            return { pathNodes, pathLinks };
        },

        // Función para expandir un nodo seleccionado y agregar sus relaciones al gráfico
        expandNode(nodeId) {
            const newLinks = this.links.filter(
                link =>
                    (link.source === nodeId || link.target === nodeId) &&
                    !this.currentLinks.includes(link)
            );

            const newNodeIds = [
                ...newLinks.map(link =>
                    link.source === nodeId ? link.target : link.source
                )
            ];

            const newNodes = this.nodess.filter(
                node =>
                    newNodeIds.includes(node.id) &&
                    !this.currentNodes.includes(node)
            );

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

        resetGrafo(nodos, enlaces, estatus) {
           // console.log('resetGrafo');
            if (estatus === false) {
                // Eliminar nodos y sus enlaces asociados
                nodos.forEach(nuevoNodo => {
                    const index = this.$store.state.red.nodes.findIndex(
                        n => n.id === nuevoNodo.id
                    );
                    if (index !== -1) {
                        // Eliminar el nodo de forma reactiva
                        this.$delete(this.$store.state.red.nodes, index);

                        // Eliminar los enlaces asociados al nodo eliminado
                        this.$store.state.red.links = this.$store.state.red.links.filter(
                            l =>
                                l.source.id !== nuevoNodo.id &&
                                l.target.id !== nuevoNodo.id
                        );
                    }
                });

                // Eliminar enlaces específicos si se proporcionan
                enlaces.forEach(nuevoEnlace => {
                    const index = this.$store.state.red.links.findIndex(
                        l =>
                            l.source.id === nuevoEnlace.source.id &&
                            l.target.id === nuevoEnlace.target.id
                    );
                    if (index !== -1) {
                        this.$delete(this.$store.state.red.links, index); // Eliminar el enlace de forma reactiva
                    }
                });
            } else {
                // Fijar las posiciones actuales de los nodos existentes para evitar que se reajusten
                this.$store.state.red.nodes.forEach(nodo => {
                    nodo.fx = nodo.x;
                    nodo.fy = nodo.y;
                });

                // Agregar nuevos nodos de forma reactiva
                nodos.forEach(nuevoNodo => {
                    const nodoExistente = this.$store.state.red.nodes.find(
                        n => n.id === nuevoNodo.id
                    );
                    if (!nodoExistente) {
                        // Asignar posición inicial cerca de algún nodo existente
                        const referenceNode =
                            this.$store.state.red.nodes.length > 0
                                ? this.$store.state.red.nodes[
                                      Math.floor(
                                          Math.random() *
                                              this.$store.state.red.nodes.length
                                      )
                                  ]
                                : { x: this.width / 2, y: this.height / 2 };

                        nuevoNodo.x = referenceNode.x + Math.random() * 30 - 15;
                        nuevoNodo.y = referenceNode.y + Math.random() * 30 - 15;
                        nuevoNodo.fx = nuevoNodo.x;
                        nuevoNodo.fy = nuevoNodo.y;

                        this.$store.state.red.nodes.push(nuevoNodo); // Agregar el nodo de forma reactiva
                    }
                });

                // Agregar nuevos enlaces de forma reactiva
                enlaces.forEach(nuevoEnlace => {
                    const enlaceExistente = this.$store.state.red.links.find(
                        l =>
                            l.source.id === nuevoEnlace.source.id &&
                            l.target.id === nuevoEnlace.target.id
                    );
                    if (!enlaceExistente) {
                        this.$store.state.red.links.push(nuevoEnlace); // Agregar el enlace de forma reactiva
                    }
                });
            }

            // Actualizar la simulación con los nuevos datos
            this.simulation.nodes(this.$store.state.red.nodes);
            this.simulation.force("link").links(this.$store.state.red.links);

            // Reiniciar la simulación para ver los cambios
            this.simulation.alpha(1).restart();

            // Actualizar el gráfico sin reiniciar completamente el SVG
            this.updateGraph(this.container);
        },

        // Función para cerrar un nodo y eliminar sus hijos
        closeNode(nodeId) {
            const linksToRemove = this.currentLinks.filter(
                link => link.source === nodeId || link.target === nodeId
            );

            const nodeIdsToRemove = linksToRemove.map(link =>
                link.source === nodeId ? link.target : link.source
            );

            // Filtrar los nodos hijos que están conectados al nodoId y eliminarlos
            this.currentNodes = this.currentNodes.filter(
                node => !nodeIdsToRemove.includes(node.id)
            );
            this.currentLinks = this.currentLinks.filter(
                link => !linksToRemove.includes(link)
            );

            console.log(this.currentLinks);

            // Reiniciar la simulación con los datos restantes
            this.simulation.nodes(this.currentNodes);
            this.simulation.force("link").links(this.currentLinks);
            this.simulation.alpha(1).restart();

            // Actualizar el gráfico
            this.renderGraph();
        },

        guardarRed(){
            console.log(this.$store.state.red);
            /*axios
                .post("/eventos/savered", { params: this.$store.state.red, id: this.$route.params.id } )
                .then(res => {
                    console.log(res.data);
                })
                .catch(error => {});*/
        },

        AddActor(){
            this.dlgAddActor = true;
            
        },

        addNodeAtClick(x, y){
            console.log(x, y);
            return;
            this.width++;
           

        this.resetGrafo(nodes, links, true);

        
        },

        traerActores(){
            axios
                .get("/eventos/actores/" + this.newActor.tipo.id)
                .then(res => {
                    console.log(res.data);
                    this.listActores = res.data;
                    
                })
                .catch(error => {});
        },

        agregarActor(){
            console.log(this.newActor);
            
            var nodes = [];
            var links = [];
            this.width++
              nodes.push({
                  id: this.newActor.tipo.id + "_" + this.newActor.select.id,
                  idItem: this.newActor.select.id,
                  cidoc: this.newActor.tipo.id,
                  type: this.newActor.tipo.name,
                  name: this.newActor.select.cNombre,
              });
              this.resetGrafo(nodes, links, true);
              this.newActor = {
                    tipo: null,
                    select: null
                },
              this.dlgAddActor = false;
        }
       
    }
};
</script>

<style scoped>
svg {
    border: 1px solid #ccc;
}
</style>

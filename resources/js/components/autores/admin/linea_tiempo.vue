<template>
  <div>
    
    <div id="timeline-embed" style="width: 100%; height: 600px;"></div>
  </div>
</template>

<script>
export default {
  name: "Timeline",
  props: {
    eventos: {
      type: Array,
      required: true
    }
  },
  computed: {
    timelineEvents() {
      return this.eventos.map((e) => {
        const fecha = new Date(e.dFecha);
        return {
          start_date: {
            year: fecha.getFullYear(),
            month: fecha.getMonth() + 1,
            day: fecha.getDate()
          },
          text: {
            headline: `${e.cRelacion || "actor desconocido"}` + " - " + e.actorRelacionado?.nombre || "",
            text: e.cDescripcion || ""
          },
          media: {
            url: "",
            caption: "",
            credit: ""
          }
        };
      });
    }
  },
  methods: {
    async loadTimelineScript() {
      if (!window.TL) {
        return new Promise((resolve, reject) => {
          const script = document.createElement("script");
          script.src = "https://cdn.knightlab.com/libs/timeline3/latest/js/timeline.js";
          script.onload = resolve;
          script.onerror = reject;
          document.head.appendChild(script);
        });
      }
    },
    renderTimeline() {
      if (window.TL) {
        new TL.Timeline("timeline-embed", {
          events: this.timelineEvents
        });
      }
    }
  },
  async mounted() {
    await this.loadTimelineScript();
    this.renderTimeline();
  },
  watch: {
    eventos: {
      handler() {
        this.renderTimeline();
      },
      deep: true
    }
  }
};
</script>

<style scoped>
/* Personaliza estilos si lo necesitas */
</style>

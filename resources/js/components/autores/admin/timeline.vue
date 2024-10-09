<template>
    <v-card>
        hola
    <div id="timeline"></div>
</v-card>
</template>

<script>
export default {
    name: "TimelineComponent",
    mounted() {
        const timelineData = {
            events: [
                {
                    start_date: { year: 1890, month: 7, day: 2 },
                    text: {
                        headline: "Evento de Nacimiento",
                        text:
                            "Miguel Othon de Mendizabal nació en Ciudad de México.<b>Este es texto</b><br><a href='/veritem/53' target='_blank'>Documento asociado</a>"
                    },
                    media: {
                        url: "URL_DE_LA_IMAGEN", // Reemplaza con la URL de la imagen
                        caption: "Foto de Miguel Othon de Mendizabal",
                        credit: "Fuente: geneanet.org"
                    }
                },
                {
                    start_date: { year: 1890, month: 7, day: 13 },
                    text: {
                        headline: "Evento Bautizo",
                        text: "Bautizo de Miguel Othon de Mendizabal."
                    },
                    media: {
                        url: "URL_DE_LA_IMAGEN", // Reemplaza con la URL de la imagen
                        caption: "Foto del evento de bautizo",
                        credit: "Fuente: geneanet.org"
                    }
                },
                {
                    start_date: { year: 1903, month: 12, day: 16 },
                    text: {
                        headline: "Certificación",
                        text:
                            "Miguel Othon de Mendizabal recibió su certificación."
                    },
                    media: {
                        url: "URL_DE_LA_IMAGEN", // Reemplaza con la URL de la imagen
                        caption: "Certificación de Miguel Othon",
                        credit: "Fuente: AHUNAM"
                    }
                },
                {
                    start_date: { year: 1910 },
                    text: {
                        headline: "Ingreso al Museo Nacional",
                        text:
                            "Miguel Othon de Mendizabal se convierte en alumno pensionado."
                    },
                    media: {
                        url: "URL_DE_LA_IMAGEN", // Reemplaza con la URL de la imagen
                        caption: "Museo Nacional",
                        credit: "Fuente: Museo Nacional"
                    }
                }
            ]
        };

        // Inicializa el timeline
        window.timeline = new TL.Timeline("timeline", timelineData);
    }
};
</script>

<style scoped>
#timeline {
    width: 100%;
    height: 600px; /* Ajusta la altura según lo necesites */
}
</style>

<template>
<div>
<div class="app-header">
    <template v-if="isLoading">
      Loading...
    </template>

    <template v-else>
      

      <span>
        <button :disabled="page <= 1" @click="page--">❮</button>

        {{ page }} / {{ pageCount }}

        <button :disabled="page >= pageCount" @click="page++">❯</button>
      </span>

        <v-text-field
            v-model="pageNumber"
            
          ></v-text-field>
    
    </template>
  </div>

  <div class="app-content">
    <vue-pdf-embed ref="pdfRef" :source="pdfSource" :page="page" @rendered="handleDocumentRender" />
  </div>
</div>
</template>
<script>
import VuePdfEmbed from 'vue-pdf-embed/dist/vue2-pdf-embed'

export default {
    name: "",
    props: ["pdf"],
    components:{
        VuePdfEmbed,
    },
    data: () => ({
        source1: "/pdf/Educación rural e indígena en Iberoamérica. Pilar Gonzalbo AizpuruOCR.pdf",

        isLoading: true,
      page: 1,
      pageNumber: 0,
      pageCount: 1,
      pdfSource: '/pdf/Educación rural e indígena en Iberoamérica. Pilar Gonzalbo AizpuruOCR.pdf',
      showAllPages: false,

    }),
    mounted(){
        this.pageNumber = this.page;
    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{
         showAllPages() {
      this.page = this.showAllPages ? null : 1
    },
    },
    computed:{

    },
    methods:{
    handleDocumentRender() {
      this.isLoading = false
      this.pageCount = this.$refs.pdfRef.pageCount
    },
    }
}
</script>
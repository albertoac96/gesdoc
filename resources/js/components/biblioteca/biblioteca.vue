<template>
<div>
     <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li
          
          v-for="(padre, index) in padres"
          :key="index"
          class="breadcrumb-item"
        >
          <a
     
      v-if="$route.params.idCarpeta != padre.idCarpeta" @click="fnCambiaCarpeta(padre)"
    >
     {{ padre.cCarpeta }}
    </a>
         
          <a  v-else>{{ padre.cCarpeta }} </a>
        </li>
      </ol>
    </nav>
   
    <resultados :id="$route.params.idCarpeta" > </resultados>
  </div>
</template>
<script>
import resultados from './resultados.vue';
export default {
    
  name: "biblioteca",
  components:{
     resultados
  },
  props: ["idCarpeta"],
  data: () => ({
    padres: [],
  }),
  watch: {
    "$route.params.idCarpeta": function (id) {
      //console.log(id);
      this.fnTraerPadres(id);
    },
  },
  created() {
    this.fnTraerPadres(this.$route.params.idCarpeta);
  },
  methods: {
    fnTraerPadres(id) {
      axios.get("/carpetaspadre/" + id).then((res) => {
        //console.log(res.data);
        this.padres = res.data;
      });
    },
    fnCambiaCarpeta(id) {
      var ruta = { name: "biblioteca", params: id };
      this.$router.push(ruta);
    },
  },
};
</script>
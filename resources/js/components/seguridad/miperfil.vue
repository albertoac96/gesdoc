<template>
<v-container>
<v-card>
    <v-card-text>
    <v-text-field
        v-model="user.cNombre"
      label="Nombre"

    ></v-text-field>

    <v-text-field
      v-model="user.cCorreo"
      label="Correo"

    ></v-text-field>

    

    <v-btn
      href="/passreset"
      target="_blank"
      color="info"
      class="mr-4"
      
    >
      Cambiar contraseña
    </v-btn>

    <v-divider></v-divider>

    <v-btn
      color="success"
      class="mr-4"
      
    >
      Guardar
    </v-btn>
    </v-card-text>
    

<v-divider></v-divider>

<v-card-text>
<h5>Sesiones iniciadas de tu usuario</h5>

<v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">
            Información
          </th>
          <th class="text-left">
            IP
          </th>
          <th class="text-left">
            Ultima conexión
          </th>
          <th class="text-left">
            Cerrar sesión
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="item in desserts"
          :key="item.name"
        >
          <td>{{ item.user_agent }}</td>
          <td><span @click="verInfoIP(item.ip_address)">{{ item.ip_address }}</span></td>
          <td>{{ getTime(item.last_activity) }}</td>
          <td>
             <v-icon
                small
                @click="cierraSesion(item)"
              >
                mdi-close
              </v-icon>

          </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</v-card-text>
  </v-card>
</v-container>

</template>
<script>
export default {
    name: "miPerfil",
    props: [],
    components:{

    },
    data: () => ({
        user:{
            cNombre: "",
            cCorreo: "",
            id: ""
        },
        desserts:[]
    }),
    mounted(){
       
    },
    created(){
      if(!this.$store.state.dataUser.info.user){
         axios
              .get("/authinfo")
              .then((res) => {
                 this.user.cNombre = res.data.user;
                 this.user.cCorreo = res.data.email;
                 this.user.id = res.data.Cve;
              })
              .catch((error) => {
         });
      } else {
        this.user.cNombre = this.$store.state.dataUser.info.user;
       this.user.cCorreo = this.$store.state.dataUser.info.email;
      }

       axios
            .get("/listsessions")
            .then((res) => {
               this.desserts = res.data;
            })
            .catch((error) => {
       });
        
    },
    beforeMount(){

    },
    watch:{
        
    },
    computed:{
   
    },
    methods:{
      cierraSesion(item){
       
         axios
              .post("/delsesion", item)
              .then((res) => {
                 console.log(res.data);
                 var pos = this.desserts.map(function(e) { return e.id; }).indexOf(item.id);
                this.desserts.splice(pos, 1); 
              })
              .catch((error) => {
         });
      },
      getTime(date){
        var date = new Date(date * 1000);
        return (date.toUTCString());
      },
      verInfoIP(ip){
        var url = "https://es.infobyip.com/ip-" + ip +".html";
        var win = window.open(url, '_blank');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
      }
    }
}
</script>
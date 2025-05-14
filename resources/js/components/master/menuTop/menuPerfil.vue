<template>
      <div class="text-center">
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      :nudge-width="200"
      offset-x
    >
      <template v-slot:activator="{ on }">
       <v-btn icon x-large v-on="on">
          <v-avatar color="indigo" size="48">
           <img
                v-if="user.info.avatar == ''"
                src="/images/perfiles/user.png"
                :alt="user.info.user"
              >
              <img
                v-else
                :src="traerAvatar(user.info.Cve)"
                :alt="user.info.user"
              >
           
          </v-avatar>
        </v-btn>
      </template>

      <v-card>
        <v-list>
          <v-list-item>
            <v-list-item-avatar>
             <img
                v-if="user.info.avatar == ''"
                src="/images/perfiles/user.png"
                :alt="user.info.user"
              >
              <img
                v-else
                :src="traerAvatar(user.info.Cve)"
                :alt="user.info.user"
              >
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>{{user.info.user}}</v-list-item-title>
              <v-list-item-subtitle>{{user.info.email}}</v-list-item-subtitle>
              <v-list-item-subtitle>{{user.rol}}</v-list-item-subtitle>
            </v-list-item-content>


           

            
          </v-list-item>
          <v-list-item to="/miperfil" @click="menu = false">
             <v-list-item-icon>
            <v-icon>mdi-account-circle</v-icon>
          </v-list-item-icon>           
            <v-list-item-content>
              <v-list-item-title>Editar perfil</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>

        <!--<v-list>
          <v-list-item>
            <v-list-item-action>
              <v-switch
                v-model="message"
                color="purple"
              ></v-switch>
            </v-list-item-action>
            <v-list-item-title>Enable messages</v-list-item-title>
          </v-list-item>

          <v-list-item>
            <v-list-item-action>
              <v-switch
                v-model="hints"
                color="purple"
              ></v-switch>
            </v-list-item-action>
            <v-list-item-title>Enable hints</v-list-item-title>
          </v-list-item>
        </v-list>-->

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            text
            @click="logout()"
          >
            Cerrar sesión
          </v-btn>
          
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>
<script>
export default {
    name:"menuPerfil",
     data: () => ({
      fav: true,
      menu: false,
      message: false,
      hints: true,
      user: {
        info:{},
        rol: {}
      },
    }),
    mounted(){
 
      this.$infoUser();
    },
    methods:{
      logout(){
        axios
        .post("/logout")
        .then(function (response){
          window.location.href = "/";
        })
        .catch( function (error){
          var e = error.response;
          if(e.statusText === 'Unprocessable Entity'){

          }
        })
      },
      traerAvatar(user){
        return ("/images/perfiles/"+user+".jpg");
      }
    }
    
}
</script>
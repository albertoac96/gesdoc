
  export default {
    created(){
      
    },
    methods: {
      $can(permissionName, texto) {
        //console.log(permissionName);
        let InstFormData = new FormData();
        InstFormData.append('permiso' , permissionName);
         axios
              .post("/tp", InstFormData)
              .then((res) => {
                 if(res.data == true){
                    return true;
                 } else {
                    this.$store.state.snack.model = true;
                    this.$store.state.snack.color = "error";
                    this.$store.state.snack.text = texto;
                    this.$store.state.snack.centered = true;
                    this.$router.push('/');
                    return false;
                 }
              })
              .catch((error) => {
         });
        
        /*var permisos = this.$store.state.dataUser.permisos;
        var existe = 0;
        for(var i = 0; i < permisos.length; i++){
          if(permisos[i] == permissionName){
            existe = 1;
            break;
          } 
        }
        if(existe == 1){
          return true;
        } else {
          
        }*/
       
        
      },
      $canSnack(permissionName, texto) {
        var permisos = this.$store.state.dataUser.permisos;
        var existe = 0;
        for(var i = 0; i < permisos.length; i++){
          if(permisos[i] == permissionName){
            existe = 1;
            break;
          } 
        }
        if(existe == 1){
          return true;
        } else {
          this.$store.state.snack.model = true;
          this.$store.state.snack.color = "error";
          this.$store.state.snack.text = texto;
          this.$store.state.snack.centered = true;
          return false;
        }
        
       
        
      },
      $canBtn(permissionName) {
        
        /*let InstFormData = new FormData();
        InstFormData.append('permiso' , permissionName);
         axios
              .post("/tp", InstFormData)
              .then((res) => {
                 if(res.data == true){
                    return true;
                 } else {
                   
                    return false;
                 }
              })
              .catch((error) => {
         });*/

        var permisos = this.$store.state.dataUser.permisos;
        var existe = 0;
        for(var i = 0; i < permisos.length; i++){
          if(permisos[i] == permissionName){
            existe = 1;
            break;
          } 
        }
        if(existe == 1){
          return true;
        } else {
          
          return false;
        }
        
        
      },
      $infoUser() {
        axios
        .post("/iu")
        .then((res) => {
          //console.log(res.data);
          this.user = res.data;
          this.$store.state.dataUser.info = res.data.info;
          this.$store.state.dataUser.permisos = Object.values(res.data.permisos);
          
          this.$store.state.dataUser.rol = res.data.rol;
          
      })
      .catch((error) => {
      });
      }
      
    },
  };


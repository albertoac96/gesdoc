<template>
     <v-dialog
      v-model="dlg"
      width="500"
      persistent
    >
     

      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Editar fechas
        </v-card-title>

        <v-card-text class="mt-3">
            <span>Fecha de inicio</span>
            <v-row>
            <v-col cols="3">
                <v-text-field
                v-model="fecha.dInicio.dia"
                label="Día"
                ></v-text-field>
            </v-col>
            <v-col cols="3">
                <v-text-field
                v-model="fecha.dInicio.mes"
                label="Mes"
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                v-model="fecha.dInicio.anyo"
                label="Año"
                ></v-text-field>
            </v-col>
            </v-row>

            <span>Fecha de fin</span>
            <v-row>
            <v-col cols="3">
                <v-text-field
                v-model="fecha.dFin.dia"
                label="Día"
                ></v-text-field>
            </v-col>
            <v-col cols="3">
                <v-text-field
                v-model="fecha.dFin.mes"
                label="Mes"
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                v-model="fecha.dFin.anyo"
                label="Año"
                ></v-text-field>
            </v-col>
            </v-row>

        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="error"
            text
            @click="$emit('close')"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="fnGuardar()"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
export default {
    name: "dlgEditFecha",
    props: ["item", "idDoc"], 
    components:{

    },
    data: () => ({
        dlg: true,
        fecha:{
            dInicio:{
                dia: "",
                mes: "",
                anyo: ""
            },
            dFin:{
                dia: "",
                mes: "",
                anyo: ""
            }
        },
        fechaOK:{
            dInicio: "",
            dFin: ""
        }
    }),
    mounted(){
        this.wFecha(this.item);
    },
    created(){
        
    },
    beforeMount(){

    },
    watch:{

    },
    computed:{

    },
    methods:{
        wFecha(fechas){
          
            if(fechas.dInicio != null){
                this.fecha.dInicio.anyo = fechas.dInicio.substring(0, 4);
            this.fecha.dInicio.mes = fechas.dInicio.substring(5, 7);
            this.fecha.dInicio.dia = fechas.dInicio.substring(8, 10);

            if(fechas.dFin != null){
                this.fecha.dFin.anyo = fechas.dFin.substring(0, 4);
            this.fecha.dFin.mes = fechas.dFin.substring(5, 7);
            this.fecha.dFin.dia = fechas.dFin.substring(8, 10);
            }
                if(this.fecha.dFin.dia+this.fecha.dFin.mes == '0101' && this.fecha.dInicio.dia+this.fecha.dInicio.mes == '0101'){
                this.fecha.dFin.mes = "";
                this.fecha.dFin.dia = "";
                this.fecha.dInicio.mes = "";
                this.fecha.dInicio.dia = "";
            }

            }

            
            

           

            

            

        },
        fnGuardar(){

            if(this.fecha.dInicio.anyo == ""){
                
                this.$store.state.snack.text = "Debe proporcionar al menos el año de inicio";
              this.$store.state.snack.color = "error";
              this.$store.state.snack.centered = true;
              this.$store.state.snack.model = "true";
                return;
            } else {
                if(this.fecha.dInicio.anyo.length != 4){
                    
                    this.$store.state.snack.text = "Los años deben tener 4 digitos";
              this.$store.state.snack.color = "error";
              this.$store.state.snack.centered = true;
              this.$store.state.snack.model = "true";
                    return;
                }
            }

            if(this.fecha.dFin.anyo == ""){
                if(this.fecha.dInicio.anyo == ""){
                   
                     this.$store.state.snack.text = "Debe proporcionar al menos el año de inicio";
              this.$store.state.snack.color = "error";
              this.$store.state.snack.centered = true;
              this.$store.state.snack.model = "true";
                    return;
                } 
                
            } else {
                if(this.fecha.dFin.anyo.length != 4){
                     this.$store.state.snack.text = "Los años deben tener 4 digitos";
              this.$store.state.snack.color = "error";
              this.$store.state.snack.centered = true;
              this.$store.state.snack.model = "true";
               
                    return;
                }
            }
         
            
            

            

           

                if(this.fecha.dInicio.mes != ""){
                    if(this.fecha.dInicio.mes.length != 2){
                        ////console.log(this.fecha.dInicio.mes.length);
                        ////console.log("dInicioMes");
                        return;
                    }
                    
                }
                if(this.fecha.dInicio.dia != ""){
                    if(this.fecha.dInicio.dia.length != 2){
                   // //console.log("dInicioDiua");
                    return;
                    }
                }
                if(this.fecha.dFin.mes != ""){
                    if(this.fecha.dFin.mes.length != 2){
                    ////console.log("Fin Mes");
                    return;
                    }
                }
                if(this.fecha.dFin.dia != ""){
                    if(this.fecha.dFin.dia.length != 2){
                    ////console.log("Fin Dia");
                    return;
                    }
                }
                
                

            

            
            if(this.fecha.dInicio.mes == "" || this.fecha.dInicio.mes == "00") this.fecha.dInicio.mes = '01';
            if(this.fecha.dInicio.dia == "" || this.fecha.dInicio.dia == "00") this.fecha.dInicio.dia = '01';
            if(this.fecha.dFin.mes == "" || this.fecha.dFin.mes == "00") this.fecha.dFin.mes = '01';
            if(this.fecha.dFin.dia == "" || this.fecha.dFin.dia == "00") this.fecha.dFin.dia = '01';
            if(this.fecha.dFin.anyo == "" || this.fecha.dFin.anyo == "00") this.fecha.dFin.anyo = this.fecha.dInicio.anyo;

            if(this.fecha.dFin.dia + this.fecha.dFin.mes + this.fecha.dFin.anyo < this.fecha.dInicio.dia + this.fecha.dInicio.mes + this.fecha.dInicio.anyo){
             
                this.$store.state.snack.text = "La fecha de fin debe ser posterior a la de inicio";
              this.$store.state.snack.color = "error";
              this.$store.state.snack.centered = true;
              this.$store.state.snack.model = "true";
                return;
            }

            this.fechaOK.dInicio = this.fecha.dInicio.anyo+"-"+this.fecha.dInicio.mes+"-"+this.fecha.dInicio.dia;
            this.fechaOK.dFin = this.fecha.dFin.anyo+"-"+this.fecha.dFin.mes+"-"+this.fecha.dFin.dia;



            if(this.VerificaPermiso('editar') == true){

          let InstFormData = new FormData();
          InstFormData.append('idElemento' , this.idDoc);
          InstFormData.append('cDesc' , "De " + this.item.dInicio + " / " + this.item.dFin + " a " + this.fechaOK.dInicio + " / " + this.fechaOK.dFin);
          InstFormData.append('idDocumento' , this.idDoc);
          InstFormData.append('tipo' , 'fechas');
          InstFormData.append('item' , JSON.stringify(this.fechaOK));
            axios
            .post("/edit", InstFormData)
            .then((res) => {
              this.$store.state.snack.text = "La fecha fue actualizada";
              this.$store.state.snack.color = "success";
              this.$store.state.snack.centered = false;
              this.$store.state.snack.model = "true";
              this.$emit('itemEdit', this.fechaOK);
            })
            .catch((error) => {});
           
        } else {
          this.$store.state.snack.text = "No tienes permisos para editar este elemento";
          this.$store.state.snack.color = "error";
          this.$store.state.snack.model = "true";
        }



            
        }
    }
}
</script>
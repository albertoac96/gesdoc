<template>
    <v-row>
        <v-chip
            small
            close
            color="indigo darken-4"
            label
            outlined
            @click:close="fnChangeTipoDoc()"
            close-icon="mdi-pencil"
            close-color="red"
            @dblclick.stop="irA('1')"
        >
            <v-icon>
                {{info.Documento.cIcono}}
            </v-icon>
            {{info.Documento.cTipoDoc}}
        </v-chip>

        <dlgEditTipoDoc v-if="dlgEditTipoDoc" :item="tipodoc" :idDoc="info.idDoc" @close="dlgEditTipoDoc = false" @itemEdit="EditTipoDocOK($event)"></dlgEditTipoDoc>

         <v-chip
            v-if="info.Documento.idTipoDoc == 1 || info.Documento.idTipoDoc == 15 || info.Documento.idTipoDoc == 17 ||
                info.Documento.idTipoDoc == 14 || info.Documento.idTipoDoc == 16 || info.Documento.idTipoDoc == 4"
            small
            close
            color="indigo darken-4"
            label
            outlined
            @click:close="fnChangePublicacion()"
            close-icon="mdi-pencil"
            @dblclick.stop="irA('2')"
        >
           
            <span v-if="info.Documento.idPublicacion == null">No hay publicación</span>
            <span v-else>{{info.Documento.cPublicacion}}</span>
        </v-chip>

        <dlgEditPub v-if="dlgEditPub" :item="info.Documento" :idDoc="info.idDoc" @close="dlgEditPub = false" @itemEdit="EditPubOK($event)"></dlgEditPub>

         <v-chip
         
            small
            close
            color="teal darken-4"
            label
            outlined
            @click:close="fnChangeFecha()"
            close-icon="mdi-pencil"
        >
           
            {{info.fechas}}
            
        </v-chip>
        <dlgEditFechas v-if="dlgEditFecha" :item="info.dates" :idDoc="info.idDoc" @close="dlgEditFecha = false" @itemEdit="EditFechaOK($event)"></dlgEditFechas>


        </v-row>
</template>
<script>
import dlgEditTipoDoc from "../../dialogos/edit/dlgEditTipoDoc.vue";
import dlgEditFechas from "../../dialogos/edit/dlgEditFechas.vue";
import dlgEditPub from "../../dialogos/edit/dlgEditPub.vue";
export default {
    name: "verNombre",
    props: ["info"],
    components:{
        dlgEditTipoDoc,
        dlgEditFechas,
        dlgEditPub
    },
    data: () => ({
        tipodoc:{
            text: "",
            value: "",
            cIcono: ""
        },
        dlgEditTipoDoc: false,
        dlgEditFecha: false,
        dlgEditPub: false,

    }),
    mounted(){
      //console.log(this.info)
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
        fnChangeTipoDoc(){
            if(this.$canSnack('editar', 'No tienes permisos para editar')){
            this.tipodoc.text = this.info.Documento.cTipoDoc;
        this.tipodoc.value = this.info.Documento.idTipoDoc;
        this.tipodoc.cIcono = this.info.Documento.cIcono;
        
        this.dlgEditTipoDoc = true;
            }
            
        },
        EditTipoDocOK(newTipoDoc){
            this.info.Documento.cIcono = newTipoDoc.cIcono;
            this.info.Documento.cTipoDoc = newTipoDoc.text;
            this.info.Documento.idTipoDoc = newTipoDoc.value;
            if(newTipoDoc.value != 1 || newTipoDoc.value != 4 || newTipoDoc.value != 14 || newTipoDoc.value != 15 || newTipoDoc.value != 16 || newTipoDoc.value != 17){
                this.info.Documento.cPublicacion = "";
                this.info.Documento.idPublicacion = null;
            }
            this.dlgEditTipoDoc = false;
        },
        fnChangePublicacion(){
            if(this.$canSnack('editar', 'No tienes permisos para editar')){
            this.dlgEditPub = true;
            }
        },
        EditPubOK(newItem){
            this.info.Documento.cPublicacion = newItem.text;
            this.info.Documento.idPublicacion = newItem.value;
            this.dlgEditPub = false;
        },
        fnChangeFecha(){
            if(this.$canSnack('editar', 'No tienes permisos para editar')){
            this.dlgEditFecha = true;
            }
        },
        EditFechaOK(newFecha){
            //console.log(newFecha);
            

            this.info.dates = newFecha;
            this.info.fechas = this.cFechaCompare(newFecha.dInicio, newFecha.dFin);
            this.dlgEditFecha = false;
        },
        irA(tipo){
            if(tipo == 1){ //TipoDOc
                //console.log(this.info.Documento.idTipoDoc);
            }
            if(tipo == 2){ //Publicacion
                if(this.info.Documento.idPublicacion == null){
                    //console.log("No puede ir");
                    return;
                }
                //console.log(this.info.Documento.idPublicacion);

            }
        }
       
    }
}
</script>
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        dataUser:{
            info:{},
            permisos: {},
            rol: ""
        },
        UploadProgress:{
            open: false,
            nArchivos: 0,
            nBytes: 0,
            infoArchivos: new Array()
        },
        carpetas: [],
        snack:{
            model: false,
            color: "",
            text: "",
            centered: true,
        },
        overlay: false,
        iNewCarpeta: 0,
        red: {
            nodes: [],
            links:[],
            item: null
        },
        evento: {
            id: "",
            cve: ""
        }
    },
    getters: {
        UploadProgressArchivos: state => state.UploadProgress.infoArchivos
    },
    mutations: {

    }
})
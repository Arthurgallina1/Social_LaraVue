import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: sessionStorage.getItem('user') ? JSON.parse(sessionStorage.getItem('user')) : null,
    conteudoLinhaTempo: [],
  },
  getters:{ 
    getUser: (state) => {
      return state.user;
    },
    getToken: (state) => {
      return state.user.token;
    },
    getConteudoLinhaTempo: (state) => state.conteudoLinhaTempo,

  },
  mutations: {
    setUsuario(state, n){
      state.user = n;
    },
    setConteudoLT(state, conteudo){
      state.conteudoLinhaTempo = conteudo;
    },
    setPaginationConteudosLT(state, lista){
      for(let item of lista){
        state.conteudoLinhaTempo.push(item);
      }
    }

  },
  actions: {

  }
})

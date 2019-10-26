import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

Vue.config.productionTip = false
Vue.prototype.$http = axios //Definindo uma instancia no objeto Vue -> this.$http nos componentes
Vue.prototype.$urlAPI = 'http://127.0.0.1:8000/api/' 

Vue.directive('scroll', {
  inserted: function (el, binding) {
    let f = function (evt) {
      if (binding.value(evt, el)) {
        window.removeEventListener('scroll', f)
      }
    }
    window.addEventListener('scroll', f)
  }
})


new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

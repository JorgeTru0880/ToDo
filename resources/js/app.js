import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(VueRouter);
Vue.use(Vuex);

// Importar componentes
import LoginComponent from './components/LoginComponent.vue';
import RegisterComponent from './components/RegisterComponent.vue';
import NotesComponent from './components/NotesComponent.vue';
import NoteFormComponent from './components/NoteFormComponent.vue';

// Obtener el token CSRF del meta tag generado por Laravel
const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    // Configurar Axios para que use el token CSRF en todas las peticiones
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Si usas autenticación con tokens (por ejemplo, JWT o Laravel Passport)
const userToken = localStorage.getItem('authToken');  // Obtén el token de autenticación guardado

if (userToken) {
    // Configura Axios para que incluya el token de autenticación en todas las peticiones
    axios.defaults.headers.common['Authorization'] = `Bearer ${userToken}`;
} else {
    console.warn('No auth token found. Ensure that users are logged in properly.');
}


// Configurar rutas
const routes = [
  { path: '/login', component: LoginComponent },
  { path: '/register', component: RegisterComponent },
  { path: '/notes', component: NotesComponent },
  { path: '/notes/create', component: NoteFormComponent },
  { path: '/notes/:id/edit', component: NoteFormComponent },
];

const router = new VueRouter({
  mode: 'history',
  routes,
});

// Configurar Vuex Store
const store = new Vuex.Store({
  state: {
    user: null,
    notes: [],
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    setNotes(state, notes) {
      state.notes = notes;
    },
  },
  actions: {
    fetchNotes({ commit }) {
      axios.get('/api/notes').then(response => {
        commit('setNotes', response.data);
      });
    },
  },
});

// Instanciar Vue
new Vue({
  router,
  store,
  el: '#app',
});

<template>
    <div>
      <h2>Iniciar Sesión</h2>
      <form @submit.prevent="login">
        <input type="email" v-model="email" placeholder="Email" required />
        <input type="password" v-model="password" placeholder="Contraseña" required />
        <button type="submit">Ingresar</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
      login() {
        axios.post('/login', {
          email: this.email,
          password: this.password,
        })
        .then(response => {
          this.$store.commit('setUser', response.data.user);
          this.$router.push('/notes');
        })
        .catch(error => {
          console.error(error);
        });
      },
    },
  };
  </script>
  
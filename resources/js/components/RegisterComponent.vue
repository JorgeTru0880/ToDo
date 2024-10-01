<template>
    <div class="register-container">
      <h2>Registro</h2>
      <form @submit.prevent="register">
        <div class="form-group">
          <label for="name">Nombre</label>
          <input type="text" v-model="form.name" id="name" required />
        </div>
  
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" v-model="form.email" id="email" required />
        </div>
  
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" v-model="form.password" id="password" required />
        </div>
  
        <div class="form-group">
          <label for="password_confirmation">Confirmar Contraseña</label>
          <input type="password" v-model="form.password_confirmation" id="password_confirmation" required />
        </div>
  
        <div v-if="errors.length" class="error-messages">
          <ul>
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          </ul>
        </div>
  
        <button type="submit">Registrar</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: '',
          password_confirmation: ''
        },
        errors: []
      };
    },
    methods: {
      async register() {
        this.errors = [];
        try {
          const response = await axios.post('/register', this.form);
          console.log('Registro exitoso:', response.data);
          // Redirige a la página de login u otra acción después del registro
          this.$router.push('/login');
        } catch (error) {
          if (error.response && error.response.data.errors) {
            this.errors = Object.values(error.response.data.errors).flat();
          } else {
            this.errors.push('Error al registrar, intenta nuevamente.');
          }
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .register-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  .form-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }
  
  .error-messages {
    color: red;
    margin-bottom: 15px;
  }
  
  button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  </style>
  
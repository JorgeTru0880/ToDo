<template>
    <div class="note-form-container">
      <h2>{{ isEditMode ? 'Editar Nota' : 'Crear Nota' }}</h2>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="title">Título</label>
          <input
            type="text"
            v-model="noteData.title"
            id="title"
            placeholder="Ingresa el título"
            required
          />
        </div>
  
        <div class="form-group">
          <label for="description">Descripción</label>
          <textarea
            v-model="noteData.description"
            id="description"
            placeholder="Ingresa la descripción"
            required
          ></textarea>
        </div>
  
        <div class="form-group">
          <label for="tags">Etiquetas</label>
          <input
            type="text"
            v-model="noteData.tags"
            id="tags"
            placeholder="Ingresa etiquetas separadas por comas"
          />
        </div>
  
        <div class="form-group">
          <label for="due_date">Fecha de Vencimiento (Opcional)</label>
          <input
            type="date"
            v-model="noteData.due_date"
            id="due_date"
          />
        </div>
  
        <div class="form-group">
          <label for="image">Imagen (Opcional)</label>
          <input type="file" @change="handleFileUpload" id="image" />
        </div>
  
        <div v-if="errors.length" class="error-messages">
          <ul>
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          </ul>
        </div>
  
        <button type="submit">{{ isEditMode ? 'Actualizar Nota' : 'Crear Nota' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: {
      note: {
        type: Object,
        default: () => ({
          title: '',
          description: '',
          tags: '',
          due_date: null,
          image: null,
        }),
      },
    },
    data() {
      return {
        noteData: {
          title: this.note.title || '',
          description: this.note.description || '',
          tags: this.note.tags || '',
          due_date: this.note.due_date || '',
          image: null,
        },
        errors: [],
      };
    },
    computed: {
      isEditMode() {
        return !!this.note.id; // Determina si es modo de edición basado en si la nota tiene un ID
      },
    },
    methods: {
      async submitForm() {
        this.errors = [];
  
        const formData = new FormData();
        formData.append('title', this.noteData.title);
        formData.append('description', this.noteData.description);
        formData.append('tags', this.noteData.tags);
        formData.append('due_date', this.noteData.due_date);
        if (this.noteData.image) {
          formData.append('image', this.noteData.image);
        }
  
        try {
          if (this.isEditMode) {
            // Si es edición, haz una solicitud PUT
            await axios.post(`/api/notes/${this.note.id}`, formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            });
          } else {
            // Si es creación, haz una solicitud POST
            await axios.post('/api/notes', formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            });
          }
          this.$emit('noteSaved');
          this.resetForm();
        } catch (error) {
          if (error.response && error.response.data.errors) {
            this.errors = Object.values(error.response.data.errors).flat();
          } else {
            this.errors.push('Error al guardar la nota.');
          }
        }
      },
      handleFileUpload(event) {
        this.noteData.image = event.target.files[0];
      },
      resetForm() {
        this.noteData = {
          title: '',
          description: '',
          tags: '',
          due_date: null,
          image: null,
        };
      },
    },
  };
  </script>
  
  <style scoped>
  .note-form-container {
    max-width: 600px;
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
  
  .form-group input,
  .form-group textarea {
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
  
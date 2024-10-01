<template>
    <div>
      <h2>Mis Notas</h2>
      <button @click="$router.push('/notes/create')">Nueva Nota</button>
      <ul>
        <li v-for="note in notes" :key="note.id">
          <h3>{{ note.title }}</h3>
          <p>{{ note.description }}</p>
          <button @click="editNote(note.id)">Editar</button>
          <button @click="deleteNote(note.id)">Eliminar</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    computed: {
      notes() {
        return this.$store.state.notes;
      },
    },
    created() {
      this.$store.dispatch('fetchNotes');
    },
    methods: {
      editNote(id) {
        this.$router.push(`/notes/${id}/edit`);
      },
      deleteNote(id) {
        axios.delete(`/api/notes/${id}`).then(() => {
          this.$store.dispatch('fetchNotes');
        });
      },
    },
  };
  </script>
  
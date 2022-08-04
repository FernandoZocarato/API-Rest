Vue.use(VueAxios, axios)
const vm = new Vue({
  el: "#app",
  data: {
    tarefas: [],
    titulo: '',
    dever: '',
    tituloModal: '',
    deverModal: '',
    id: '',
    valor: {}
  },
  methods: {
    fetchTarefas() {
      fetch("http://localhost:8009/tarefas/")
        .then(r => r.json())
        .then(r => {
          this.tarefas = r;
        });
    },
    valueInput() {
      this.axios.post("http://localhost:8009/tarefas/", {
        titulo: this.titulo,
        dever: this.dever
      })
      .then(() => {
        this.fetchTarefas();
        this.titulo = '';
        this.dever = '';
      });

    },
    deleteTable(id) {
      if (confirm("confirmar a exclusão?")) {
        this.axios.delete(`http://localhost:8009/tarefas/${id}`)
          .then(() => {
            this.fetchTarefas();

          });
      }
    },
    getValue(id, titulo, dever) {
      this.id = id;
      this.tituloModal = titulo;
      this.deverModal = dever;
    },
    editTable() {
      id = this.id;
      if (this.tituloModal === "" & !(this.deverModal === "")) {
        this.axios.put(`http://localhost:8009/tarefas/${id}`, {
        dever: this.deverModal
      })
      .then(() => {
        this.fetchTarefas();
      });

      } else if (this.deverModal === "" & !(this.tituloModal === "")) {
        this.axios.put(`http://localhost:8009/tarefas/${id}`, {
          titulo: this.tituloModal
      })
      .then(() => {
        this.fetchTarefas();
      });

      } else if (this.tituloModal === "" & (this.deverModal === "")) {
        alert("É necessário preencher um campo");

      }else {
        this.axios.put(`http://localhost:8009/tarefas/${id}`, {
        titulo: this.tituloModal,
        dever: this.deverModal
      })
      .then(() => {
        this.fetchTarefas();
      });
      }

      this.tituloModal = '';
      this.deverModal = '';
    }
  },
  created() {
    this.fetchTarefas();
  }
})
<template>
  <div class="max-w-xl mx-auto mt-10 p-4">
    <h2 class="text-2xl font-bold mb-4">Todo List</h2>

    <form @submit.prevent="createTodo" class="mb-4 flex gap-2">
      <input v-model="title" placeholder="Title" class="input flex-1" />
      <input v-model="due_date" type="date" class="input" />
      <button class="btn">Add</button>
    </form>

    <ul class="space-y-2">
      <li v-for="todo in todos" :key="todo.id" class="flex justify-between items-center p-2 border rounded">
        <div>
          <input type="checkbox" v-model="todo.is_complete" @change="toggleComplete(todo)" />
          <span :class="{ 'line-through text-gray-500': todo.is_complete }">{{ todo.title }}</span>
        </div>
        <button @click="deleteTodo(todo.id)" class="text-red-500">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';

const toast = useToast();
const router = useRouter();

const todos = ref([]);
const title = ref('');
const due_date = ref('');

const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const fetchTodos = async () => {
  const res = await axios.get('/todos');
  todos.value = res.data;
};

const createTodo = async () => {
  const res = await axios.post('/todos', {
    title: title.value,
    due_date: due_date.value,
  });
  todos.value.push(res.data);
  title.value = '';
  due_date.value = '';
};

const deleteTodo = async (id) => {
  await axios.delete(`/todos/${id}`);
  todos.value = todos.value.filter(t => t.id !== id);
};

const toggleComplete = async (todo) => {
  await axios.put(`/todos/${todo.id}`, {
    is_complete: todo.is_complete,
  });
};

onMounted(fetchTodos);
</script>

<style scoped>
/* .input {
  @apply p-2 border border-gray-300 rounded;
}
.btn {
  @apply bg-blue-600 text-white px-4 py-2 rounded;
} */
</style>

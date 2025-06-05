<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-2xl mx-auto bg-white shadow-xl rounded-2xl p-8">
      <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">📋 My Todo List</h2>
        <button
          @click="logout"
          class="text-sm text-red-500 font-medium hover:underline"
        >
          Logout
        </button>
      </div>

      <form @submit.prevent="isEditing ? updateTodo() : createTodo()" class="flex flex-col sm:flex-row gap-4 mb-6">
        <input
          v-model="title"
          type="text"
          placeholder="What do you need to do?"
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          required
        />
        <input
          v-model="due_date"
          type="date"
          class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          required
        />
        <button
          type="submit"
          :class="isEditing ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-indigo-600 hover:bg-indigo-700'"
          class="px-6 py-2 text-white font-semibold rounded-lg transition"
        >
          {{ isEditing ? 'Update' : 'Add' }}
        </button>
        <button
          v-if="isEditing"
          @click.prevent="cancelEdit"
          class="px-6 py-2 bg-gray-300 text-black font-semibold rounded-lg hover:bg-gray-400 transition"
        >
          Cancel
        </button>
      </form>

      <ul class="space-y-4">
        <li
          v-for="todo in todos"
          :key="todo.id"
          class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg border"
        >
          <div class="flex items-center gap-3">
            <input
              type="checkbox"
              v-model="todo.is_complete"
              @change="toggleComplete(todo)"
              class="form-checkbox h-5 w-5 text-indigo-600"
            />
            <span :class="{ 'line-through text-gray-500': todo.is_complete }" class="text-gray-800 font-medium">
              {{ todo.title }}
            </span>
          </div>
          <div class="flex gap-2">
            <button @click="editTodo(todo)" class="text-sm text-blue-500 hover:underline">Edit</button>
            <button @click="deleteTodo(todo.id)" class="text-sm text-red-500 hover:underline">Delete</button>
          </div>
        </li>
      </ul>

      <div v-if="todos.length === 0" class="text-center text-gray-500 mt-10">
        No tasks yet. Add your first todo!
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';
import axios from 'axios';

const toast = useToast();
const router = useRouter();

const todos = ref([]);
const title = ref('');
const due_date = ref('');
const isEditing = ref(false);
const editTodoId = ref(null);

const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const fetchTodos = async () => {
  try {
    const res = await axios.get('/todos');
    todos.value = res.data;
  } catch (err) {
    toast.error('Failed to load todos');
  }
};

const createTodo = async () => {
  try {
    const res = await axios.post('/todos', {
      title: title.value,
      due_date: due_date.value,
    });
    todos.value.push(res.data);
    title.value = '';
    due_date.value = '';
    toast.success('Todo added!');
  } catch (err) {
    toast.error('Failed to create todo');
  }
};

const editTodo = (todo) => {
  title.value = todo.title;
  due_date.value = todo.due_date;
  editTodoId.value = todo.id;
  isEditing.value = true;
};

const cancelEdit = () => {
  title.value = '';
  due_date.value = '';
  editTodoId.value = null;
  isEditing.value = false;
};

const updateTodo = async () => {
  try {
    const res = await axios.put(`/todos/${editTodoId.value}`, {
      title: title.value,
      due_date: due_date.value,
    });
    const index = todos.value.findIndex((t) => t.id === editTodoId.value);
    if (index !== -1) {
      todos.value[index] = res.data;
    }
    toast.success('Todo updated!');
    cancelEdit();
  } catch (err) {
    toast.error('Failed to update todo');
  }
};

const deleteTodo = async (id) => {
  try {
    await axios.delete(`/todos/${id}`);
    todos.value = todos.value.filter((t) => t.id !== id);
    toast.success('Todo deleted!');
  } catch (err) {
    toast.error('Failed to delete todo');
  }
};

const toggleComplete = async (todo) => {
  try {
    await axios.put(`/todos/${todo.id}`, {
      is_complete: todo.is_complete,
    });
    toast.success('Status updated');
  } catch (err) {
    toast.error('Failed to update status');
  }
};

const logout = () => {
  localStorage.removeItem('token');
  delete axios.defaults.headers.common['Authorization'];
  router.push('/login');
};

onMounted(fetchTodos);
</script>

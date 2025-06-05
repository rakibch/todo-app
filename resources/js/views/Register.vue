<template>
  <div class="max-w-md mx-auto mt-20 p-4 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Register</h2>
    <form @submit.prevent="register">
      <input v-model="name" type="text" placeholder="Name" class="input" />
      <input v-model="email" type="email" placeholder="Email" class="input" />
      <input v-model="password" type="password" placeholder="Password" class="input" />
      <input v-model="password_confirmation" type="password" placeholder="Confirm Password" class="input" />
      <button class="btn">Register</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const router = useRouter();
const toast = useToast();

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');

const register = async () => {
  try {
    const res = await axios.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });
    localStorage.setItem('token', res.data.token);
    toast.success('Registered successfully!');
    router.push('/dashboard');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Registration failed');
  }
};
</script>

<style scoped>
/* .input {
  @apply w-full p-2 border border-gray-300 rounded mb-3;
}
.btn {
  @apply w-full bg-blue-600 text-white p-2 rounded;
} */
</style>

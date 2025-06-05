<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
      <div class="mb-6 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
        <p class="text-sm text-gray-500 mt-2">
          Don't have an account?
          <router-link to="/register" class="text-indigo-600 hover:underline">Register</router-link>
        </p>
      </div>
      <form @submit.prevent="login" class="space-y-5">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="you@example.com"
          />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="••••••••"
          />
        </div>
        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center">
            <input type="checkbox" class="h-4 w-4 text-indigo-600 rounded" />
            <span class="ml-2 text-gray-600">Remember me</span>
          </label>
          <a href="#" class="text-indigo-600 hover:underline">Forgot password?</a>
        </div>
        <button
          type="submit"
          class="w-full flex justify-center py-2 px-4 border border-transparent text-white font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Sign In
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const router = useRouter();
const toast = useToast();

const email = ref('');
const password = ref('');

const login = async () => {
  try {
    const res = await axios.post('/login', {
      email: email.value,
      password: password.value,
    });
    localStorage.setItem('token', res.data.token);
    toast.success('Login successful!');
    router.push('/dashboard');
  } catch (err) {
    toast.error('Login failed. Please check your credentials.');
  }
};
</script>

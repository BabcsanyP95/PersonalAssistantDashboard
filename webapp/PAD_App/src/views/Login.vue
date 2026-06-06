<template>
  <div class="min-h-screen bg-slate-100 flex">

    <!-- Branding -->
    <div class="hidden lg:flex w-1/2 bg-slate-900 text-white flex-col justify-center px-20">
      <h1 class="text-5xl font-bold mb-4">
        PAD
      </h1>

      <p class="text-slate-300 text-xl">
        Personal Assistant Dashboard
      </p>

      <p class="mt-6 text-slate-400">
        Track your finances, manage transactions and visualize your spending.
      </p>
    </div>

    <!-- Login -->

    <div class="flex-1 flex justify-center items-center p-6">

      <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">

        <h2 class="text-3xl font-bold mb-2">
          Welcome back
        </h2>

        <p class="text-gray-500 mb-6">
          Sign in to continue
        </p>

        <input v-model="email" placeholder="Email" class="w-full border rounded-lg p-3 mb-4" />

        <input v-model="password" type="password" placeholder="Password" class="w-full border rounded-lg p-3 mb-6" />

        <button @click="loginUser" class="w-full bg-slate-900 text-white rounded-lg py-3 hover:bg-slate-800">
          Sign In
        </button>

        <div class="mt-6 text-center text-sm text-gray-500">

          Don't have an account?

          <RouterLink to="/register" class="text-blue-600 ml-1 hover:underline">
            Register
          </RouterLink>

        </div>

      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref<string | null>(null)

const auth = useAuthStore()
const router = useRouter()

const loginUser = async () => {
  try {
    error.value = null

    await auth.login({
      email: email.value,
      password: password.value
    })

    router.push('/')
  } catch (e: any) {
    error.value = 'Login failed'
    console.error(e)
  }
}
</script>
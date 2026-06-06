<template>
  <div class="min-h-screen bg-slate-100 flex">

    <!-- Left branding -->
    <div
      class="hidden lg:flex w-1/2 bg-slate-900 text-white flex-col justify-center px-20"
    >
      <h1 class="text-5xl font-bold mb-4">
        PAD
      </h1>

      <p class="text-xl text-slate-300">
        Personal Assistant Dashboard
      </p>

      <p class="mt-6 text-slate-400 leading-relaxed">
        Create your account and start tracking your finances,
        transactions, budgets and spending habits.
      </p>
    </div>

    <!-- Register card -->
    <div class="flex-1 flex justify-center items-center p-6">

      <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">

        <h2 class="text-3xl font-bold mb-2">
          Create Account
        </h2>

        <p class="text-gray-500 mb-6">
          Join PAD today.
        </p>

        <div class="space-y-4">

          <input
            v-model="form.name"
            type="text"
            placeholder="Full Name"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-slate-800 outline-none"
          />

          <input
            v-model="form.email"
            type="email"
            placeholder="Email Address"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-slate-800 outline-none"
          />

          <input
            v-model="form.password"
            type="password"
            placeholder="Password"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-slate-800 outline-none"
          />

          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-slate-800 outline-none"
          />

        </div>

        <button
          @click="registerUser"
          :disabled="loading"
          class="w-full mt-6 bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg transition disabled:opacity-50"
        >
          {{ loading ? "Creating account..." : "Create Account" }}
        </button>

        <p
          v-if="error"
          class="mt-4 text-red-500 text-sm"
        >
          {{ error }}
        </p>

        <div class="mt-8 text-center text-sm text-gray-500">

          Already have an account?

          <RouterLink
            to="/login"
            class="text-blue-600 hover:underline font-medium"
          >
            Sign In
          </RouterLink>

        </div>

      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()

const loading = ref(false)
const error = ref('')

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const registerUser = async () => {
  error.value = ''

  loading.value = true

  try {
    await auth.register(form)

    router.push('/dashboard')
  } catch (e: any) {
    error.value =
      e.response?.data?.message ??
      'Registration failed.'
  } finally {
    loading.value = false
  }
}
</script>
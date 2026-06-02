<template>
  <div>
    <h1>Login</h1>

    <input v-model="email" placeholder="email" />
    <input v-model="password" type="password" placeholder="password" />

    <button @click="loginUser">Login</button>

    <p v-if="error">{{ error }}</p>
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

    await auth.login(email.value, password.value)

    router.push('/')
  } catch (e: any) {
    error.value = 'Login failed'
    console.error(e)
  }
}
</script>
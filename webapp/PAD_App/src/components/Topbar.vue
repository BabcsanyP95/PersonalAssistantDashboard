<template>
  <div class="topbar">
    <div>Dashboard</div>

    <div>
      <span v-if="auth.user" class="me-3">{{ auth.user.name }}</span>
      <button @click="logout">Logout</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '../stores/auth.ts'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const logout = () => {
  auth.logout()
  router.push('/login')
}
</script>

<style scoped>
.topbar {
  height: 60px;
  background: #f3f4f6;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
}
</style>
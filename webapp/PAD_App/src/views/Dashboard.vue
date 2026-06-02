<template>
  <div class="dashboard">
    <h1>Dashboard</h1>

    <p v-if="auth.user">
      Welcome, {{ auth.user.name }} 👋
    </p>

    <div class="cards">
      <div class="card">
        <h3>Balance</h3>
        <p>$2,450</p>
      </div>

      <div class="card">
        <h3>Income</h3>
        <p>$3,200</p>
      </div>

      <div class="card">
        <h3>Expenses</h3>
        <p>$750</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()

onMounted(async () => {
  if (auth.token && !auth.user) {
    await auth.fetchUser()
  }
})
</script>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.card {
  background: #f3f4f6;
  padding: 20px;
  border-radius: 12px;
}
</style>
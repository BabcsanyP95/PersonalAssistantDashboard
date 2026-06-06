<template>
  <header class="h-14 bg-white border-b flex items-center justify-between px-4 md:px-6">
    <div class="flex items-center gap-3">

      <!-- MOBILE MENU BUTTON -->
      <button class="md:hidden text-gray-700 text-xl" @click="emit('toggle-sidebar')">
        ☰
      </button>

    </div>

    <!-- Left: Page Title -->
    <div class="flex items-center gap-3">
      <h1 class="font-semibold text-gray-800">
        {{ title }}
      </h1>
    </div>

    <!-- Right: User section -->
    <div class="flex items-center gap-4">

      <!-- User name -->
      <div class="hidden md:block text-sm text-gray-600">
        {{ auth.user?.name }}
      </div>

      <!-- Avatar -->
      <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-sm">
        {{ initials }}
      </div>

      <!-- Logout -->
      <button @click="logout" class="text-sm text-red-500 hover:text-red-600">
        Logout
      </button>

    </div>

  </header>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const emit = defineEmits(['toggle-sidebar'])

const logout = () => {
  auth.logout()
  router.replace('/login')
}

/**
 * Dynamic page title based on route
 */
const title = computed(() => {
  switch (route.path) {
    case '/dashboard':
      return 'Dashboard'

    case '/transactions':
      return 'Transactions'

    case '/categories':
      return 'Categories'

    default:
      return 'App'
  }
})

/**
 * User initials (nice UI touch)
 */
const initials = computed(() => {
  const name = auth.user?.name ?? ''

  return name
    .split(' ')
    .filter(Boolean)
    .map((n: string) => n[0])
    .slice(0, 2)
    .join('')
    .toUpperCase()
})
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
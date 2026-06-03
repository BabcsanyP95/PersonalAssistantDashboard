<template>
  <aside class="w-64 bg-slate-900 text-white flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-slate-800">
      <h1 class="text-xl font-bold">
        PAD
      </h1>

      <p class="text-slate-400 text-sm">
        Personal Assistant Dashboard
      </p>
    </div>

    <nav class="flex-1 p-4 space-y-2">
      <RouterLink v-for="link in links" :key="link.path" :to="link.path"
        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-800 transition"
        active-class="bg-slate-800 font-medium">
        <component :is="link.icon" :size="18" />
        {{ link.name }}
      </RouterLink>
    </nav>

    <!-- User Section -->
    <div class="p-4 border-t border-slate-800">
      <p class="text-sm text-slate-300">
        {{ auth.user?.name }}
      </p>

      <button @click="logout" class="mt-2 text-red-400 hover:text-red-300 text-sm">
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import {
  LayoutDashboard,
  Wallet,
  Tags,
} from 'lucide-vue-next'


const router = useRouter()
const auth = useAuthStore()

const links = [
  {
    name: 'Dashboard',
    path: '/dashboard',
    icon: LayoutDashboard,
  },
  {
    name: 'Transactions',
    path: '/transactions',
    icon: Wallet,
  },
  {
    name: 'Categories',
    path: '/categories',
    icon: Tags,
  },
]

const logout = () => {
  auth.logout()

  router.push('/login')
}
</script>

<style scoped>
.sidebar {
  width: 220px;
  background: #111827;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  height: 100vh;
}

nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

a {
  color: white;
  text-decoration: none;
  opacity: 0.8;
}

a:hover {
  opacity: 1;
}
</style>
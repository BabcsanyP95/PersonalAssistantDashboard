<template>
  <!-- overlay -->
  <div v-if="isOpen" class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="isOpen = false" />

  <!-- sidebar -->
  <aside :class="[
    'fixed md:static z-50 top-0 left-0 h-screen w-64 bg-slate-900 text-white flex flex-col transition-transform duration-300 overflow-y-auto',
    isOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
  ]">
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
  PiggyBank
} from 'lucide-vue-next'
import { ref } from 'vue'

const router = useRouter()
const auth = useAuthStore()
const isOpen = ref(false)

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
  {
    name: "Budgets",
    path: "/budgets",
    icon: PiggyBank,
  },
]

const logout = () => {
  auth.logout()

  router.replace('/login')
}

const open = () => (isOpen.value = true)
const close = () => (isOpen.value = false)

defineExpose({
  open,
  close,
})
</script>

<style scoped></style>
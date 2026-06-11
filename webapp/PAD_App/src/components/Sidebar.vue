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
      <RouterLink v-for="link in links" :key="link.path" :to="link.path" @click="close"
        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-slate-800 transition"
        active-class="bg-slate-800 font-medium">
        <component :is="link.icon" :size="18" />
        {{ link.name }}
      </RouterLink>
    </nav>

    <!-- User Section -->
    <div class="p-4 border-t border-slate-800">
      <div class="flex items-center gap-3 mb-3">

        <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-semibold">
          {{ initials }}
        </div>

        <div>
          <p class="text-sm font-medium">
            {{ auth.user?.name }}
          </p>

          <p class="text-xs text-slate-400">
            {{ auth.user?.email }}
          </p>
        </div>

      </div>

      <button @click="logout" class="w-full rounded-lg bg-red-500/20 py-2 text-red-400 hover:bg-red-500/30 transition">
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
  PiggyBank,
  Target,
} from 'lucide-vue-next'
import { ref, computed } from 'vue'

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
    name: 'Budgets',
    path: '/budgets',
    icon: PiggyBank,
  },
  {
    name: 'Savings Goals',
    path: '/savings',
    icon: Target,
  },
]

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
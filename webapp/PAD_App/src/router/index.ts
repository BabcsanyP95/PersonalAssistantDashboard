import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

import MainLayout from '../layouts/MainLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Transactions from '../views/Transactions.vue'
import Categories from '../views/Categories.vue'
import { useAuthStore } from '@/stores/auth.ts'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    // PUBLIC ROUTES
    {
      path: '/login',
      component: Login,
    },
    {
      path: '/register',
      component: Register,
    },

    // PROTECTED APP
    {
      path: '/',
      component: MainLayout,
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          redirect: '/dashboard',
        },
        {
          path: 'dashboard',
          component: Dashboard,
        },
        {
          path: 'transactions',
          component: Transactions,
        },
        {
          path: 'categories',
          component: Categories,
        },
      ],
    },
  ],
})

// auth guard
router.beforeEach(async (to) => {
    const auth = useAuthStore()

    // wait until auth is initialized
    if (!auth.authReady) {
        await auth.fetchUser()
    }

    const isLoggedIn = !!auth.token

    if (to.meta.requiresAuth && !isLoggedIn) {
        return '/login'
    }

    if (to.path === '/login' && isLoggedIn) {
        return '/dashboard'
    }
})

export default router
import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

import MainLayout from '../layouts/MainLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Transactions from '../views/Transactions.vue'
import Categories from '../views/Categories.vue'

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
router.beforeEach((to) => {
  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    return '/login'
  }
})

export default router
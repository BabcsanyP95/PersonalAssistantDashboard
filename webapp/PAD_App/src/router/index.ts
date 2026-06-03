import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Login.vue'
import MainLayout from '../layouts/MainLayout.vue'

import Dashboard from '../views/Dashboard.vue'
import Transactions from '../views/Transactions.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      component: Login,
    },

    {
      path: '/',
      component: MainLayout,
      meta: { requiresAuth: true },
      children: [
        {
          path: 'dashboard',
          component: Dashboard,
        },
        {
          path: 'transactions',
          component: Transactions,
        },
        {
          path: '',
          redirect: '/dashboard',
        },
      ],
    },
  ],
})

router.beforeEach((to) => {
    const token = localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
        return '/login'
    }
})

export default router
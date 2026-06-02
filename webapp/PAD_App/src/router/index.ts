import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Login.vue'
import MainLayout from '../layouts/MainLayout.vue'
import Dashboard from '../views/Dashboard.vue'

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
      children: [
        {
          path: '',
          component: Dashboard,
        },
      ],
    },
  ],
})

export default router
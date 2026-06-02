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
            meta: { requiresAuth: true }
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
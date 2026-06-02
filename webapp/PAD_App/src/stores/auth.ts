import { defineStore } from 'pinia'
import api from '../api/http'

export interface User {
    id: number
    name: string
    email: string
}

interface AuthState {
    user: User | null
    token: string | null
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => ({
        user: null,
        token: localStorage.getItem('token'),
    }),

    actions: {
        // LOGIN
        async login(email: string, password: string) {
            const res = await api.post('/login', {
                email,
                password,
            })

            this.token = res.data.token
            this.user = res.data.user

            if (this.token) {
                localStorage.setItem('token', this.token)
            }
        },

        // GET LOGGED USER
        async fetchUser() {
            const res = await api.get('/user')
            this.user = res.data
        },

        // LOGOUT
        logout() {
            this.user = null
            this.token = null
            localStorage.removeItem('token')
        },
    },
})
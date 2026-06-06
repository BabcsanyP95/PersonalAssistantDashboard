import { defineStore } from 'pinia'
import { login, register, getUser, logout } from '../api/auth'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as any,
        token: localStorage.getItem('token') || '',
        loading: false,
        authReady: false
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {

        async login(payload: { email: string; password: string }) {
            const res = await login(payload)

            this.token = res.token
            localStorage.setItem('token', res.token)

            await this.fetchUser() // 👈 IMPORTANT
        },

        async register(payload: {
            name: string
            email: string
            password: string
            password_confirmation: string
        }) {
            const res = await register(payload)

            this.token = res.token
            localStorage.setItem('token', res.token)

            await this.fetchUser() // 👈 IMPORTANT
        },

        async fetchUser() {
            if (!this.token) return

            try {
                const res = await getUser()
                this.user = res
            } catch (e) {
                // token invalid → reset auth
                this.logout()
            } finally
            {
              this.authReady = true
            }

        },

        async logout() {
            try {
                await logout()
            } finally {
                this.user = null
                this.token = ''
                localStorage.removeItem('token')
            }
        },
    },
})
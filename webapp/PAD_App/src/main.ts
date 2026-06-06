import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import './style.css'
import { useAuthStore } from './stores/auth.ts'

const app = createApp(App)

const pinia = createPinia() // create FIRST

app.use(pinia)              // register Pinia
app.use(router)             // register router

const auth = useAuthStore()
auth.initAuth()

app.mount('#app')
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'

const app = createApp(App)

const pinia = createPinia() // create FIRST

app.use(pinia)              // register Pinia
app.use(router)             // register router

app.mount('#app')
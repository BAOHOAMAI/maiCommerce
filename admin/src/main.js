import './index.css'
import { createApp } from 'vue';
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';
import App from './App.vue'
import router from './router'
import store from './store'
import './index.css';
const app = createApp(App)

app.use(router)
app.use(store)
app.use( CkeditorPlugin )

app.mount('#app')

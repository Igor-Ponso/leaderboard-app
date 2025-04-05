// src/main.ts
import { createApp } from 'vue'
import App from '@/App.vue'
import { registerPlugins } from '@/plugins'

import ElementPlus from 'element-plus'
import pt from 'element-plus/es/locale/lang/pt-br'
import 'element-plus/dist/index.css'
import '@/styles/index.scss'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import 'element-plus/theme-chalk/dark/css-vars.css'
import 'element-plus/theme-chalk/src/message.scss'
import 'element-plus/theme-chalk/src/message-box.scss'
import 'element-plus/theme-chalk/src/notification.scss'
import 'element-plus/theme-chalk/src/loading.scss'
import 'element-plus/theme-chalk/src/tooltip.scss'
import '@/styles/unocss.css';
import 'uno.css'

const app = createApp(App)

// Register Element Plus icons
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
  app.component(key, component)
}

// Register other plugins
registerPlugins(app)
app.use(ElementPlus, {
  locale: pt,
})

// Mount the app
app.mount('#app')

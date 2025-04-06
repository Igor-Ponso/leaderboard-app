/**
 * plugins/index.ts
 *
 * Automatically included in `./src/main.ts`
 */

// Plugins
import pinia from '@/stores'
import router from '@/router'
import i18n from './i18n'
import { createHead } from '@vueuse/head'
import Toast, { PluginOptions } from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import { useDark } from '@vueuse/core'

useDark();

// Types
import type { App } from 'vue'

const toastOptions: PluginOptions = {
  timeout: 5000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.7,
  showCloseButtonOnHover: true,
  hideProgressBar: false,
  closeButton: 'button',
  icon: true,
  rtl: false,
  transition: 'Vue-Toastification__slideBlurred',
}

export function registerPlugins(app: App) {
  app
    .use(router)
    .use(createHead())
    .use(i18n)
    .use(pinia)
    .use(Toast, toastOptions)
}

// src/router/index.ts
import { createRouter, createWebHistory } from 'vue-router'
import { setupLayouts } from 'virtual:generated-layouts'
import routes from 'virtual:generated-pages';

routes.push({ path: '/:catchAll(.*)', redirect: '/404' })

const router = createRouter({
  history: createWebHistory(),
  routes: setupLayouts(routes),
})

export default router
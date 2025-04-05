// api/paths.ts
import type { Pages } from '@/interfaces/Pages'
import { baseApiUrl } from './config'

export const rootPaths = ['/home']

export const pages: Pages = {
  home: {
    path: '/home',
    title: 'Dashboard',
    icon: 'house',
    onMenu: true,
  },

  welcome: {
    path: '/welcome',
    title: 'Dashboard',
  },

  logout: {
    path: '/logout',
    title: 'Sair',
    onMenu: false,
  },
}

export const endpoints = {
  permissions: {
    get: `${baseApiUrl}/permissoes`,
  },

  profiles: {
    get: `${baseApiUrl}/perfis`,
    delete: (hash: string) => `${baseApiUrl}/perfis/${hash}`,
    create: `${baseApiUrl}/perfis`,
    update: (hash: string) => `${baseApiUrl}/perfis/${hash}`,
  },
}

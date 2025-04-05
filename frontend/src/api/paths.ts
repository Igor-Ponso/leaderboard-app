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

  login: {
    path: '/auth/login',
    title: 'Login',
    onMenu: false,
  },

  register: {
    path: '/auth/register',
    title: 'Criar Conta',
    onMenu: false,
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

}

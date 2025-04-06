// api/paths.ts
import type { Pages } from '@/interfaces/Pages'
import { baseApiUrl } from './config'

export const rootPaths = ['/home']

export const pages: Pages = {
  dashboard: {
    path: '/auth/dashboard',
    title: 'Dashboard',
    icon: 'house',
    onMenu: true,
  },

  players: {
    path: '/auth/players',
    title: 'Players',
    icon: 'user',
    onMenu: true,
  },

  login: {
    path: '/login',
    title: 'Login',
    onMenu: false,
  },

  register: {
    path: '/register',
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
  auth: {
    register: `${baseApiUrl}/register`,
    login: `${baseApiUrl}/login`,
    logout: `${baseApiUrl}/logout`,
    forgotPassword: `${baseApiUrl}/forgot-password`,
    resetPassword: `${baseApiUrl}/reset-password`,
    sendVerification: `${baseApiUrl}/email/verification-notification`,
    verifyEmail: (id: number, hash: string) =>
      `${baseApiUrl}/verify-email/${id}/${hash}`,
  },

  players: {
    get: `${baseApiUrl}/api/players`,
    show: (hash: string) => `${baseApiUrl}/api/players/${hash}`,
    create: `${baseApiUrl}/api/players`,
    update: (hash: string) => `${baseApiUrl}/api/players/${hash}`,
    delete: (hash: string) => `${baseApiUrl}/api/players/${hash}`,
  },
}
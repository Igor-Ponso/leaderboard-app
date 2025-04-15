import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'
import vue from '@vitejs/plugin-vue'
import path from 'node:path'
import {
  presetAttributify,
  presetIcons,
  transformerDirectives,
  transformerVariantGroup,
} from 'unocss'
import Unocss from 'unocss/vite'
import AutoImport from 'unplugin-auto-import/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import Components from 'unplugin-vue-components/vite'
import { defineConfig } from 'vite'
import Inspect from 'vite-plugin-inspect'
import Pages from 'vite-plugin-pages'
import Layouts from 'vite-plugin-vue-layouts-next'

import { presetWind3 } from '@unocss/preset-wind3'

import vueDevTools from 'vite-plugin-vue-devtools'

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
    extensions: ['.js', '.json', '.ts', '.vue'],
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@use "@/styles/element/index.scss" as *;`,
      },
    },
  },
  plugins: [
    vue(),
    VueI18nPlugin({
      include: path.resolve(__dirname, '@/locales/**'),
    }),
    AutoImport({
      dts: '@types/auto-imports.d.ts',
      imports: ['vue', '@vueuse/core', '@vueuse/head', 'vue-router'],
      resolvers: [ElementPlusResolver()],
      vueTemplate: true,
    }),
    Layouts({
      layoutsDirs: 'src/layouts',
      defaultLayout: 'Default',
    }),
    Pages({
      extensions: ['vue'],
    }),
    Components({
      extensions: ['vue', 'md'],
      include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
      resolvers: [
        ElementPlusResolver({
          importStyle: 'sass',
        }),
      ],
      dirs: [
        'src/components/LayoutCompositions',
        'src/components',
        'src/widgets',
      ],
      dts: '@types/components.d.ts',
    }),
    Unocss({
      presets: [
        presetWind3(),
        presetAttributify(),
        presetIcons({
          scale: 1.2,
          warn: true,
        }),
      ],
      transformers: [transformerDirectives(), transformerVariantGroup()],
    }),

    Inspect(),
    vueDevTools(),
  ],
  server: {
    host: 'localhost',
    port: 3000,
    cors: {
      origin: 'http://localhost:5000',
      credentials: true,
    },
  },
  build: {
    chunkSizeWarningLimit: 1600,
  },
})

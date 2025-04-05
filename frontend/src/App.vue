<script setup lang="ts">
import { useHead } from '@vueuse/head'

useHead({
  title: 'Spring LeaderBoard',
  meta: [
    { name: 'description', content: '' },
    { name: 'author', content: 'Igor Ponso' },
  ],
  link: [
    {
      rel: 'stylesheet',
      href: 'https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css',
    },
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap',
    },
  ],
})

</script>

<template>
  <router-view />
</template>
<script lang="ts" setup>
import { pages } from '@/api/paths'
import page_not_found from '@/assets/svgs/page_not_found.svg'
import { useRouter } from 'vue-router'

const router = useRouter()
</script>

<template>
  <el-container id="page-not-found">
    <img :src="page_not_found" />

    <div class="page-not-found__footer">
      <h2>Page Not Found</h2>
      <el-button @click="router.push(pages.welcome.path)">
        Take me home
      </el-button>
    </div>
  </el-container>
</template>

<style lang="stylus" scoped>
#page-not-found,
.page-not-found__footer
  display: flex
  flex-direction: column
  justify-content center
  img
    height 60dvh
    max-width 100dvw
.page-not-found__footer
  gap: 1rem
  align-items: center
</style>

<route lang="yaml">
  meta:
    layout: blank
  </route>
  
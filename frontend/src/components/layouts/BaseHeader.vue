<script setup lang="ts">
import { useRoute } from 'vue-router'
import { pages } from '@/api/paths'
import { computed } from 'vue'
import { onBack } from '@/composables/helpers'

const route = useRoute()

// Split the path into breadcrumbs

const currentPage = computed(() => {
  const path = route.path

  // Match dynamic paths with regex
  const matchedPage = Object.values(pages).find((page) => {
    const pathRegex = new RegExp(
      '^' + page.path.replace(/:[^/]+/g, '[^/]+') + '$',
    )
    return pathRegex.test(path)
  }) || { title: path, path }

  return matchedPage
})

</script>

<template>
  <el-header>
    <el-page-header @back="onBack" title="Voltar">
      
      <template #content>
        <h1 class="text-xl">{{ currentPage.title }}</h1>
      </template>
      <template #extra>
        <img
          style="width: 120px; padding-top: .7rem;"
          src="../../assets/logos/leaderboard.png"
          alt="Leaderboard App logo"
          fit
        />
      </template>
    </el-page-header>

    <div class="flex-grow" />
  </el-header>
</template>

<style scoped lang="stylus">

:deep(.ep-page-header__breadcrumb)
  margin-bottom 0px
</style>

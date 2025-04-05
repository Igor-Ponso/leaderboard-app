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

const breadcrumbs = computed(() => {
  const pathArray = route.path.split('/').filter((p) => p)

  const breadcrumbArray = pathArray.map((_, index) => {
    const fullPath = '/' + pathArray.slice(0, index + 1).join('/')

    // Try to find a matching page in the pages config
    const page = Object.values(pages).find((page) => {
      const pathRegex = new RegExp(
        '^' +
          page.path.replace(/:[^/]+/g, '[^/]+') + // Replace :params with regex
          '$',
      )
      return pathRegex.test(fullPath) // Test if it matches
    })

    return {
      title: page?.title || pathArray[index], // Use title if matched, else use path
      path: fullPath,
    }
  })

  return breadcrumbArray
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
          style="width: 150px"
          src="../../assets/img/cruzeiro_logo.png"
          alt="Cruzeiro logo"
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

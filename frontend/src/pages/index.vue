<script setup lang="ts">
import { pages } from '@/api/paths'
import { ElConfigProvider } from 'element-plus'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

/**
 * First page after App->router-view.
 * @name 'IndexPage'
 * @version 1.0.0
 */


const router = useRouter()
onMounted(async () => {
  router.push(pages.welcome)
})
</script>

<template>
  <el-config-provider>
    <router-view />
  </el-config-provider>
</template>

<route lang="yaml">
meta:
  layout: default
</route>

<script setup lang="ts">
import { toggleDark, isDark } from '@/composables'
import { Sunny, Moon } from '@element-plus/icons-vue'
</script>

<template>
  <el-footer height="60px" class="flex items-center justify-between px-8">
    <el-text>Spring Leaderboard</el-text>
    <LanguageSwitcher />
    <el-button size="large" @click="toggleDark()" type="info" circle>
      <el-icon>
        <component :is="isDark ? Moon : Sunny" />
      </el-icon>
    </el-button>
  </el-footer>
</template>

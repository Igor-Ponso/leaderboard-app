<script setup lang="ts">
import { toggleDark } from '@/composables';

</script>

<template>
  <el-footer>
    <ClusterL between center full-width>
      <el-text>Spring Leaderboard</el-text>
      <ClusterL>
        <el-text>INFO NAME</el-text>
        <el-text></el-text>
        <el-button size="large" @click="toggleDark()" type="info" circle>
          <i inline-flex i="dark:ep-moon ep-sunny" />
        </el-button>
      </ClusterL>
    </ClusterL>
  </el-footer>
</template>

<style lang="stylus">
.ep-footer
  display flex
  padding-left: 5rem
</style>

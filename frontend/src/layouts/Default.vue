<template>
    <el-config-provider namespace="ep">
      <BaseHeader />
      <div class="flex main-container">
        <BaseSide />
        <el-main>
          <el-card shadow="always">
            <router-view />
          </el-card>
        </el-main>
      </div>
      <BaseFooter />
    </el-config-provider>
  </template>
  
  <style scoped lang="stylus">
  .main-container {
    height: calc(95dvh - var(--ep-menu-item-height) - 8px);
  }
  </style>
  
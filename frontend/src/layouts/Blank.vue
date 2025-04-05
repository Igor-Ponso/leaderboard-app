<template>
  <el-config-provider namespace="ep">
    <div class="blank-layout">
      <router-view />
    </div>
  </el-config-provider>
</template>

<style scoped lang="stylus">
.blank-layout
  display flex
  align-items center
  justify-content center
  height 100vh
  overflow hidden
  flex-direction column
</style>

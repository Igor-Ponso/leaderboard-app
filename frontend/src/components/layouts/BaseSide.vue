<script setup lang="ts">
import { computed, ref } from 'vue'
import { pages } from '@/api/paths'
import { Expand, Fold, SwitchButton } from '@element-plus/icons-vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const isCollapse = ref(true)
const dialogVisible = ref(false)

const toggleCollapse = () => {
  isCollapse.value = !isCollapse.value
}

const menuPages = computed(() => {
  return Object.values(pages).filter((page) => page.onMenu)
})

const handleOpenDialog = () => {
  dialogVisible.value = true
}

const cancelLogoff = () => {
  dialogVisible.value = false
}

const confirmLogoff = async () => {
  await authStore.logout()
  dialogVisible.value = false
  router.push(pages.welcome.path)
}
</script>

<template>
  <el-aside
    :width="isCollapse ? '65px' : '200px'"
    class="aside-transition side-menu"
  >
    <el-menu
      :default-active="menuPages[0]?.path"
      class="h-full"
      :collapse="isCollapse"
      collapse-transition
    >
      <!-- Collapse Button -->
      <el-menu-item index="toggle" @click="toggleCollapse" style="justify-content: end">
        <el-icon v-if="isCollapse"><Expand /></el-icon>
        <el-icon v-else><Fold /></el-icon>
      </el-menu-item>

      <!-- Navigation Pages -->
      <template v-for="(page, key) in menuPages" :key="key">
        <router-link :to="page.path" class="router-link">
          <el-menu-item :index="page.path">
            <el-tooltip v-if="isCollapse" :content="page.title" placement="right">
              <el-icon><component :is="page.icon || 'Document'" /></el-icon>
            </el-tooltip>
            <el-icon v-else><component :is="page.icon || 'Document'" /></el-icon>
            <transition name="fade" mode="out-in">
              <span v-show="!isCollapse" class="menu-title">{{ page.title }}</span>
            </transition>
          </el-menu-item>
        </router-link>
      </template>

      <!-- Logoff -->
      <el-menu-item index="logoff" @click="handleOpenDialog">
        <el-tooltip v-if="isCollapse" content="Log out" placement="right">
          <el-icon><SwitchButton /></el-icon>
        </el-tooltip>
        <el-icon v-else><SwitchButton /></el-icon>
        <transition name="fade" mode="out-in">
          <span v-show="!isCollapse" class="menu-title">Log out</span>
        </transition>
      </el-menu-item>
    </el-menu>
  </el-aside>

  <!-- Logoff Confirmation Dialog -->
  <el-dialog
    v-model="dialogVisible"
    title="Log out"
    width="500"
    align-center
    :show-close="false"
  >
    <el-text>Are you sure you want to end your session?</el-text>
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="cancelLogoff">Cancel</el-button>
        <el-button type="info" @click="confirmLogoff">Confirm</el-button>
      </div>
    </template>
  </el-dialog>
</template>

<style scoped lang="stylus">
.side-menu
  overflow-x: hidden

.aside-transition
  transition: width 0.3s

.el-menu
  &.el-menu--collapse
    .el-menu-item
      .el-tooltip
        transition: none
</style>

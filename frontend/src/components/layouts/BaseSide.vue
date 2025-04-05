<script setup lang="ts">
import { computed, ref } from 'vue'
import { pages } from '@/api/paths'
import { Expand, Fold, SwitchButton } from '@element-plus/icons-vue'

const isCollapse = ref(true)

const toggleCollapse = () => {
  isCollapse.value = !isCollapse.value
}

const menuPages = computed(() => {
  return Object.values(pages).filter((page) => page.onMenu)
})

const dialogVisible = ref(false)

const logoffPage = pages.logout

const handleClose = () => {
  dialogVisible.value = true
}

const cancelLogoff = () => {
  dialogVisible.value = false
}
</script>

<template>
  <el-aside
    :width="isCollapse ? '65px' : '200px'"
    class="aside-transition side-menu"
  >
    <el-menu
      :default-active="menuPages[0].path"
      class="h-full"
      :collapse="isCollapse"
      collapse-transition
    >
      <el-menu-item
        index="toggle"
        @click="toggleCollapse"
        style="justify-content: end"
      >
        <el-icon v-if="isCollapse">
          <Expand />
        </el-icon>
        <el-icon v-else>
          <Fold />
        </el-icon>
      </el-menu-item>
      <template v-for="(page, key) in menuPages" :key="key">
        <router-link :to="page.path" class="router-link">
          <el-menu-item :index="page.path">
            <el-tooltip
              v-if="isCollapse"
              :content="page.title"
              placement="right"
            >
              <el-icon>
                <component :is="page.icon || 'Document'" />
              </el-icon>
            </el-tooltip>
            <el-icon v-else>
              <component :is="page.icon || 'Document'" />
            </el-icon>
            <transition name="fade" mode="out-in">
              <template #default>
                <span v-show="!isCollapse" class="menu-title">
                  {{ page.title }}
                </span>
              </template>
            </transition>
          </el-menu-item>
        </router-link>
      </template>
      <el-menu-item index="logoff" @click="handleClose">
        <el-tooltip
          v-if="isCollapse"
          :content="logoffPage.title"
          placement="right"
        >
          <el-icon>
            <SwitchButton />
          </el-icon>
        </el-tooltip>
        <el-icon v-else>
          <SwitchButton />
        </el-icon>
        <transition name="fade" mode="out-in">
          <template #default>
            <span v-show="!isCollapse" class="menu-title">
              {{ logoffPage.title }}
            </span>
          </template>
        </transition>
      </el-menu-item>
    </el-menu>
  </el-aside>
  <el-dialog
    v-model="dialogVisible"
    title="Finalizar sessão"
    width="500"
    align-center
    :show-close="false"
    :before-close="handleClose"
  >
    <el-text>Tem certeza que deseja finalizar sua sessão?</el-text>
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="cancelLogoff">Cancelar</el-button>
        <el-button type="info">
          Confirmar
        </el-button>
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

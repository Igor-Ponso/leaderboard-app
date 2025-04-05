<script lang="ts" setup>
import { ref, defineEmits } from 'vue'
const drawer = ref(false)
const emit = defineEmits(['update:modelValue'])
const cancelClick = () => {
  drawer.value = false
  emit('update:modelValue', false)
}
const confirmClick = () => {
  drawer.value = false
  emit('update:modelValue', false)
}
</script>

<template>
  <el-drawer v-model="drawer" direction="rtl">
    <template #header>
      <h4>set title by slot</h4>
    </template>
    <template #default>
      <div>Default template</div>
    </template>
    <template #footer>
      <div style="flex: auto">
        <el-button @click="cancelClick">cancel</el-button>
        <el-button type="info" @click="confirmClick">confirm</el-button>
      </div>
    </template>
  </el-drawer>
</template>

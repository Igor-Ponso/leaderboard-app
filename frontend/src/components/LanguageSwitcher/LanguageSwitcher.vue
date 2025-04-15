<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import { useI18n } from 'vue-i18n'

const { locale, availableLocales } = useI18n()

const selectedLang = ref(locale.value)

const changeLanguage = (val: string) => {
  locale.value = val
  localStorage.setItem('app_lang', val)
}

watchEffect(() => {
  const saved = localStorage.getItem('app_lang')
  if (saved && availableLocales.includes(saved)) {
    locale.value = saved
    selectedLang.value = saved
  }
})
</script>

<template>
  <el-radio-group
    v-model="selectedLang"
    @change="changeLanguage"
    size="small"
    class="flex gap-2 items-center"
  >
    <el-radio-button value="en">EN</el-radio-button>
    <el-radio-button value="fr">FR</el-radio-button>
  </el-radio-group>
</template>

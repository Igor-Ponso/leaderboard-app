<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import type { FormInstance } from 'element-plus'
import { pages } from '@/api/paths'
import { useAuthStore } from '@/stores/auth'

import { useI18n } from 'vue-i18n'

const { t: $t } = useI18n()

const router = useRouter()
const authStore = useAuthStore()
const formRef = ref<FormInstance>()

const form = reactive({
  email: '',
  password: '',
})

const rules = {
  email: [
    { required: true, message: $t('login.emailRequired'), trigger: 'blur' },
    {
      type: 'email',
      message: $t('login.emailInvalid'),
      trigger: ['blur', 'change'],
    },
  ],
  password: [
    { required: true, message: $t('login.passwordRequired'), trigger: 'blur' },
  ],
}

const handleSubmit = async () => {
  formRef.value?.validate(async (valid) => {
    if (valid) {
      await authStore.login(form)
      router.push(pages.dashboard.path)
    }
  })
}
</script>

<template>
  <section
    class="min-h-[100dvh] flex items-center justify-center px-4 sm:px-6 md:px-8 bg-gradient-to-br from-[#062345] to-[#053765]"
  >
    <el-card class="w-full max-w-md shadow-xl">
      <div class="text-center space-y-2 mb-6">
        <h2 class="text-xl font-bold pixel-font">{{ $t('login.title') }}</h2>
        <p class="text-sm text-gray-400">{{ $t('login.subtitle') }}</p>
      </div>
      <el-form
        :model="form"
        :rules="rules"
        ref="formRef"
        label-position="top"
        autocomplete="off"
        @submit.prevent="handleSubmit"
      >
        <el-form-item :label="$t('login.emailLabel')" prop="email">
          <el-input
            v-model="form.email"
            :placeholder="$t('login.emailPlaceholder')"
          />
        </el-form-item>

        <el-form-item :label="$t('login.passwordLabel')" prop="password">
          <el-input
            v-model="form.password"
            :placeholder="$t('login.passwordPlaceholder')"
            show-password
          />
        </el-form-item>

        <div class="flex justify-between items-center mt-2 mb-4">
          <el-link type="info" :underline="false">
            {{ $t('login.forgotPassword') }}
          </el-link>
        </div>

        <el-button type="primary" class="w-full" @click="handleSubmit">
          {{ $t('login.loginButton') }}
        </el-button>

        <p class="mt-4 text-center text-sm text-gray-400">
          {{ $t('login.noAccount') }}
          <el-link
            :href="pages.register.path"
            :router="true"
            type="warning"
            class="ml-1"
          >
            {{ $t('login.createAccount') }}
          </el-link>
        </p>
      </el-form>

      <LanguageSwitcher class="mt-8" />
    </el-card>
  </section>
</template>

<route lang="yaml">
meta:
  layout: blank
</route>

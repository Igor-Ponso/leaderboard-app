<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import type { FormInstance } from 'element-plus'
import { pages } from '@/api/paths'
import { useAuthStore } from '@/stores/auth'
import { useI18n } from 'vue-i18n'

const { t: $t } = useI18n()

const router = useRouter()
const formRef = ref<FormInstance>()
const auth = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const rules = {
  name: [
    {
      required: true,
      message: $t('register.nameRequired'),
      trigger: 'blur',
    },
  ],
  email: [
    { required: true, message: $t('register.emailRequired'), trigger: 'blur' },
    {
      type: 'email',
      message: $t('register.emailInvalid'),
      trigger: ['blur', 'change'],
    },
  ],
  password: [
    {
      required: true,
      message: $t('register.passwordRequired'),
      trigger: 'blur',
    },
  ],
  password_confirmation: [
    {
      required: true,
      message: $t('register.confirmPasswordRequired'),
      trigger: 'blur',
    },
    {
      validator: (_: any, value: string, callback: Function) => {
        if (value !== form.password) {
          callback(new Error($t('register.confirmPasswordMismatch')))
        } else {
          callback()
        }
      },
      trigger: ['blur', 'change'],
    },
  ],
}

const handleSubmit = async () => {
  formRef.value?.validate(async (valid) => {
    if (!valid) return

    try {
      await auth.register(form)
      router.push(pages.dashboard.path)
    } catch (_) {}
  })
}
</script>

<template>
  <section
    class="min-h-[100dvh] flex items-center justify-center px-4 sm:px-6 md:px-8 bg-gradient-to-br from-[#062345] to-[#053765]"
  >
    <el-card class="w-full max-w-md shadow-xl">
      <h2 class="text-center text-xl font-bold mb-6 pixel-font">
        {{ $t('register.title') }}
      </h2>

      <el-form
        :model="form"
        :rules="rules"
        ref="formRef"
        label-position="top"
        autocomplete="off"
        @submit.prevent="handleSubmit"
      >
        <el-form-item :label="$t('register.nameLabel')" prop="name">
          <el-input
            v-model="form.name"
            :placeholder="$t('register.namePlaceholder')"
          />
        </el-form-item>

        <el-form-item :label="$t('register.emailLabel')" prop="email">
          <el-input
            v-model="form.email"
            :placeholder="$t('register.emailPlaceholder')"
          />
        </el-form-item>

        <el-form-item :label="$t('register.passwordLabel')" prop="password">
          <el-input
            v-model="form.password"
            :placeholder="$t('register.passwordPlaceholder')"
            show-password
          />
        </el-form-item>

        <el-form-item
          :label="$t('register.confirmPasswordLabel')"
          prop="password_confirmation"
        >
          <el-input
            v-model="form.password_confirmation"
            :placeholder="$t('register.confirmPasswordPlaceholder')"
            show-password
          />
        </el-form-item>

        <el-button
          type="primary"
          class="w-full mt-4 pixel-font"
          @click="handleSubmit"
        >
          {{ $t('register.registerButton') }}
        </el-button>

        <p class="mt-4 text-center text-sm text-gray-700 dark:text-gray-300">
          {{ $t('register.alreadyHaveAccount') }}
          <el-link
            :underline="true"
            :href="pages.login.path"
            type="warning"
            class="ml-1"
          >
            {{ $t('register.loginLink') }}
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

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import type { FormInstance } from 'element-plus'
import { pages } from '@/api/paths'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const formRef = ref<FormInstance>()

const form = reactive({
  email: '',
  password: '',
})

const rules = {
  email: [
    { required: true, message: 'Email is required', trigger: 'blur' },
    { type: 'email', message: 'Invalid email', trigger: ['blur', 'change'] },
  ],
  password: [
    { required: true, message: 'Password is required', trigger: 'blur' },
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
        <h2 class="text-xl font-bold pixel-font">Login to your account</h2>
        <p class="text-sm text-gray-400">
          Access your leaderboard and manage your progress
        </p>
      </div>

      <el-form
        :model="form"
        :rules="rules"
        ref="formRef"
        label-position="top"
        autocomplete="off"
        @submit.prevent="handleSubmit"
      >
        <el-form-item label="Email" prop="email">
          <el-input v-model="form.email" placeholder="Enter your email" />
        </el-form-item>

        <el-form-item label="Password" prop="password">
          <el-input
            v-model="form.password"
            placeholder="Enter your password"
            show-password
          />
        </el-form-item>

        <div class="flex justify-between items-center mt-2 mb-4">
          <el-link type="info" :underline="false">Forgot Password?</el-link>
        </div>

        <el-button
          type="primary"
          class="w-full"
          @click="handleSubmit"
        >
          Login
        </el-button>

        <p class="mt-4 text-center text-sm text-gray-400">
          Don't have an account?
          <el-link
            :href="pages.register.path"
            :router="true"
            type="warning"
            class="ml-1"
          >
            Create one
          </el-link>
        </p>
      </el-form>
    </el-card>
  </section>
</template>

<route lang="yaml">
meta:
  layout: blank
</route>

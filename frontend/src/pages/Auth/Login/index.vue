<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import type { FormInstance } from 'element-plus'
import { pages } from '@/api/paths'

const router = useRouter()
const formRef = ref<FormInstance>()

const form = reactive({
  email: '',
  password: ''
})

const rules = {
  email: [
    { required: true, message: 'Email is required', trigger: 'blur' },
    { type: 'email', message: 'Invalid email', trigger: ['blur', 'change'] }
  ],
  password: [{ required: true, message: 'Password is required', trigger: 'blur' }]
}

const handleLogin = () => {
  formRef.value?.validate((valid) => {
    if (valid) {
      console.log('Logging in with', form)
      router.push(pages.home.path)
    }
  })
}
</script>

<template>
    <section
      class="min-h-[100dvh] flex items-center justify-center px-4 sm:px-6 md:px-8 bg-gradient-to-br from-[#062345] to-[#053765]"
    >
      <el-card class="w-full max-w-md shadow-2xl">
        <h2 class="text-center text-xl font-bold mb-4 dark:text-white">
          Welcome back
        </h2>
        <p class="text-center text-sm mb-6 text-gray-600 dark:text-gray-300">
          Log in to continue your quest
        </p>
  
        <el-form
          :model="form"
          :rules="rules"
          ref="formRef"
          label-position="top"
          autocomplete="off"
          @submit.prevent="handleLogin"
        >
          <el-form-item label="Email" prop="email">
            <el-input v-model="form.email" placeholder="Enter your email" />
          </el-form-item>
  
          <el-form-item label="Password" prop="password">
            <el-input
              v-model="form.password"
              type="password"
              show-password
              placeholder="Enter your password"
            />
          </el-form-item>
  
          <div class="flex justify-between items-center mb-4">
            <el-link type="info" :underline="false" href="#">Forgot password?</el-link>
          </div>
  
          <el-button
            type="primary"
            class="w-full pixel-font"
            @click="handleLogin"
          >
            Login
          </el-button>
  
          <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-300">
            Don’t have an account?
            
            <el-link :href="pages.register.path" :router="true" type="warning" class="ml-1">
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
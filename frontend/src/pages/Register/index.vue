<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import type { FormInstance } from 'element-plus'
import { pages } from '@/api/paths'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'

const router = useRouter()
const formRef = ref<FormInstance>()
const auth = useAuthStore()
const toast = useToast()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const rules = {
  name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
  email: [
    { required: true, message: 'Email is required', trigger: 'blur' },
    { type: 'email', message: 'Invalid email', trigger: ['blur', 'change'] },
  ],
  password: [
    { required: true, message: 'Password is required', trigger: 'blur' },
  ],
  password_confirmation: [
    {
      required: true,
      message: 'Please confirm your password',
      trigger: 'blur',
    },
    {
      validator: (_: any, value: string, callback: Function) => {
        if (value !== form.password) {
          callback(new Error("Passwords don't match"))
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
    } catch (err) {
      // A toast já será disparada no catch da store
    }
  })
}
</script>

<template>
  <section
    class="min-h-[100dvh] flex items-center justify-center px-4 sm:px-6 md:px-8 bg-gradient-to-br from-[#062345] to-[#053765]"
  >
    <el-card class="w-full max-w-md shadow-xl">
      <h2 class="text-center text-xl font-bold mb-6 pixel-font">
        Create your account
      </h2>

      <el-form
        :model="form"
        :rules="rules"
        ref="formRef"
        label-position="top"
        autocomplete="off"
        @submit.prevent="handleSubmit"
      >
        <el-form-item label="Name" prop="name" >
          <el-input v-model="form.name" placeholder="Enter your full name" />
        </el-form-item>

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

        <el-form-item label="Confirm Password" prop="password_confirmation">
          <el-input
            v-model="form.password_confirmation"
            placeholder="Confirm your password"
            show-password
          />
        </el-form-item>

        <el-button
          type="primary"
          class="w-full mt-4 pixel-font"
          @click="handleSubmit"
        >
          Register
        </el-button>

        <p class="mt-4 text-center text-sm text-gray-700 dark:text-gray-300">
          Already have an account?
          <el-link
            :underline="true"
            :href="pages.login.path"
            type="warning"
            class="ml-1"
          >
            Log in
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

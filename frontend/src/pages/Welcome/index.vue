<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

const goToLogin = () => router.push('/login')
const goToRegister = () => router.push('/register')
</script>

<template>
  <section
    class="relative h-[100dvh] bg-gradient-to-br from-[#062345] to-[#053765] text-white overflow-hidden flex items-center justify-center px-4 sm:px-6 md:px-12 flex-col"
  >
  <el-row justify="center" align="middle" class="w-full max-w-screen-xl z-10 h-full">
      <el-col :xs="24" :md="12" class="text-center md:text-left space-y-6">
        <h1 class="pixel-font font-bold text-[clamp(2rem,6vw,3rem)] leading-tight">
          Welcome to <span class="text-[#eba408]">Leaderboard</span>
        </h1>
        <p class="pixel-font text-[clamp(1rem,3.5vw,1.25rem)] text-[#f0f0f0] font-mono">
          Inspired by retro games. Built for modern challenges.
        </p>

        <div class="flex gap-4 justify-center md:justify-start flex-wrap">
          <el-button
            class="pixel-font"
            type="primary"
            size="large"
            round
            plain
            @click="goToRegister()"
          >
            Create Account
          </el-button>
          <el-button
            class="pixel-font"
            type="info"
            size="large"
            round
            plain
            @click="goToLogin()"
          >
            Login
          </el-button>
        </div>
      </el-col>

      <el-col
        :xs="24"
        :md="12"
        class="flex justify-center items-center mt-8 md:mt-0"
      >
        <img
          src="@/assets/illustrations/welcome-hero.png"
          alt="Pixel hero"
          class="w-full max-w-[360px] h-auto aspect-[4/5] rounded object-contain pixelated"
        />
      </el-col>
    </el-row>

    <Waves />
  </section>
</template>

<route lang="yaml">
meta:
  layout: blank
</route>

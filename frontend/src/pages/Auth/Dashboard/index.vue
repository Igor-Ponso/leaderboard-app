<script setup lang="ts">
import dayjs from 'dayjs'
import { onMounted, ref } from 'vue'
import ApexChart from 'vue3-apexcharts'
import { api } from '@/api/config'

const today = dayjs().format('DD/MM/YYYY')

const leaderboardSummary = ref({})
const loading = ref(true)
const scores = ref<string[]>([])
const userCounts = ref<number[]>([])
const averageAges = ref<number[]>([])

const fetchSummary = async () => {
  try {
    loading.value = true
    const { data } = await api.get('api/leaderboard-summary')

    leaderboardSummary.value = data

    scores.value = Object.keys(data).sort((a, b) => Number(b) - Number(a))
    userCounts.value = scores.value.map(score => data[score].names.length)
    averageAges.value = scores.value.map(score => data[score].average_age)
  } catch (e) {
    console.error('Failed to fetch summary', e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchSummary)
</script>

<template>
  <section class="max-w-[1200px] mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-bold">📈 Dashboard</h1>
      <div class="text-gray-500">{{ today }}</div>
    </div>

    <el-card v-loading="loading" shadow="hover">
      <template #header>
        <div class="flex justify-between items-center">
          <span>Scores Summary</span>
          <el-button size="small" @click="fetchSummary">Refresh</el-button>
        </div>
      </template>

      <ApexChart
        width="100%"
        type="bar"
        :options="{
          chart: { id: 'score-summary' },
          xaxis: { categories: scores },
          dataLabels: {
            enabled: true,
            formatter: (_: any, opts: any) => `Avg: ${averageAges[opts.dataPointIndex]}`
          },
          title: {
            text: 'Users Grouped by Score',
            align: 'left'
          }
        }"
        :series="[
          { name: 'Number of Users', data: userCounts }
        ]"
      />
    </el-card>
  </section>
</template>

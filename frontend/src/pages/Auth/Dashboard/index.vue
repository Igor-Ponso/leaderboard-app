<script setup lang="ts">
import { onMounted, ref, computed, watch } from 'vue'
import { useDark } from '@vueuse/core'
import dayjs from 'dayjs'
import ApexChart from 'vue3-apexcharts'
import { api } from '@/api/config'
import { useI18n } from 'vue-i18n'

const { t: $t } = useI18n()

const today = dayjs().format('DD/MM/YYYY')
const isDark = useDark()

interface ScoreGroup {
  names: string[]
  average_age: number
}

type LeaderboardSummary = Record<string, ScoreGroup>

const leaderboardSummary = ref<LeaderboardSummary>({})

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
    userCounts.value = scores.value.map((score) => data[score].names.length)
    averageAges.value = scores.value.map((score) => data[score].average_age)
  } catch (e) {
    console.error('Failed to fetch summary', e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchSummary)

// Chart options reativos com suporte a dark mode e i18n
const chartOptions = computed(() => ({
  chart: {
    id: 'score-summary',
    toolbar: {
      show: true,
    },
    background: 'transparent',
    foreColor: isDark.value ? '#f0f0f0' : '#333',
  },
  xaxis: {
    categories: scores.value,
    labels: {
      style: {
        colors: isDark.value ? '#f0f0f0' : '#333',
      },
    },
  },
  dataLabels: {
    enabled: true,
    formatter: (_: any, opts: any) =>
      `${$t('dashboard.dataLabelPrefix')}: ${averageAges.value[opts.dataPointIndex]}`,
    style: {
      colors: [isDark.value ? '#fff' : '#000'],
    },
  },
  title: {
    text: $t('dashboard.chartTitle'),
    align: 'left',
    style: {
      color: isDark.value ? '#f0f0f0' : '#333',
    },
  },
  tooltip: {
    custom: ({ dataPointIndex }: { dataPointIndex: number }) => {
      const score = scores.value[dataPointIndex]
      const group = leaderboardSummary.value[score]

      if (!group) return 'No data'

      const names = group.names || []
      const avgAge = group.average_age ?? '-'

      const maxNamesToShow = 5
      const displayedNames = names.slice(0, maxNamesToShow)
      const remaining = names.length - maxNamesToShow

      return `
    <div style="padding: 8px; max-width: 280px; font-size: 13px; line-height: 1.4;">
      <div style="font-weight: 600; margin-bottom: 4px;">Score: ${score}</div>
      <div style="margin-bottom: 4px;"><strong>Avg Age:</strong> ${avgAge}</div>
      <div style="margin-bottom: 4px;"><strong>Users:</strong></div>
      <ul style="margin: 0; padding-left: 1em;">
        ${displayedNames.map((name: string) => `<li>${name}</li>`).join('')}
        ${remaining > 0 ? `<li>+${remaining} more</li>` : ''}
      </ul>
    </div>
  `
    },
    theme: isDark.value ? 'dark' : 'light',
  },

  responsive: [
    {
      breakpoint: 768,
      options: {
        chart: { height: 300 },
        legend: { position: 'bottom' },
      },
    },
  ],
}))

const chartSeries = computed(() => [
  {
    name: $t('dashboard.seriesLabel'),
    data: userCounts.value,
  },
])
</script>

<template>
  <section class="max-w-[1200px] mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-bold">📈 {{ $t('dashboard.title') }}</h1>
      <div class="text-gray-500">{{ today }}</div>
    </div>

    <el-card v-loading="loading" shadow="hover">
      <template #header>
        <div class="flex justify-between items-center">
          <el-text>{{ $t('dashboard.summaryTitle') }}</el-text>
          <el-button size="small" @click="fetchSummary">
            {{ $t('dashboard.refreshButton') }}
          </el-button>
        </div>
      </template>

      <ApexChart
        width="100%"
        type="bar"
        :options="chartOptions"
        :series="chartSeries"
      />
    </el-card>
  </section>
</template>

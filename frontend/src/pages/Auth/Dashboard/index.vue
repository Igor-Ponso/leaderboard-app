<script lang="ts" setup>
import dayjs from 'dayjs'

const today = dayjs()

</script>

<template>
  {{ today.format('DD/MM/YYYY') }}
</template>

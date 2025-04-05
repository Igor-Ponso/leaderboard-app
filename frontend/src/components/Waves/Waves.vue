<script setup lang="ts">

const props = defineProps({
  waveCount: {
    type: Number,
    default: 5
  },
  waveColors: {
    type: Array as () => string[],
    default: () => [
      'rgba(33, 86, 179, 0.4)',
      'rgba(23, 66, 146, 0.5)',
      'rgba(14, 42, 102, 0.6)'
    ]
  },
  baseSpeed: {
    type: Number,
    default: 12
  },
  containerHeight: {
    type: Number,
    default: 100
  }
})

/**
 * 3 "tileable" paths,
 * for morph d attribute animation (morph).
 */
const wavePath1 = `
  M0,160
  C 80,80   160,240  240,160
  C 320,80  400,240  480,160
  C 560,80  640,240  720,160
  C 800,80  880,240  960,160
  C 1040,80 1120,240 1200,160
  C 1280,80 1360,240 1440,160
  L1440,320
  L0,320
  Z
`
const wavePath2 = `
  M0,160
  C 80,40   160,280  240,140
  C 320,200 400,80   480,160
  C 560,220 640,100  720,160
  C 800,50  880,240  960,150
  C 1040,100 1120,220 1200,160
  C 1280,40 1360,280 1440,160
  L1440,320
  L0,320
  Z
`
const wavePath3 = `
  M0,160
  C 80,100  160,260  240,180
  C 320,60  400,240  480,170
  C 560,200 640,140  720,160
  C 800,130 880,220  960,160
  C 1040,90 1120,180 1200,160
  C 1280,120 1360,220 1440,160
  L1440,320
  L0,320
  Z
`

/**
 * Gera dados p/ cada onda
 */
const wavesData = Array.from({ length: props.waveCount }).map((_, index) => {
  const color = props.waveColors[index % props.waveColors.length]

  // Durations
  const randX = Math.random() * 2 - 1
  const randY = Math.random() * 2 - 1
  const xDuration = Math.max(2, props.baseSpeed + randX + index)
  const yDuration = Math.max(2, props.baseSpeed + randY + index)

  // amplitude de Y (scale)
  const amplitudeY = 1 + Math.random() * 0.4 // 1..1.4
  // Delays
  const xDelay = -index
  const yDelay = -(index + Math.random())

  // Duração do morph
  const morphDuration = (props.baseSpeed + index) + 4

  return {
    id: index,
    color,
    xDuration,
    yDuration,
    amplitudeY,
    xDelay,
    yDelay,
    morphDuration
  }
})
</script>

<template>
  <div
    class="wave-container"
    :style="{ height: containerHeight + 'px' }"
  >
    <div
      v-for="wave in wavesData"
      :key="wave.id"
      class="wave-bounce"
      :style="{
        animationDuration: wave.yDuration + 's',
        animationDelay: wave.yDelay + 's',
        '--wave-amplitude': wave.amplitudeY
      }"
    >
      <svg
        class="wave-inner"
        viewBox="0 0 1440 320"
        preserveAspectRatio="none"
        :style="{
          animationDuration: wave.xDuration + 's',
          animationDelay: wave.xDelay + 's'
        }"
      >
        <path
          :fill="wave.color"
        >
          <animate
            attributeName="d"
            :dur="wave.morphDuration + 's'"
            repeatCount="indefinite"
            :values="wavePath1 + ';' + wavePath2 + ';' + wavePath3 + ';' + wavePath1"
          />
        </path>
      </svg>
    </div>
  </div>
</template>

<style scoped lang="stylus">
.wave-container
    bottom: 0;
    left: 0;
    width: 100%;
    height: 200px;
    overflow: hidden;
    z-index: 0;

.wave-bounce
  position absolute
  bottom 0
  left 0
  width 100%
  height 100%
  transform-origin bottom center
  animation waveYBounce ease-in-out infinite alternate

.wave-inner
  position absolute
  width 200%
  height 100%
  animation waveXMove ease-in-out infinite alternate

@keyframes waveXMove
  0%
    transform translateX(0)
  100%
    transform translateX(-50%)

@keyframes waveYBounce
  0%
    transform scaleY(1)
  50%
    transform scaleY(var(--wave-amplitude))
  100%
    transform scaleY(1)
</style>

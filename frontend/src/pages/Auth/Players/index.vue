<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { usePlayersStore } from '@/stores/players'
import PlayerModal from '@/components/Modals/PlayerModal.vue'
import { ElMessageBox } from 'element-plus'
import { Delete, Plus, Minus } from '@element-plus/icons-vue'
import type { Player } from '@/stores/players'
import { storeToRefs } from 'pinia'

const playerStore = usePlayersStore()
const { loading } = storeToRefs(playerStore)

const showModal = ref(false)
const modalMode = ref<'create' | 'edit' | 'view'>('create')
const selectedPlayer = ref<Player | null>(null)
const search = ref('')
const sortKey = ref<'name' | 'score'>('score')
const sortOrder = ref<'asc' | 'desc'>('desc')

const filteredPlayers = computed(() => {
  return playerStore.players.filter((p) =>
    p.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const sortedPlayers = computed(() => {
  return [...filteredPlayers.value].sort((a, b) => {
    const key = sortKey.value
    const valA = a[key]
    const valB = b[key]

    if (valA < valB) return sortOrder.value === 'asc' ? -1 : 1
    if (valA > valB) return sortOrder.value === 'asc' ? 1 : -1
    return 0
  })
})

const openCreateModal = () => {
  modalMode.value = 'create'
  selectedPlayer.value = null
  showModal.value = true
}

const openViewModal = (player: Player) => {
  modalMode.value = 'view'
  selectedPlayer.value = { ...player }
  showModal.value = true
}


const deletePlayer = async (player: Player) => {
  try {
    await ElMessageBox.confirm(
      `Are you sure you want to delete "${player.name}"?`,
      'Confirm Deletion',
      {
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }
    )
    await playerStore.deletePlayer(player.hash)
  } catch (_) {
    // canceled
  }
}

const increment = async (player: Player) => {
  await playerStore.updatePlayer(player.hash, { score: player.score + 1 })
}

const decrement = async (player: Player) => {
  if (player.score === 0) return
  await playerStore.updatePlayer(player.hash, { score: player.score - 1 })
}

const toggleSort = (key: 'name' | 'score') => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortOrder.value = 'asc'
  }
}

// Load players on mount
onMounted(() => {
  playerStore.fetchAll()
})

</script>

<template>
  <section class="max-w-[1200px] mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
      <el-input v-model="search" placeholder="Search by name" style="width: 200px" clearable />

      <el-button type="primary" @click="openCreateModal">+ Add User</el-button>
    </div>

    <el-table border :data="sortedPlayers" stripe highlight-current-row style="width: 100%" v-loading="loading">
      <el-table-column width="100" align="center">
        <template #default="{ row }">
          <el-button size="small" type="danger" :icon="Delete" @click="() => deletePlayer(row)" />
          
        </template>
      </el-table-column>

      <el-table-column prop="name" label="Name" sortable align="left" min-width="180">
        <template #default="{ row }">
          <el-button link type="primary" @click="openViewModal(row)">
            {{ row.name }}
          </el-button>
        </template>
      </el-table-column>

      <el-table-column label="Actions" header-align="center" align="center" width="160">
        <template #default="{ row }">
          <el-button size="small"  :icon="Plus" @click="() => increment(row)" />
          <el-button size="small"  :icon="Minus" @click="() => decrement(row)" />
        </template>
      </el-table-column>

      <el-table-column label="Score" header-align="center" align="center" prop="score" width="120" sortable @click="toggleSort('score')">
        <template #default="{ row }">
          <el-tag>{{ row.score }} points</el-tag>
        </template>
      </el-table-column>
    </el-table>

    <PlayerModal v-model="showModal" :mode="modalMode" :player-data="selectedPlayer"
      :on-refresh="playerStore.fetchAll" />
  </section>
</template>

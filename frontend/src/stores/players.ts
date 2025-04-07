import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from '@/api/config'
import { endpoints } from '@/api/paths'
import { useToast } from 'vue-toastification'

export interface Player {
  hash: string
  name: string
  birth_date: string
  score: number
  created_at?: string
  address: string
}

export const usePlayersStore = defineStore('players', () => {
  const players = ref<Player[]>([])
  const loading = ref(false)

  const fetchAll = async () => {
    loading.value = true
    try {
      const { data } = await api.get(endpoints.players.get)
      players.value = data
    } catch (err) {
      useToast().error('Failed to fetch players')
    } finally {
      loading.value = false
    }
  }

  const createPlayer = async (payload: Omit<Player, 'hash'>) => {
    try {
      const { data } = await api.post(endpoints.players.create, payload)
      players.value.push(data)
      useToast().success('Player created successfully')
      fetchAll() // Refresh the list after creating a player
      return data
    } catch (error: any) {
      useToast().error(
        error.response?.data?.message || 'Failed to create player',
      )
      throw error
    }
  }

  const updatePlayer = async (hash: string, payload: Partial<Player>) => {
    try {
      const { data } = await api.put(endpoints.players.update(hash), payload)
      const index = players.value.findIndex((p) => p.hash === hash)
      if (index !== -1) {
        players.value[index] = data
      }
      useToast().success('Player updated successfully')
      return data
    } catch (error: any) {
      useToast().error(
        error.response?.data?.message || 'Failed to update player',
      )
      throw error
    }
  }

  const deletePlayer = async (hash: string) => {
    try {
      await api.delete(endpoints.players.delete(hash))
      players.value = players.value.filter((p) => p.hash !== hash)
      useToast().success('Player deleted successfully')
    } catch (error: any) {
      useToast().error(
        error.response?.data?.message || 'Failed to delete player',
      )
      throw error
    }
  }

  const incrementScore = async (hash: string) => {
    const player = players.value.find(p => p.hash === hash)
    if (!player) return
  
    await updatePlayer(hash, { score: player.score + 1 })
  }
  
  const decrementScore = async (hash: string) => {
    const player = players.value.find(p => p.hash === hash)
    if (!player) return
  
    await updatePlayer(hash, { score: player.score - 1 })
  }
  

  return {
    players,
    loading,
    fetchAll,
    createPlayer,
    updatePlayer,
    deletePlayer,
    incrementScore,
    decrementScore,
  }
})

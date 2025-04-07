<script setup lang="ts">
import { ref, watch, reactive, computed } from 'vue'
import type { FormInstance } from 'element-plus'
import { usePlayersStore } from '@/stores/players'

const props = defineProps<{
  modelValue: boolean
  mode: 'create' | 'edit' | 'view'
  playerData?: {
    name: string
    birth_date: string
    score: number
    address: string
    hash: string
  } | null
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'refresh'): void
}>()

const formRef = ref<FormInstance>()
const playersStore = usePlayersStore()

const form = reactive({
  name: '',
  birth_date: '',
  address: '',
  score: 0,
})

const rules = {
  name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
  birth_date: [{ required: true, message: 'Birth date is required', trigger: 'change' }],
  address: [{ required: true, message: 'Address is required', trigger: 'change' }],
}

const isEdit = computed(() => props.mode === 'edit')
const isView = computed(() => props.mode === 'view')
const viewAsEdit = ref(false)

const resetForm = () => {
  form.name = ''
  form.birth_date = ''
  form.address = ''
  form.score = 0
}

watch(
  () => props.modelValue,
  (val) => {
    if (val && (isEdit.value || isView.value) && props.playerData) {
      Object.assign(form, props.playerData)
      viewAsEdit.value = false
    } else {
      resetForm()
    }
  }
)

const handleClose = () => {
  emit('update:modelValue', false)
}

const handleSubmit = async () => {
  await formRef.value?.validate(async (valid) => {
    if (!valid) return

    try {
      if ((isEdit.value || viewAsEdit.value) && props.playerData?.hash) {
        await playersStore.updatePlayer(props.playerData.hash, form)
      } else {
        await playersStore.createPlayer(form)
      }

      emit('refresh')
      handleClose()
    } catch (e) {
      // Handle error
    }
  })
}

const switchToEdit = () => {
  viewAsEdit.value = true
}
</script>

<template>
  <el-dialog
    :model-value="modelValue"
    @close="handleClose"
    :title="isView ? form.name : isEdit ? `Edit ${form.name}` : 'Create Player'"
    width="900px"
  >
    <template v-if="!isView || viewAsEdit">
      <el-form
        ref="formRef"
        :model="form"
        :rules="rules"
        label-position="top"
        autocomplete="off"
      >
        <el-form-item v-if="!isView || viewAsEdit" label="Name" prop="name">
          <el-input v-model="form.name" placeholder="Enter name" />
        </el-form-item>

        <el-form-item label="Birth Date" prop="birth_date">
          <el-date-picker
            v-model="form.birth_date"
            type="date"
            placeholder="Select date"
            style="width: 100%"
          />
        </el-form-item>

        <el-form-item label="Address" prop="address">
          <el-input v-model="form.address" placeholder="Enter address" />
        </el-form-item>
      </el-form>
    </template>

    <template v-else-if="!viewAsEdit">
      <el-table :data="[form]" border style="width: 100%">
        <el-table-column prop="birth_date" label="Birth Date" />
        <el-table-column prop="address" label="Address" />
        <el-table-column prop="score" label="Score">
          <template #default="{ row }">
            {{ row.score }} points
          </template>
        </el-table-column>
      </el-table>
    </template>

    <template #footer>
      <div class="flex justify-between items-center">
        <div v-if="isView && !viewAsEdit">
          <el-button type="primary" @click="switchToEdit">Edit</el-button>
        </div>

        <div class="flex justify-end gap-2 w-full">
          <el-button @click="handleClose">Close</el-button>
          <el-button
            v-if="!isView || viewAsEdit"
            type="primary"
            @click="handleSubmit"
          >
            {{ isEdit || viewAsEdit ? 'Update' : 'Create' }}
          </el-button>
        </div>
      </div>
    </template>
  </el-dialog>
</template>

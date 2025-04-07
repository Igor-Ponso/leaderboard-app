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
    address: {
      postal_code: string
      street: string
      city: string
      province: string
    }
    hash: string
    qr_code_url?: string
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
  score: 0,
  address: {
    postal_code: '',
    street: '',
    city: '',
    province: '',
  },
})

const rules = {
  name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
  birth_date: [
    { required: true, message: 'Birth date is required', trigger: 'change' },
  ],
  'address.postal_code': [
    { required: true, message: 'Postal code is required', trigger: 'blur' },
  ],
  'address.street': [
    { required: true, message: 'Street is required', trigger: 'blur' },
  ],
}

const isEdit = computed(() => props.mode === 'edit')
const isView = computed(() => props.mode === 'view')
const viewAsEdit = ref(false)

const resetForm = () => {
  form.name = ''
  form.birth_date = ''
  form.score = 0
  form.address = {
    postal_code: '',
    street: '',
    city: '',
    province: '',
  }
}

watch(
  () => props.modelValue,
  (val) => {
    if (val && (isEdit.value || isView.value) && props.playerData) {
      const player = { ...props.playerData }

      // Format birth_date to YYYY-MM-DD
      if (player.birth_date?.includes('/')) {
        const [day, month, year] = player.birth_date.split('/')
        player.birth_date = `${year}-${month}-${day}`
      }

      Object.assign(form, player)
      viewAsEdit.value = false
    } else {
      resetForm()
    }
  },
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
        await playersStore.createPlayer({
          ...form,
          ...form.address,
        })
      }

      emit('refresh')
      handleClose()
    } catch (e) {
      console.error('Failed to submit form', e)
    }
  })
}

const switchToEdit = () => {
  viewAsEdit.value = true
}

// Watch ZIP only if editing/creating
watch(
  () => form.address.postal_code,
  async (zip) => {
    if (isView.value && !viewAsEdit.value) return

    const cleaned = zip?.toUpperCase().replace(/\s/g, '')
    const fsa = cleaned?.slice(0, 3)

    const canadianPostalRegex =
      /^[A-Za-z]\d[A-Za-z]\d[A-Za-z]\d$|^[A-Za-z]\d[A-Za-z]$/
    if (!cleaned || !canadianPostalRegex.test(cleaned)) return

    try {
      const res = await fetch(`https://api.zippopotam.us/CA/${fsa}`)
      if (!res.ok) return

      const data = await res.json()
      const place = data.places[0]

      form.address.city = place['place name']
      form.address.province = place['state abbreviation']
    } catch (e) {
      console.error('Postal lookup failed:', e)
    }
  },
)

const onPostalCodeInput = (val: string) => {
  const cleaned = val.toUpperCase().replace(/[^A-Z0-9]/g, '')
  const formatted =
    cleaned.length > 3
      ? `${cleaned.slice(0, 3)} ${cleaned.slice(3, 6)}`
      : cleaned
  form.address.postal_code = formatted
}
</script>
<template>
  <el-dialog
    :model-value="modelValue"
    @close="handleClose"
    :title="isView ? form.name : isEdit ? `Edit ${form.name}` : 'Create Player'"
     width="90vw"
  class="max-w-[900px]"
  >
    <!-- CREATE / EDIT FORM -->
    <template v-if="!isView || viewAsEdit">
      <el-form
        ref="formRef"
        :model="form"
        :rules="rules"
        label-position="top"
        autocomplete="off"
      >
        <el-form-item label="Name" prop="name">
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

        <el-row :gutter="20">
          <el-col :xs="24" :md="5">
            <el-form-item label="Postal Code" prop="address.postal_code">
              <el-tooltip
                content="After entering the first 3 characters, city and province will be filled automatically"
                placement="top"
              >
                <el-input
                  v-model="form.address.postal_code"
                  placeholder="e.g. V6C 1A1"
                  maxlength="7"
                  @input="onPostalCodeInput"
                />
              </el-tooltip>
            </el-form-item>
          </el-col>

          <el-col :xs="24" :md="17">
            <el-form-item label="City">
              <el-input v-model="form.address.city" readonly />
            </el-form-item>
          </el-col>

          <el-col :xs="24" :md="2">
            <el-form-item label="Province">
              <el-input v-model="form.address.province" readonly />
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item label="Street" prop="address.street">
          <el-input
            v-model="form.address.street"
            placeholder="Enter street address"
          />
        </el-form-item>
      </el-form>
    </template>

    <!-- VIEW MODE -->
    <template v-else-if="!viewAsEdit">
      <el-table :data="[form]" border style="width: 100%">
        <el-table-column prop="birth_date" label="Birth Date" />
        <el-table-column prop="address.postal_code" label="Postal Code" />
        <el-table-column prop="address.street" label="Street" />
        <el-table-column prop="address.city" label="City" />
        <el-table-column prop="address.province" label="Province" />
        <el-table-column prop="score" label="Score">
          <template #default="{ row }"> {{ row.score }} points </template>
        </el-table-column>
      </el-table>

      <div v-if="props.playerData?.qr_code_url" class="mt-4 text-center">
        <img
          :src="props.playerData.qr_code_url"
          alt="QR Code"
          class="w-[200px] mx-auto"
        />
        <p class="text-xs text-gray-500 mt-2">QR Code to user address</p>
      </div>
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

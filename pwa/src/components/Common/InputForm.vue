<script setup lang="ts">
type PropsType = {
  label?: string
  modelValue: string
  type?: 'default'
  placeholder?: string
  errorMessage?: string
  required?: boolean
}

const props = withDefaults(defineProps<PropsType>(), {
  type: 'default',
  required: false
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

/**
 * Function to catch input event.
 * @param {Event} event
 */
function changeValue(event: Event) {
  emit('update:modelValue', (event.target as HTMLInputElement).value)
}
</script>

<template>
  <div class="input-atom">
    <label v-if="label" class="text-xs mb-1 block ml-1">
      <span class="text-text-error text-xl" v-if="required">*</span>
      {{ label }}
    </label>
    <input type="text" :value="modelValue" @input="changeValue" :disabled="type === 'disabled'"
      :class="['w-full p-4 font-normal text-text-input text-base border border-slate-200 rounded transition-all', {
        'cursor-not-allowed': type === 'disabled',
        'border-default-border outline-default-outline': type === 'default'
      }]" :placeholder="placeholder" />
    <span v-if="errorMessage" class="text-text-error text-xs block mt-1 ml-1">
      {{ errorMessage }}
    </span>
  </div>
</template>
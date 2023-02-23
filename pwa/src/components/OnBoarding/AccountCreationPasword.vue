<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const modelValue = ref<string>('')
</script>

<template>
  <div class="flex flex-col gap-y-6">
    <InputForm v-model="modelValue" :required="true"
      :placeholder="t('components.on-boarding.account-creation-password-form.input-placeholder')"
      :label="t('components.on-boarding.account-creation-password-form.input-label')" />
    <ButtonForm type="success" class="w-full">
      {{ t('components.on-boarding.account-creation-password-form.button') }}
    </ButtonForm>
  </div>
</template>
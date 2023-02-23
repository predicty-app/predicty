<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const modelValue = ref<string>('')
</script>

<template>
  <InputForm v-model="modelValue" :required="true"
    :placeholder="t('components.on-boarding.account-creation-email-form.input-placeholder')"
    :label="t('components.on-boarding.account-creation-email-form.input-label')" />
</template>
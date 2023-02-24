<script setup lang="ts">
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router';
import { useGlobalStore } from '@/stores/global'
import { useOnBoardingStore } from '@/stores/onboarding'
import { isRequiredValidation, isEmailValidation } from '@/helpers/rulesValidation'

const { t } = useI18n()
const router = useRouter()
const globalStore = useGlobalStore()
const onBoardingStore = useOnBoardingStore()

const modelValue = ref<string>('')
const errorMessage = ref<string | null>(null)

async function handleSubmitForm() {
  errorMessage.value = isRequiredValidation(modelValue.value)
  errorMessage.value = !errorMessage.value ? isEmailValidation(modelValue.value) : errorMessage.value

  if (!errorMessage.value) {
    globalStore.toogleSpinnerState()
    await onBoardingStore.handleSaveEmail(modelValue.value)
    globalStore.toogleSpinnerState()
    router.push('/onboarding/account-creation/password')
  }
}
</script>

<template>
  <InputForm v-model="modelValue" :error-message="errorMessage" v-on:keyup.enter="handleSubmitForm" :required="true"
    :placeholder="t('components.on-boarding.account-creation-email-form.input-placeholder')"
    :label="t('components.on-boarding.account-creation-email-form.input-label')" />
</template>
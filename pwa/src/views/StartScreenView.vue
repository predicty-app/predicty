<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router';

const { t } = useI18n()
const router = useRouter()
const nextStepPath = '/onboarding/account-creation'
</script>

<template> 
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <div class="flex justify-center">
        <IconSvg name="claim" class-name="" />
      </div>
      <HeaderText :header-title="t('views.start-screen.header-title')"
        :header-description="t('views.start-screen.header-description')" />
      <ButtonForm type="success" class="w-full" @click="router.push(nextStepPath)">
        {{ t('views.start-screen.button') }}
      </ButtonForm>
    </template>
  </OnBoardingLayout>
</template>
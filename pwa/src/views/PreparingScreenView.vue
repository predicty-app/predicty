<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <HeaderText :header-title="t('views.start-screen.header-title')"
        :header-description="t('views.start-screen.header-description')" />
    </template>
  </OnBoardingLayout>
</template>
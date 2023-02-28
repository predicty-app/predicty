<script setup lang="ts">
import { useI18n } from "vue-i18n";

const { t } = useI18n();
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <div class="text-center">
        <HeaderText
          :header-title="t('views.preparing-screen.header-title')"
          :header-description="t('views.preparing-screen.header-description')"
          class="mb-9"
        />
        <SpinnerBar :is-visible="true" />
      </div>
    </template>
  </OnBoardingLayout>
</template>

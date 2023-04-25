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
      <HeaderText
        :header-title="t('views.basic-media-integration.header-title')"
        :header-description="
          t('views.basic-media-integration.header-description')
        "
      />
      <ConnectAccountForm />
    </template>
    <template #footer>
      <div class="w-full max-w-[48px]"></div>
      <div class="flex items-center flex-auto"></div>
      <div class="w-full max-w-[387px]" id="next-button"></div>
    </template>
    <template #progress>
      <ProgressBar :count-steps="6" :active-step="4" />
    </template>
  </OnBoardingLayout>
</template>

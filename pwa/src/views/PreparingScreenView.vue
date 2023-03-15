<script setup lang="ts">
import { onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { useOnBoardingStore, FilesTypes } from "@/stores/onboarding";
import { handleUploadFile } from "@/services/api/onboarding";

const { t } = useI18n();
const onBoardingStore = useOnBoardingStore();

onMounted(async () => {
  if (onBoardingStore.file) {
    await handleUploadFile({
      file: onBoardingStore.file,
      type: FilesTypes.FACEBOOK
    });
  }
});
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

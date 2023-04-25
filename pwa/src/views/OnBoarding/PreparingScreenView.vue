<script setup lang="ts">
import { onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import type { FileType } from "@/stores/onboarding";
import { useOnBoardingStore } from "@/stores/onboarding";
import {
  handleUploadFile,
  handleCompleteOnboarding
} from "@/services/api/onboarding";

const { t } = useI18n();
const router = useRouter();
const onBoardingStore = useOnBoardingStore();

onMounted(async () => {
  if (onBoardingStore.moreServices.length > 0) {
    onBoardingStore.moreServices.forEach(async (service: FileType) => {
      await handleUploadFile({
        file: service.file,
        type: service.fileImportTypes,
        campaignName: service.name
      });
    });
    onBoardingStore.moreServices = [];
    router.push("/onboarding/preparing-screen/import-history");
  } else {
    await handleCompleteOnboarding();
    router.push("/");
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

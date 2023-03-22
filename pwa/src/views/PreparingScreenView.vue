<script setup lang="ts">
import { onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { handleUploadFile } from "@/services/api/onboarding";
import { useOnBoardingStore } from "@/stores/onboarding";

const { t } = useI18n();
const router = useRouter();
const onBoardingStore = useOnBoardingStore();

onMounted(async () => {
  if (onBoardingStore.file.file) {
    await handleUploadFile({
      file: onBoardingStore.file.file,
      type: onBoardingStore.file.type
    });
  }

  router.push("/");
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

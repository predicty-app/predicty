<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

const { t } = useI18n();
const router = useRouter();

const dashboardStepPath = "/";
const filesStepPath = "/onboarding/more-media-integration";
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <div class="text-center">
        <HeaderText
          :header-title="t('views.add-more-files.header-title')"
          :header-description="t('views.add-more-files.header-description')"
          class="mb-9"
        />
        <ButtonForm
          type="success"
          class="w-full"
          @click="router.push(filesStepPath)"
        >
          {{ t("views.add-more-files.buttons.more") }}
        </ButtonForm>
      </div>
    </template>
    <template #footer>
      <div class="w-full max-w-[48px]"></div>
      <div class="flex items-center flex-auto"></div>
      <div class="w-full max-w-[257px]">
        <ButtonForm
          type="success"
          class="w-full"
          @click="router.push(dashboardStepPath)"
        >
          {{ t("views.add-more-files.buttons.next") }}
        </ButtonForm>
      </div>
    </template>
  </OnBoardingLayout>
</template>

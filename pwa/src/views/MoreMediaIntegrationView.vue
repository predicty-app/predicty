<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

const { t } = useI18n();
const router = useRouter();
const nextStepPath = "/onboarding/preparing-screen";
const previousStepPath = "/onboarding/basic-media-integration";
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <HeaderText
        :header-title="t('views.more-media-integration.header-title')"
        :header-description="
          t('views.more-media-integration.header-description')
        "
      />
      <DividerLine />
      <ConnectMoreServicesForm />
    </template>
    <template #footer>
      <div class="w-full max-w-[48px]">
        <ButtonForm
          class="w-full flex justify-center"
          @click="router.push(previousStepPath)"
        >
          <IconSvg name="arrowback" class-name="h-3 w-3" />
        </ButtonForm>
      </div>
      <div class="flex items-center flex-auto">&nbsp;</div>
      <div class="w-full max-w-[267px]">
        <ButtonForm
          type="success"
          class="w-full"
          @click="router.push(nextStepPath)"
        >
          <div class="relative">
            {{ t("views.more-media-integration.button") }}
            <IconSvg
              name="arrownext"
              class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3"
            />
          </div>
        </ButtonForm>
      </div>
    </template>
  </OnBoardingLayout>
</template>

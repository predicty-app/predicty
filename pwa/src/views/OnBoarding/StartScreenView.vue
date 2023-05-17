<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

const { t } = useI18n();
const router = useRouter();
const authenticationStepPath = "/authentication";
const nextStepPath = "/onboarding/account-creation";
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
      <HeaderText
        :header-title="t('views.start-screen.header-title')"
        :header-description="t('views.start-screen.header-description')"
      />
      <ButtonForm
        type="success"
        class="w-full"
        @click="router.push(nextStepPath)"
      >
        {{ t("views.start-screen.button") }}
      </ButtonForm>
      <div
        class="text-base flex justify-center items-center gap-x-1 text-gray-1200"
      >
        {{ t("views.start-screen.login.description") }}
        <button
          class="font-bold cursor-pointer underline border-0 text-gray-1200 hover:text-green-400 transition-all"
          @click="router.push(authenticationStepPath)"
        >
          {{ t("views.start-screen.login.button") }}
        </button>
      </div>
    </template>
    <template #progress>
      <ProgressBar :count-steps="6" :active-step="1" />
    </template>
  </OnBoardingLayout>
</template>

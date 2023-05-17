<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

const { t } = useI18n();
const router = useRouter();
const previousStepPath = "/onboarding/start-screen";
const forgotStepPath = "/authentication/reset-password";
const signupStepPath = "/onboarding/account-creation/email";
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <HeaderText
        :header-title="t('views.authentication-login.header-title')"
        :header-description="t('views.authentication-login.header-description')"
      />
      <AuthenticationLoginForm />
      <div class="text-base flex items-center justify-between">
        <button
          class="font-bold cursor-pointer underline border-0 text-gray-1200 hover:text-green-400 transition-all"
          @click="router.push(forgotStepPath)"
        >
          {{ t("views.authentication-login.buttons.forgot") }}
        </button>
        <div class="flex justify-center items-center gap-x-1">
          {{ t("views.authentication-login.buttons.sign-up.description") }}
          <button
            class="font-bold cursor-pointer underline border-0 text-gray-1200 hover:text-green-400 transition-all"
            @click="router.push(signupStepPath)"
          >
            {{ t("views.authentication-login.buttons.sign-up.button") }}
          </button>
        </div>
      </div>
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
      <div class="flex items-center flex-auto"></div>
      <div class="w-full max-w-[267px]" id="next-button"></div>
    </template>
    <template #progress>
      <ProgressBar :count-steps="6" :active-step="2" />
    </template>
  </OnBoardingLayout>
</template>

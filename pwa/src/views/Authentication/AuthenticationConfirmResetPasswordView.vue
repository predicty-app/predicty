<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

const { t } = useI18n();
const router = useRouter();
const previousStepPath = "/authentication/login";
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <HeaderText
        :header-title="
          t('views.authentication-confirm-reset-password.header-title')
        "
        :header-description="
          t('views.authentication-confirm-reset-password.header-description')
        "
      />
      <AuthenticationConfirmResetPasswordForm />
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

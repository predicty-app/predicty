<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useOnBoardingStore } from "@/stores/onboarding";

const { t } = useI18n();
const router = useRouter();
const onBoardingStore = useOnBoardingStore();
const previousStepPath = "/onboarding/account-creation/email";
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <HeaderText
        :header-title="t('views.account-creation-pasword.header-title')"
        :header-description="
          t('views.account-creation-pasword.header-description', {
            email: onBoardingStore.email
          })
        "
      />
      <AccountCreationPasswordForm />
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
      <ProgressBar :count-steps="6" :active-step="3" />
    </template>
  </OnBoardingLayout>
</template>

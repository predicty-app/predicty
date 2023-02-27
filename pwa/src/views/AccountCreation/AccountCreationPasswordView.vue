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
            email: onBoardingStore.email,
          })
        "
      />
      <AccountCreationPasswordForm />
    </template>
    <template #footer>
      <div class="w-full max-w-[267px]">
        <ButtonForm class="w-full" @click="router.push(previousStepPath)">
          <div class="relative">
            <IconSvg
              name="arrowback"
              class-name="absolute left-5 top-0 bottom-0 m-auto h-3 w-3"
            />
            {{ t("views.account-creation-pasword.buttons.back") }}
          </div>
        </ButtonForm>
      </div>
      <div class="flex items-center flex-auto">
        <ProgressBar :count-steps="3" :active-step="2" />
      </div>
      <div class="w-full max-w-[267px]" id="next-button"></div>
    </template>
  </OnBoardingLayout>
</template>

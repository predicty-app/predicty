<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <HeaderText :header-title="t('views.account-creation-email.header-title')"
        :header-description="t('views.account-creation-email.header-description')" />
      <AccountCreationEmailForm />
    </template>
    <template #footer>
      <div class="w-full max-w-[267px]"></div>
      <div class="flex items-center flex-auto">
        <ProgressBar :count-steps="3" :active-step="1"/>
      </div>
      <div class="w-full max-w-[267px]">
        <ButtonForm type="success" class="w-full">
          {{ t('views.account-creation-email.button') }}
        </ButtonForm>
      </div>
    </template>
  </OnBoardingLayout>
</template>
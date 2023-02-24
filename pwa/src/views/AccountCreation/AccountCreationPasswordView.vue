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
      <HeaderText :header-title="t('views.account-creation-pasword.header-title')"
        :header-description="t('views.account-creation-pasword.header-description')" />
      <AccountCreationPassword />
    </template>
    <template #footer>
      <div class="w-full max-w-[267px]">
        <ButtonForm class="w-full">
          <div class="relative">
            <IconSvg name="arrowback" class-name="absolute left-5 top-0 bottom-0 m-auto h-3 w-3" />
            {{ t('views.account-creation-pasword.buttons.back') }}
          </div>
        </ButtonForm>
      </div>
      <div class="flex items-center flex-auto">
        <ProgressBar :count-steps="3" :active-step="1" />
      </div>
      <div class="w-full max-w-[267px]">
        <ButtonForm type="success" class="w-full">
          <div class="relative">
            {{ t('views.account-creation-pasword.buttons.next') }}
            <IconSvg name="arrownext" class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3" />
          </div>
        </ButtonForm>
      </div>
    </template>
  </OnBoardingLayout>
</template>
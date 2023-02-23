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
      <HeaderText :header-title="t('views.basic-media-integration.header-title')"
        :header-description="t('views.basic-media-integration.header-description')" />
      <ConnectAccountForm />
    </template>
    <template #footer>
      <div class="w-full max-w-[48px]">
        <ButtonForm class="w-full flex justify-center">
          <IconSvg name="arrowback" class-name="h-3 w-3" />
        </ButtonForm>
      </div>
      <div class="flex items-center flex-auto">
        &nbsp;
      </div>
      <div class="w-full max-w-[267px]">
        <ButtonForm type="success" class="w-full">
          <div class="relative">
            {{ t('views.basic-media-integration.button') }}
            <IconSvg name="arrownext" class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3" />
          </div>
        </ButtonForm>
      </div>
    </template>
  </OnBoardingLayout>
</template>
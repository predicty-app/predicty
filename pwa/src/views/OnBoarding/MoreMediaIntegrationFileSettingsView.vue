<template>
  <OnBoardingLayout>
    <template #header>
      <AppLogo />
    </template>
    <template #content>
      <ConnectMoreServicesFileSettingsForm type="extended"/>
    </template>
    <template #footer>
      <div class="w-full max-w-[48px]" id="previous-button"></div>
      <div class="flex items-center flex-auto"></div>
      <div class="w-full max-w-[267px]" id="next-button"></div>
    </template>
    <template #progress>
      <ProgressBar :count-steps="6" :active-step="5" />
    </template>
  </OnBoardingLayout>
</template>

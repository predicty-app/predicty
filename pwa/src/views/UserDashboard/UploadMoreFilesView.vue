<script setup lang="ts">
import { useRouter } from "vue-router";

const router = useRouter();
const previousStepPath = "/";
</script>

<template>
  <SupportingLayout>
    <template #content>
      <ConnectMoreServicesFileSettingsForm />
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
  </SupportingLayout>
</template>

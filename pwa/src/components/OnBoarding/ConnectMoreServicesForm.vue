<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useOnBoardingStore } from "@/stores/onboarding";
import { ref, watch, onMounted, nextTick, computed } from "vue";

const { t } = useI18n();

const router = useRouter();
const allowedFiles = [".csv", ".xls"];
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);
const fileInstance = ref<File | string | null>(null);
const nextStepPath = computed<string>(() =>
  onBoardingStore.file.file
    ? "/onboarding/more-media-integration/file-settings"
    : "/onboarding/preparing-screen"
);

const isNextButtonDisabled = computed<boolean>(() => {
  if (Object.keys(onBoardingStore.providers).length > 0) {
    return true;
  }

  return !onBoardingStore.file.file ? true : false;
});
onMounted(() => nextTick(() => (isComponentMounted.value = true)));

watch(fileInstance, () => {
  onBoardingStore.handleSaveFile(fileInstance.value as File);
});
</script>

<template>
  <div v-if="isComponentMounted">
    <UploadFile v-model="fileInstance" :files-type="allowedFiles" />
    <Teleport to="#next-button">
      <ButtonForm
        :type="isNextButtonDisabled ? 'disabled' : 'success'"
        class="w-full"
        @click="router.push(nextStepPath)"
      >
        <div class="relative">
          {{
            t("components.on-boarding.connect-more-services-form.button.next")
          }}
          <IconSvg
            name="arrownext"
            class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3"
          />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

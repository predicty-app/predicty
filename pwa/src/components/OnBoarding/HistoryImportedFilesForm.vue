<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useOnBoardingStore } from "@/stores/onboarding";
import { ref, onMounted, nextTick, computed } from "vue";
import {
  handleGetImportedFiles,
  handleCompleteOnboarding
} from "@/services/api/onboarding";

const { t } = useI18n();

const router = useRouter();
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);

onMounted(() => nextTick(() => (isComponentMounted.value = true)));

/**
 * Function to submit finish setup onboarding.
 */
async function handleFinishSetup() {
  await handleCompleteOnboarding();
  router.push('/')
}
</script>
<template>
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <Teleport to="#next-button">
      <ButtonForm
        type="success"
        class="w-full"
      >
        <div class="relative">
          {{
            t("components.on-boarding.history-imported-files-form.button.next")
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
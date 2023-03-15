<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useOnBoardingStore } from "@/stores/onboarding";
import {
  isRequiredValidation,
  isPasscodeCorrectValidation
} from "@/helpers/rulesValidation";
import { handleLoginUser } from "@/services/api/onboarding";

const { t } = useI18n();
const router = useRouter();
const globalStore = useGlobalStore();
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);

const modelValue = ref<string>("");
const errorMessage = ref<string | null>(null);

onMounted(() => nextTick(() => (isComponentMounted.value = true)));

/**
 * Function to handle submit form.
 */
async function handleSubmitForm() {
  errorMessage.value = isRequiredValidation(modelValue.value, t);
  errorMessage.value = !errorMessage.value
    ? isPasscodeCorrectValidation(
        parseInt(modelValue.value.replace("-", ""), 10),
        t
      )
    : errorMessage.value;

  if (!errorMessage.value) {
    globalStore.toogleSpinnerState();
    const response = await handleLoginUser({
      username: onBoardingStore.email,
      passcode: modelValue.value.replace("-", "")
    });

    if (response !== "OK") {
      setErrorFormResponse(response);
      return;
    }

    await onBoardingStore.handleSavePassword(modelValue.value);
    globalStore.toogleSpinnerState();
    router.push("/onboarding/basic-media-integration");
  }
}

/**
 * Function to set error from response.
 * @param {string} response
 */
function setErrorFormResponse(response: string) {
  errorMessage.value = response;
  onBoardingStore.password = null;
  globalStore.toogleSpinnerState();
}
</script>

<template>
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <CodeForm
      v-model="modelValue"
      :error-message="errorMessage"
      :required="true"
      :label="
        t('components.on-boarding.account-creation-password-form.input-label')
      "
    />
    <Teleport to="#next-button">
      <ButtonForm
        :type="modelValue.length === 6 ? 'success' : 'disabled'"
        class="w-full"
        @click="handleSubmitForm"
      >
        <div class="relative">
          {{
            t(
              "components.on-boarding.account-creation-password-form.buttons.next"
            )
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

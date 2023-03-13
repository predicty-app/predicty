<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
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

onMounted(() => nextTick(() => (isComponentMounted.value = true)));
</script>

<template>
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <InputForm
      v-model="modelValue"
      mask="###-###"
      :error-message="errorMessage"
      v-on:keyup.enter="handleSubmitForm"
      :required="true"
      :placeholder="
        t(
          'components.on-boarding.account-creation-password-form.input-placeholder'
        )
      "
      :label="
        t('components.on-boarding.account-creation-password-form.input-label')
      "
    />
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full" @click="handleSubmitForm">
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

<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { useGlobalStore } from "@/stores/global";
import { useOnBoardingStore } from "@/stores/onboarding";
import { isRequiredValidation } from "@/helpers/rulesValidation";

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

  if (!errorMessage.value) {
    globalStore.toogleSpinnerState();
    const isValid = await onBoardingStore.handleSavePassword(modelValue.value);
    globalStore.toogleSpinnerState();
    if (isValid) {
      router.push("/onboarding/basic-media-integration");
    } else {
      errorMessage.value = t(
        "components.common.account-creation-password-form.is-correct-code"
      );
    }
  }
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

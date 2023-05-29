<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useOnBoardingStore } from "@/stores/onboarding";
import { handleRegisterUser } from "@/services/api/onboarding";
import {
  isRequiredValidation,
  isEmailValidation
} from "@/helpers/rulesValidation";
import type { TermsType } from '@/stores/onboarding';
import type { CheckboxForm } from "@/stories/components/Common/CheckboxForm.stories";

const { t } = useI18n();
const router = useRouter();
const globalStore = useGlobalStore();
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);

const modelValue = ref<string | null>(onBoardingStore.email || "");
const errorMessage = ref<string | null>(null);
const termsModel = ref<TermsType>(onBoardingStore.terms);
const termsError = ref<string | null>(null);

/**
 * Function to handle submit form.
 */
async function handleSubmitForm() {
  if (modelValue.value === onBoardingStore.email) {
    router.push("/onboarding/basic-media-integration");
    return;
  }

  errorMessage.value = isRequiredValidation(modelValue.value as string, t);
  errorMessage.value = !errorMessage.value
    ? isEmailValidation(modelValue.value as string, t)
    : errorMessage.value;

  termsError.value = termsModel.value.fileVersionId === 0 ? 'This field is a required field.' : null;

  if (!errorMessage.value && !termsError.value) {
    globalStore.toogleSpinnerState();
    const response = await handleRegisterUser({
      email: modelValue.value,
      acceptedTermsOfServiceVersion: termsModel.value.fileVersionId,
      hasAgreedToNewsletter: termsModel.value.newsletter
    });

    if (response !== "OK") {
      setErrorFormResponse(response);
      return;
    }
    await onBoardingStore.handleSaveEmail(modelValue.value as string);
    onBoardingStore.password = null;
    globalStore.toogleSpinnerState();
    router.push("/onboarding/account-creation/password");
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

/**
 * Function to change state of terms.
 * @param {'fileVersionId' | 'newsletter'} type 
 * @param {boolean} stateValue 
 */
function handleChangeStateCheckbox(type: 'fileVersionId' | 'newsletter', stateValue: boolean) {
  if (type === 'fileVersionId') {
    termsModel.value.fileVersionId = stateValue ? globalStore.currentTermsOfServiceVersion : 0;
  } else {
    termsModel.value.newsletter = stateValue
  }
}

onMounted(() => nextTick(() => (isComponentMounted.value = true)));
</script>

<template>
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <InputForm v-model="modelValue" :error-message="errorMessage" v-on:keyup.enter="handleSubmitForm" :required="true"
      :placeholder="t(
        'components.on-boarding.account-creation-email-form.input-placeholder'
      )
        " :label="t('components.on-boarding.account-creation-email-form.input-label')
    " />
    <div class="flex gap-x-2 text-xs">
      <CheckboxForm :is-checked="termsModel.fileVersionId > 0" color="#272727" border-color="#272727"
        @onChange="(value) => handleChangeStateCheckbox('fileVersionId', value)" />
      <span class="cursor-pointer" @click="handleChangeStateCheckbox('fileVersionId', !(termsModel.fileVersionId > 0))"> I
        agree with <span class=" font-bold"> Terms and conditionsI</span></span>
      <div v-if="termsError" class="text-red-100 text-xs">{{ termsError }}</div>
    </div>
    <div class="flex gap-x-2 text-xs">
      <CheckboxForm :is-checked="termsModel.newsletter" color="#272727" border-color="#272727"
        @onChange="(value) => handleChangeStateCheckbox('newsletter', value)" />
      <span class="cursor-pointer" @click="handleChangeStateCheckbox('newsletter', !termsModel.newsletter)"> I want to
        receive new features, thus agree to
        <span class=" font-bold">Rules of receiving communication by email</span></span>
    </div>
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full" @click="handleSubmitForm">
        <div class="relative">
          {{ t("components.on-boarding.account-creation-email-form.button") }}
          <IconSvg name="arrownext" class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3" />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

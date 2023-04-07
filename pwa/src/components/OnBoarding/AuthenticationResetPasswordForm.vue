<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useOnBoardingStore } from "@/stores/onboarding";
import { handleResetPassword } from "@/services/api/onboarding";
import {
  isRequiredValidation,
  isEmailValidation
} from "@/helpers/rulesValidation";

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const router = useRouter();
const globalStore = useGlobalStore();
const isComponentMounted = ref<boolean>(false);

const modelValue = ref<string | null>("");
const errorMessage = ref<string | null>(null);

const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});

/**
 * Function to handle submit form.
 */
async function handleSubmitForm() {
  errorMessage.value = isRequiredValidation(modelValue.value as string, t);
  errorMessage.value = !errorMessage.value
    ? isEmailValidation(modelValue.value as string, t)
    : errorMessage.value;

  if (!errorMessage.value) {
    globalStore.toogleSpinnerState();
    const response = await handleResetPassword({ email: modelValue.value });

    if (response !== "OK") {
      setErrorFormResponse(response);
      return;
    }

    notificationMessageModel.value.visible = true;
    notificationMessageModel.value.message = t('components.on-boarding.authentication-reset-password-form.success');
    notificationMessageModel.value.type = 'success';

    setTimeout(() => {
      globalStore.toogleSpinnerState();
      router.push("/onboarding/start-screen");
    }, 2000);
  }
}

/**
 * Function to set error from response.
 * @param {string} response
 */
function setErrorFormResponse(response: string) {
  errorMessage.value = response;
  globalStore.toogleSpinnerState();
}

onMounted(() => nextTick(() => (isComponentMounted.value = true)));
</script>

<template>
  <NotificationMessage v-model="notificationMessageModel.visible" :message="notificationMessageModel.message"
    :type="notificationMessageModel.type" />
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <InputForm v-model="modelValue" :error-message="errorMessage" v-on:keyup.enter="handleSubmitForm" :required="true"
      :placeholder="
        t(
          'components.on-boarding.authentication-reset-password-form.input-placeholder'
        )
      " :label="
  t('components.on-boarding.authentication-reset-password-form.input-label')
" />
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full" @click="handleSubmitForm">
        <div class="relative">
          {{ t("components.on-boarding.authentication-reset-password-form.button") }}
          <IconSvg name="arrownext" class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3" />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

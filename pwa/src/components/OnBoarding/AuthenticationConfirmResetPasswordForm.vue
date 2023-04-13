<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter, useRoute } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
import { useGlobalStore } from "@/stores/global";
import { handleConfirmResetPassword } from "@/services/api/onboarding";
import {
  isRequiredValidation,
  isPasswordValidation
} from "@/helpers/rulesValidation";


type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const router = useRouter();
const route = useRoute();
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
    ? isPasswordValidation(modelValue.value as string)
    : errorMessage.value;

  if (!errorMessage.value) {
    globalStore.toogleSpinnerState();
    const response = await handleConfirmResetPassword({ password: modelValue.value, token: route.params.token as string });

    if (response !== "OK") {
      setErrorFormResponse(response);
      return;
    }

    notificationMessageModel.value.visible = true;
    notificationMessageModel.value.message = t('components.on-boarding.authentication-confirm-reset-password-form.success');
    notificationMessageModel.value.type = 'success';

    setTimeout(() => {
      globalStore.toogleSpinnerState();
      router.push("/onboarding/authentication/login");
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
    <PasswordForm v-model="modelValue" :error-message="errorMessage" v-on:keyup.enter="handleSubmitForm" :required="true"
      :placeholder="
        t(
          'components.on-boarding.authentication-confirm-reset-password-form.input-placeholder'
        )
      " :label="
  t('components.on-boarding.authentication-confirm-reset-password-form.input-label')
" />
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full" @click="handleSubmitForm">
        <div class="relative">
          {{ t("components.on-boarding.authentication-confirm-reset-password-form.button") }}
          <IconSvg name="arrownext" class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3" />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

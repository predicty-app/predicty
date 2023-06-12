<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { handleAuthLoginUser } from "@/services/api/onboarding";
import { handleAuthenticatedUser } from "@/services/api/authentication";
import {
  isRequiredValidation,
  isEmailValidation
} from "@/helpers/rulesValidation";

type ModelValueType = {
  email: string | null;
  password: string | null;
};

type ErrorMessagesType = {
  email: string | null;
  password: string | null;
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});

const { t } = useI18n();
const router = useRouter();
const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();
const isComponentMounted = ref<boolean>(false);

const modelValue = ref<ModelValueType>({
  email: "",
  password: ""
});
const errorMessage = ref<ErrorMessagesType>({
  email: null,
  password: null
});

/**
 * Function to handle submit form.
 */
async function handleSubmitForm() {
  errorMessage.value.email = isRequiredValidation(
    modelValue.value.email as string,
    t
  );
  errorMessage.value.email = !errorMessage.value.email
    ? isEmailValidation(modelValue.value.email as string, t)
    : errorMessage.value.email;

  errorMessage.value.password = isRequiredValidation(
    modelValue.value.password as string,
    t
  );

  if (!errorMessage.value.email && !errorMessage.value.password) {
    globalStore.toogleSpinnerState();
    const response = await handleAuthLoginUser({
      username: modelValue.value.email,
      password: modelValue.value.password
    });

    if (response !== "OK") {
      setErrorFormResponse(response);
      return;
    }

    notificationMessageModel.value.visible = true;
    notificationMessageModel.value.message = t(
      "components.on-boarding.authentication-login-form.success"
    );
    notificationMessageModel.value.type = "success";
    await handleAuthenticatedUser();

    setTimeout(() => {
      globalStore.toogleSpinnerState();
      router.push(
        userDashboardStore.authenticatedUserParams.isOnboardingComplete
          ? "/"
          : "/onboarding/basic-media-integration"
      );
    }, 2000);
  }
}

/**
 * Function to set error from response.
 * @param {string} response
 */
function setErrorFormResponse(response: string) {
  notificationMessageModel.value.visible = true;
  notificationMessageModel.value.message = response;
  notificationMessageModel.value.type = "error";
  globalStore.toogleSpinnerState();
}

onMounted(() => nextTick(() => (isComponentMounted.value = true)));
</script>

<template>
  <NotificationMessage
    v-model="notificationMessageModel.visible"
    :message="notificationMessageModel.message"
    :type="notificationMessageModel.type"
  />
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <InputForm
      v-model="modelValue.email"
      :error-message="errorMessage.email"
      v-on:keyup.enter="handleSubmitForm"
      :required="true"
      :placeholder="
        t(
          'components.on-boarding.authentication-login-form.inputs.email.placeholder'
        )
      "
      :label="
        t('components.on-boarding.authentication-login-form.inputs.email.label')
      "
    />
    <PasswordForm
      v-model="modelValue.password"
      :error-message="errorMessage.password"
      v-on:keyup.enter="handleSubmitForm"
      :required="true"
      :placeholder="
        t(
          'components.on-boarding.authentication-login-form.inputs.password.placeholder'
        )
      "
      :label="
        t(
          'components.on-boarding.authentication-login-form.inputs.password.label'
        )
      "
    />
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full" @click="handleSubmitForm">
        <div class="relative">
          {{ t("components.on-boarding.authentication-login-form.button") }}
          <IconSvg
            name="arrownext"
            class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3"
          />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

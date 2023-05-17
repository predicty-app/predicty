<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick, computed } from "vue";
import {
  handleGetProvidersList,
  type ProviderType
} from "@/services/api/providers";
import { useOnBoardingStore, AvalibleProviders } from "@/stores/onboarding";

enum TypeOfList {
  BASIC = "basic",
  EXTENDED = "extended"
}

enum StepValues {
  CHOOSE_TYPE_FILE = "choose_type_file",
  UPLOAD_FILE = "upload_file"
}

type ProvidersListType = {
  name?: string;
  key: string;
  label: string;
  fileImportTypes: string[];
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();

type PropsType = {
  type?: TypeOfList;
};
const router = useRouter();
const props = withDefaults(defineProps<PropsType>(), {
  type: "basic" as TypeOfList
});
const displayedName = ref<string>("");
const selectedProvider = ref<string>("");
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);
const currentStep = ref<StepValues>(StepValues.CHOOSE_TYPE_FILE);
const uploadedFileModel = ref<File | string | null>(null);
const headerTitle = computed<string>(() =>
  currentStep.value === StepValues.CHOOSE_TYPE_FILE
    ? t(
        "components.shared.connect-more-services-file-settings-form.header-title"
      )
    : selectedProvider.value === AvalibleProviders.OTHER
    ? t(
        "components.shared.connect-more-services-file-settings-form.header-title-dynamic-custom",
        {
          name: displayedName.value
        }
      )
    : t(
        "components.shared.connect-more-services-file-settings-form.header-title-dynamic",
        {
          provider: t(
            `components.shared.connect-more-services-file-settings-form.data-type.options.${selectedProvider.value}`
          )
        }
      )
);

const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});

const headerDescription = computed<string>(() =>
  currentStep.value === StepValues.CHOOSE_TYPE_FILE
    ? t(
        "components.shared.connect-more-services-file-settings-form.header-description"
      )
    : ""
);

const isNextButtonDisabled = computed<boolean>(() => {
  if (!selectedProvider.value) {
    return true;
  }

  if (
    selectedProvider.value === AvalibleProviders.OTHER &&
    !displayedName.value
  ) {
    return true;
  }

  return false;
});

const columnsList = {
  "campaign-id": t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.campaign-id"
  ),
  "adset-id": t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.adset-id"
  ),
  "ad-id": t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.ad-id"
  ),
  "ad-name": t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.ad-name"
  ),
  "image-hash": t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.image-hash"
  ),
  spent: t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.spent"
  ),
  currency: t(
    "components.shared.connect-more-services-file-settings-form.descriptions.columns-names.currency"
  )
};

const columnsProvider = {
  [AvalibleProviders.FACEBOOK_ADS]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent",
    "currency"
  ],
  [AvalibleProviders.GOOGLE_ADS]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent",
    "currency"
  ],
  [AvalibleProviders.GOOGLE_ANALYTICS]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent",
    "currency"
  ],
  [AvalibleProviders.TIK_TOK]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent",
    "currency"
  ],
  [AvalibleProviders.OTHER]: ["ad-name", "spent", "currency"]
};

const providersList = ref<ProvidersListType[]>([]);
const typeFiles = ["csv", "xls"];

onMounted(async () => {
  const response = await handleGetProvidersList();
  if (response) {
    providersList.value = response.map((provider: ProviderType) => ({
      key: provider.id,
      label: t(
        `components.shared.connect-more-services-file-settings-form.data-type.options.${provider.id}`
      ),
      name: provider.name,
      fileImportTypes: provider.fileImportTypes
    }));
  }
  nextTick(() => (isComponentMounted.value = true));
});

/**
 * Function to handle submit form.
 */
function handleSubmitForm() {
  if (isNextButtonDisabled.value) {
    return;
  }

  switch (currentStep.value) {
    case StepValues.CHOOSE_TYPE_FILE:
      {
        currentStep.value = StepValues.UPLOAD_FILE;
      }
      break;
    case StepValues.UPLOAD_FILE:
      {
        const provider = providersList.value.find(
          (provider: ProvidersListType) =>
            provider.key === selectedProvider.value
        );
        onBoardingStore.handleSaveCustomService({
          file: uploadedFileModel.value as File,
          fileImportTypes: provider.fileImportTypes[0],
          name:
            selectedProvider.value === AvalibleProviders.OTHER
              ? displayedName.value
              : selectedProvider.value,
          type: selectedProvider.value
        });
        notificationMessageModel.value.visible = true;
        notificationMessageModel.value.type = "success";
        notificationMessageModel.value.message = t(
          "components.shared.connect-more-services-file-settings-form.notifications.success"
        );

        nextTick(() => {
          router.push(
            props.type === TypeOfList.EXTENDED
              ? "/onboarding/more-media-integration"
              : "/dashboard/import-history"
          );
        });
      }
      break;
  }
}

/**
 * Handle action previous step.
 */
function handlePreviousStepAction() {
  switch (currentStep.value) {
    case StepValues.CHOOSE_TYPE_FILE:
      {
        router.push("/onboarding/more-media-integration");
      }
      break;
    case StepValues.UPLOAD_FILE:
      {
        currentStep.value = StepValues.CHOOSE_TYPE_FILE;
      }
      break;
  }
}
</script>

<template>
  <NotificationMessage
    v-model="notificationMessageModel.visible"
    :message="notificationMessageModel.message"
    :type="notificationMessageModel.type"
  />
  <div
    class="connect-more-services-file-settings flex flex-col gap-y-6"
    v-if="isComponentMounted"
  >
    <HeaderText
      :header-title="headerTitle"
      :header-description="headerDescription"
    />
    <SelectForm
      v-if="currentStep === StepValues.CHOOSE_TYPE_FILE"
      v-model="selectedProvider"
      :options="providersList"
      class="animate-fade-in"
      :placeholder="
        t(
          'components.shared.connect-more-services-file-settings-form.data-type.placeholder'
        )
      "
      :label="
        t(
          'components.shared.connect-more-services-file-settings-form.data-type.label'
        )
      "
    />
    <InputForm
      v-if="
        selectedProvider === AvalibleProviders.OTHER &&
        currentStep === StepValues.CHOOSE_TYPE_FILE
      "
      v-model="displayedName"
      class="animate-fade-in"
      :label="
        t(
          'components.shared.connect-more-services-file-settings-form.display-name.label'
        )
      "
      :placeholder="
        t(
          'components.shared.connect-more-services-file-settings-form.display-name.placeholder'
        )
      "
    />

    <div
      v-if="currentStep === StepValues.UPLOAD_FILE"
      class="animate-fade-in flex flex-col gap-y-6 text-base  text-gray-1200"
    >
      <p
        v-html="
          t(
            `components.shared.connect-more-services-file-settings-form.descriptions.header`
          )
        "
      ></p>
      <ul class="list-disc pl-4 flex flex-col gap-y-1">
        <li
          :key="`${column}_${Math.random()}`"
          v-for="column in columnsProvider[selectedProvider]"
        >
          {{ columnsList[column] }}
        </li>
      </ul>
    </div>
    <DividerLine v-if="currentStep === StepValues.UPLOAD_FILE" />
    <UploadFile
      v-if="currentStep === StepValues.UPLOAD_FILE"
      :files-type="typeFiles"
      :can-remove="false"
      v-model="uploadedFileModel"
    />
    <Teleport to="#previous-button" v-if="type === TypeOfList.EXTENDED">
      <ButtonForm
        class="w-full flex justify-center"
        @click="handlePreviousStepAction"
      >
        <IconSvg name="arrowback" class-name="h-3 w-3" />
      </ButtonForm>
    </Teleport>
    <Teleport to="#next-button">
      <ButtonForm
        :type="isNextButtonDisabled ? 'disabled' : 'success'"
        class="w-full"
        @click="handleSubmitForm"
      >
        <div class="relative">
          {{
            t(
              "components.shared.connect-more-services-file-settings-form.button.next"
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

<style scoped lang="scss">
.connect-more-services-file-settings {
  :deep(.select-form-content) {
    @apply p-4 text-base rounded-[10px] border-default-outline;
  }
}
</style>

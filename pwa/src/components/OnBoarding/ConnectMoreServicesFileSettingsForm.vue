<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick, watch, computed } from "vue";
import {
  handleGetProvidersList,
  type ProviderType
} from "@/services/api/providers";
import { useOnBoardingStore, AvalibleProviders } from "@/stores/onboarding";

const { t } = useI18n();

const router = useRouter();
const displayedName = ref<string>("");
const selectedProvider = ref<string>("");
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);
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

type ProvidersListType = {
  key: string;
  label: string;
  fileImportTypes: string[];
};

const columnsList = {
  "campaign-id": t(
    "components.on-boarding.connect-more-services-file-settings-form.descriptions.columns-names.campaign-id"
  ),
  "adset-id": t(
    "components.on-boarding.connect-more-services-file-settings-form.descriptions.columns-names.adset-id"
  ),
  "ad-id": t(
    "components.on-boarding.connect-more-services-file-settings-form.descriptions.columns-names.ad-id"
  ),
  "ad-name": t(
    "components.on-boarding.connect-more-services-file-settings-form.descriptions.columns-names.ad-name"
  ),
  "image-hash": t(
    "components.on-boarding.connect-more-services-file-settings-form.descriptions.columns-names.image-hash"
  ),
  spent: t(
    "components.on-boarding.connect-more-services-file-settings-form.descriptions.columns-names.spent"
  )
};

const columnsProvider = {
  [AvalibleProviders.FACEBOOK_ADS]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent"
  ],
  [AvalibleProviders.GOOGLE_ADS]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent"
  ],
  [AvalibleProviders.GOOGLE_ANALYTICS]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent"
  ],
  [AvalibleProviders.TIK_TOK]: [
    "campaign-id",
    "adset-id",
    "ad-id",
    "ad-name",
    "image-hash",
    "spent"
  ],
  [AvalibleProviders.OTHER]: ["ad-name", "spent"]
};

const dictionaryFileTypes = {
  "text/csv": "_CSV"
};
const providersList = ref<ProvidersListType[]>([]);

onMounted(async () => {
  const response = await handleGetProvidersList();
  if (response) {
    providersList.value = response.map((provider: ProviderType) => ({
      key: provider.id,
      label: t(
        `components.on-boarding.connect-more-services-file-settings-form.data-type.options.${provider.id}`
      ),
      fileImportTypes: provider.fileImportTypes
    }));
  }
  nextTick(() => (isComponentMounted.value = true));
});

watch(selectedProvider, () => {
  const provider = providersList.value.find(
    (provider: ProvidersListType) => provider.key === selectedProvider.value
  );
  onBoardingStore.file.type = provider.fileImportTypes[0];

  if (onBoardingStore.file.type === AvalibleProviders.OTHER) {
    displayedName.value = "";
  }
});

watch(displayedName, () => {
  onBoardingStore.file.name = displayedName.value;
});

/**
 * Function to handle submit form.
 */
function handleSubmitForm() {
  if (isNextButtonDisabled.value) {
    return;
  }

  router.push("/onboarding/preparing-screen");
}
</script>

<template>
  <div
    class="connect-more-services-file-settings flex flex-col gap-y-6"
    v-if="isComponentMounted"
  >
    <SelectForm
      v-model="selectedProvider"
      :options="providersList"
      :placeholder="
        t(
          'components.on-boarding.connect-more-services-file-settings-form.data-type.placeholder'
        )
      "
      :label="
        t(
          'components.on-boarding.connect-more-services-file-settings-form.data-type.label'
        )
      "
    />
    <InputForm
      class="animate-fade-in"
      v-if="selectedProvider === AvalibleProviders.OTHER"
      v-model="displayedName"
      :label="
        t(
          'components.on-boarding.connect-more-services-file-settings-form.display-name.label'
        )
      "
      :placeholder="
        t(
          'components.on-boarding.connect-more-services-file-settings-form.display-name.placeholder'
        )
      "
    />
    <div
      v-if="selectedProvider"
      class="animate-fade-in flex flex-col gap-y-6 text-base text-connectMoreMedia-text"
    >
      <p
        v-html="
          t(
            `components.on-boarding.connect-more-services-file-settings-form.descriptions.header`
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
    <Teleport to="#next-button">
      <ButtonForm
        :type="isNextButtonDisabled ? 'disabled' : 'success'"
        class="w-full"
        @click="handleSubmitForm"
      >
        <div class="relative">
          {{
            t(
              "components.on-boarding.connect-more-services-file-settings-form.button.next"
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

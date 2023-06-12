<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { hGetParseDate } from "@/helpers/utils";
import type { FileType } from "@/stores/onboarding";
import { useOnBoardingStore } from "@/stores/onboarding";
import { ref, onMounted, nextTick, computed } from "vue";
import type { ImportType } from "@/services/api/imports";
import { handleGetImports, handleRevertImport } from "@/services/api/imports";
import {
  handleCompleteOnboarding,
  handleUploadFile
} from "@/services/api/onboarding";

enum TypeOfList {
  BASIC = "basic",
  EXTENDED = "extended"
}

enum ActionFiles {
  SHOW = "show",
  REVERT = "revert"
}

type PropsType = {
  type?: TypeOfList;
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const router = useRouter();
const props = withDefaults(defineProps<PropsType>(), {
  type: "basic" as TypeOfList
});
const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});
const importsList = ref<ImportType[]>([]);
const onBoardingStore = useOnBoardingStore();
const isSpinnerVisible = ref<boolean>(true);
const isComponentMounted = ref<boolean>(false);
const onlyTodayImportsHistoryList = computed<ImportType[]>(() =>
  props.type === TypeOfList.BASIC
    ? importsList.value
    : importsList.value.filter(
        (item: ImportType) => hGetParseDate(item.startedAt) === hGetParseDate()
      )
);

onMounted(async () => {
  await handleUploadFiles();
  importsList.value = await handleGetImports();
  nextTick(() => (isComponentMounted.value = true));
  isSpinnerVisible.value = false;
});

/**
 * Function to handle uplaod files.
 * @return {Promise<unknown>}
 */
async function handleUploadFiles(): Promise<unknown> {
  return new Promise((resolve) => {
    if (onBoardingStore.moreServices.length > 0) {
      onBoardingStore.moreServices.forEach(async (service: FileType) => {
        await handleUploadFile({
          file: service.file,
          type: service.fileImportTypes,
          campaignName: service.name
        });
      });
    }
    onBoardingStore.moreServices = [];
    resolve(true);
  });
}

/**
 * Function to submit finish setup onboarding.
 */
async function handleFinishSetup() {
  await handleCompleteOnboarding();
  router.push("/");
}

/**
 * Function to fired action for file.
 * @param {ImportType} file
 * @param {ActionFiles} action
 */
async function handleFiredActionFile(
  importedFile: ImportType,
  action: ActionFiles
) {
  switch (action) {
    case ActionFiles.SHOW:
      {
        window.open(importedFile.downloadUrl, "__blank");
      }
      break;
    case ActionFiles.REVERT:
      {
        isSpinnerVisible.value = true;
        await handleRevertImport({ importId: importedFile.id });
        importsList.value = await handleGetImports();
        isSpinnerVisible.value = false;

        notificationMessageModel.value.type = "success";
        notificationMessageModel.value.visible = true;
        notificationMessageModel.value.message = t(
          "components.shared.history-imported-files-form.notifications.success"
        );
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
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <div class="flex justify-between items-center">
      <HeaderText
        class="px-5"
        :header-title="
          t('components.shared.history-imported-files-form.header.title')
        "
      />
      <HeaderText
        class="px-5"
        :header-description="
          t(
            'components.shared.history-imported-files-form.header.description',
            {
              count: onlyTodayImportsHistoryList.length
            }
          )
        "
      />
    </div>
    <ScrollbarPanel
      class="overflow-x-hidden overflow-y-auto px-2 pb-2 flex flex-col gap-y-4 max-h-[500px]"
    >
      <CardPanel
        :key="`import-${importFile.id}`"
        v-for="importFile in onlyTodayImportsHistoryList"
      >
        <div class="grid grid-flow-row grid-rows-auto gap-2">
          <div class="grid grid-cols-[32px_1fr_1fr] items-center gap-2">
            <IconSvg name="checkmark" :class-name="`w-8 h-8`" />
            <h4 class="text-base font-bold">
              {{
                t("components.shared.history-imported-files-form.import-from")
              }}
              {{
                importFile.__typename === "ApiImport"
                  ? importFile.dataProvider.name
                  : t("components.shared.history-imported-files-form.file")
              }}
            </h4>
            <div
              class="flex direction-column"
              :class="{
                'justify-end': importFile.__typename !== 'FileImport'
              }"
            >
              <ButtonForm
                @click="handleFiredActionFile(importFile, ActionFiles.REVERT)"
                isSmall
                :class="{ 'w-1/2': importFile.__typename !== 'FileImport' }"
              >
                {{
                  t(
                    "components.shared.history-imported-files-form.button.revert"
                  )
                }}
              </ButtonForm>
              <ButtonForm
                @click="handleFiredActionFile(importFile, ActionFiles.SHOW)"
                isSmall
                class="ml-3"
                v-if="importFile.__typename === 'FileImport'"
                >{{
                  t("components.shared.history-imported-files-form.button.show")
                }}</ButtonForm
              >
            </div>
          </div>
          <div
            class="grid grid-cols-[32px_1fr_1fr] items-center gap-2 text-xs leading-normal"
          >
            <div class="col-start-2">
              <p>
                <span class="text-green-600 font-bold"
                  >{{ importFile.result.createdAds }}
                  {{
                    importFile.result.createdAds === 1
                      ? t("components.shared.history-imported-files-form.ad")
                      : t("components.shared.history-imported-files-form.ads")
                  }}</span
                >
                {{
                  importFile.result.createdAds === 1
                    ? t(
                        "components.shared.history-imported-files-form.element-added"
                      )
                    : t(
                        "components.shared.history-imported-files-form.elements-added"
                      )
                }}.
              </p>
              <p>
                <span class="text-green-600 font-bold"
                  >{{ importFile.result.createdCampaigns }}
                  {{
                    importFile.result.createdCampaigns === 1
                      ? t(
                          "components.shared.history-imported-files-form.campaign"
                        )
                      : t(
                          "components.shared.history-imported-files-form.campaigns"
                        )
                  }}</span
                >
                {{
                  importFile.result.createdCampaigns === 1
                    ? t(
                        "components.shared.history-imported-files-form.element-added"
                      )
                    : t(
                        "components.shared.history-imported-files-form.elements-added"
                      )
                }}.
              </p>
            </div>
            <div class="col-start-2 text-gray-900" v-if="importFile.endedAt">
              <p>{{ importFile.endedAt }}</p>
              <p v-if="importFile.__typename === 'FileImport'">
                {{ importFile.filename }}
              </p>
            </div>
          </div>
        </div>
      </CardPanel>
    </ScrollbarPanel>
    <Teleport to="#next-button" v-if="type === TypeOfList.EXTENDED">
      <ButtonForm type="success" class="w-full" @click="handleFinishSetup">
        <div class="relative">
          {{ t("components.shared.history-imported-files-form.button.next") }}
          <IconSvg
            name="arrownext"
            class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3"
          />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

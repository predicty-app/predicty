<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { hGetParseDate } from "@/helpers/utils";
import { ref, onMounted, nextTick, computed } from "vue";
import type { ImportType } from "@/services/api/imports";
import { handleGetImports } from "@/services/api/imports";
import { handleCompleteOnboarding } from "@/services/api/onboarding";

const { t } = useI18n();

const router = useRouter();
const importsList = ref<ImportType[]>([]);
const isSpinnerVisible = ref<boolean>(true);
const isComponentMounted = ref<boolean>(false);
const onlyTodayImportsHistoryList = computed<ImportType[]>(() =>
  importsList.value.filter(
    (item: ImportType) => hGetParseDate(item.startedAt) === hGetParseDate()
  )
);

onMounted(async () => {
  importsList.value = await handleGetImports();
  nextTick(() => (isComponentMounted.value = true));
  isSpinnerVisible.value = false;
});

/**
 * Function to submit finish setup onboarding.
 */
async function handleFinishSetup() {
  await handleCompleteOnboarding();
  router.push("/");
}
</script>
<template>
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <div class="flex justify-between items-center">
      <HeaderText
        class="px-5"
        :header-title="
          t('components.on-boarding.history-imported-files-form.header.title')
        "
      />
      <HeaderText
        class="px-5"
        :header-description="
          t(
            'components.on-boarding.history-imported-files-form.header.description',
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
                t(
                  "components.on-boarding.history-imported-files-form.import-from"
                )
              }}
              {{
                importFile.__typename === "ApiImport"
                  ? importFile.dataProvider.name
                  : t("components.on-boarding.history-imported-files-form.file")
              }}
            </h4>
            <div
              class="flex direction-column"
              :class="{
                'justify-end': importFile.__typename !== 'FileImport'
              }"
            >
              <ButtonForm
                isSmall
                :class="{ 'w-1/2': importFile.__typename !== 'FileImport' }"
              >
                {{
                  t(
                    "components.on-boarding.history-imported-files-form.button.revert"
                  )
                }}
              </ButtonForm>
              <ButtonForm
                isSmall
                class="ml-3"
                v-if="importFile.__typename === 'FileImport'"
                >{{
                  t(
                    "components.on-boarding.history-imported-files-form.button.show"
                  )
                }}</ButtonForm
              >
            </div>
          </div>
          <div
            class="grid grid-cols-[32px_1fr_1fr] items-center gap-2 text-xs leading-normal"
          >
            <div class="col-start-2">
              <p>
                <span class="text-imports-green font-bold"
                  >{{ importFile.result.createdAds }}
                  {{
                    importFile.result.createdAds === 1
                      ? t(
                          "components.on-boarding.history-imported-files-form.ad"
                        )
                      : t(
                          "components.on-boarding.history-imported-files-form.ads"
                        )
                  }}</span
                >
                {{
                  importFile.result.createdAds === 1
                    ? t(
                        "components.on-boarding.history-imported-files-form.element-added"
                      )
                    : t(
                        "components.on-boarding.history-imported-files-form.elements-added"
                      )
                }}.
              </p>
              <p>
                <span class="text-imports-green font-bold"
                  >{{ importFile.result.createdCampaigns }}
                  {{
                    importFile.result.createdCampaigns === 1
                      ? t(
                          "components.on-boarding.history-imported-files-form.campaign"
                        )
                      : t(
                          "components.on-boarding.history-imported-files-form.campaigns"
                        )
                  }}</span
                >
                {{
                  importFile.result.createdCampaigns === 1
                    ? t(
                        "components.on-boarding.history-imported-files-form.element-added"
                      )
                    : t(
                        "components.on-boarding.history-imported-files-form.elements-added"
                      )
                }}.
              </p>
            </div>
            <div
              class="col-start-2 text-imports-grey"
              v-if="importFile.completedAt"
            >
              <p>{{ importFile.completedAt }}</p>
              <p v-if="importFile.__typename === 'FileImport'">
                {{ importFile.filename }}
              </p>
            </div>
          </div>
        </div>
      </CardPanel>
    </ScrollbarPanel>
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full" @click="handleFinishSetup">
        <div class="relative">
          {{
            t("components.on-boarding.history-imported-files-form.button.next")
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

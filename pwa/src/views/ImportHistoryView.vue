<script setup lang="ts">
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { onMounted, ref } from "vue";
import { handleGetImports, type ImportType } from "@/services/api/imports";

const { t } = useI18n();
const router = useRouter();
const previousStepPath = "/";
let imports = ref<ImportType[]>(null);

onMounted(async () => {
  imports.value = await handleGetImports();
});
</script>

<template>
  <SupportingLayout>
    <template #content>
      <HeaderText
        class="px-5"
        :header-title="t('views.import-history.title')"
      />
      <ScrollbarPanel class="overflow-x-hidden overflow-y-auto px-2 pb-2">
        <div
          v-for="element in imports"
          :key="element.id"
          class="mb-4 last:mb-0"
        >
          <CardPanel
            class="mb-4 last:mb-0"
            v-if="element.status === 'COMPLETE'"
          >
            <div class="grid grid-flow-row grid-rows-auto gap-2">
              <div class="grid grid-cols-[32px_1fr_1fr] items-center gap-2">
                <IconSvg name="checkmark" :class-name="`w-8 h-8`" />
                <h4 class="text-base font-bold">
                  {{ t("views.import-history.import-from") }}
                  {{
                    element.__typename === "ApiImport"
                      ? element.dataProvider.name
                      : t("views.import-history.file")
                  }}
                </h4>
                <div
                  class="flex direction-column"
                  :class="{
                    'justify-end': element.__typename !== 'FileImport'
                  }"
                >
                  <ButtonForm
                    isSmall
                    :class="{ 'w-1/2': element.__typename !== 'FileImport' }"
                  >
                    {{ t("views.import-history.revert") }}
                  </ButtonForm>
                  <ButtonForm
                    isSmall
                    class="ml-3"
                    v-if="element.__typename === 'FileImport'"
                    >{{ t("views.import-history.show") }}</ButtonForm
                  >
                </div>
              </div>
              <div
                class="grid grid-cols-[32px_1fr_1fr] items-center gap-2 text-xs leading-normal"
              >
                <div class="col-start-2">
                  <p>
                    <span class="text-imports-green font-bold"
                      >{{ element.result.createdAds }}
                      {{
                        element.result.createdAds === 1
                          ? t("views.import-history.ad")
                          : t("views.import-history.ads")
                      }}</span
                    >
                    {{
                      element.result.createdAds === 1
                        ? t("views.import-history.element-added")
                        : t("views.import-history.elements-added")
                    }}.
                  </p>
                  <p>
                    <span class="text-imports-green font-bold"
                      >{{ element.result.createdCampaigns }}
                      {{
                        element.result.createdCampaigns === 1
                          ? t("views.import-history.campaign")
                          : t("views.import-history.campaigns")
                      }}</span
                    >
                    {{
                      element.result.createdCampaigns === 1
                        ? t("views.import-history.element-added")
                        : t("views.import-history.elements-added")
                    }}.
                  </p>
                </div>
                <div
                  class="col-start-2 text-imports-grey"
                  v-if="element.completedAt"
                >
                  <p>{{ element.completedAt }}</p>
                  <p v-if="element.__typename === 'FileImport'">
                    {{ element.filename }}
                  </p>
                </div>
              </div>
            </div>
          </CardPanel>
        </div>
      </ScrollbarPanel>
    </template>
    <template #footer>
      <div class="w-full max-w-[48px]">
        <ButtonForm
          class="w-full flex justify-center"
          @click="router.push(previousStepPath)"
        >
          <IconSvg name="arrowback" class-name="h-3 w-3" />
        </ButtonForm>
      </div>
    </template>
  </SupportingLayout>
</template>

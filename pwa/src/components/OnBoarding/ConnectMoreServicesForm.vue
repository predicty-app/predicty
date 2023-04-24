<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import type { FileType } from "@/stores/onboarding";
import { useOnBoardingStore } from "@/stores/onboarding";
import { ref, onMounted, nextTick, computed } from "vue";

const { t } = useI18n();

const router = useRouter();
const searchValueModel = ref<string>("");
const onBoardingStore = useOnBoardingStore();
const isComponentMounted = ref<boolean>(false);
const nextStepPath = computed<string>(() => "/onboarding/preparing-screen");
const filterMoreServiceList = computed<FileType[]>(() =>
  searchValueModel.value.length > 2
    ? onBoardingStore.moreServices.filter((service: FileType) =>
        service.type === "OTHER"
          ? service.name.includes(searchValueModel.value)
          : t(
              `components.on-boarding.connect-more-services-form.data-type.options.${service.type}`
            ).includes(searchValueModel.value)
      )
    : onBoardingStore.moreServices
);

const addFileStepPath = computed<string>(
  () => "/onboarding/more-media-integration/file-settings"
);

onMounted(() => nextTick(() => (isComponentMounted.value = true)));

function parseServiceName(service: FileType): string {
  switch (service.type) {
    case "OTHER": {
      return `${service.name} <br/> <span class="opacity-75">(custom)</span>`;
    }
    default: {
      return t(
        `components.on-boarding.connect-more-services-form.data-type.options.${service.type}`
      ).replace("(Meta)", "");
    }
  }
}
</script>

<template>
  <div v-if="isComponentMounted" class="flex flex-col gap-y-6">
    <InputForm
      icon="search"
      v-model="searchValueModel"
      :placeholder="
        t('components.on-boarding.connect-more-services-form.input.placeholder')
      "
    />
    <div class="flex items-center gap-[8px]">
      <CardPanel
        class="animate-fade-in w-[90px] h-[100px] p-0 flex items-center justify-center"
        type="success"
        :key="`service_${index}`"
        v-for="(service, index) in filterMoreServiceList"
      >
        <div class="flex flex-col items-center gap-y-4">
          <span
            class="text-xs font-bold text-center"
            v-html="parseServiceName(service)"
          ></span>
          <div
            class="bg-connectMoreMedia-icon-background rounded-full w-7 h-7 flex items-center justify-center"
          >
            <IconSvg
              name="check"
              class-name="w-4 h-4 fill-connectMoreMedia-icon-fill"
            />
          </div>
        </div>
      </CardPanel>
      <CardPanel
        @click="router.push(addFileStepPath)"
        class="w-[90px] h-[100px] p-0 flex items-center justify-center cursor-pointer"
        :key="`service_add`"
      >
        <div
          class="text-center"
          v-html="
            t('components.on-boarding.connect-more-services-form.services.add')
          "
        ></div>
      </CardPanel>
    </div>
    <Teleport to="#next-button">
      <ButtonForm
        type="success"
        class="w-full"
        @click="router.push(nextStepPath)"
      >
        <div class="relative">
          {{
            t("components.on-boarding.connect-more-services-form.button.next")
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

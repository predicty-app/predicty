<script setup lang="ts">
import { useI18n } from "vue-i18n";

type PropsType = {
  filesType?: string[];
};

defineProps<PropsType>();
const { t } = useI18n();
</script>

<template>
  <div class="flex flex-col gap-y-4">
    <div data-testid="upload-file" class="flex items-center gap-x-1">
      <h3 class="font-ibm-sans text-lg font-bold mr-2">
        {{ t("components.common.upload-file.header") }}
      </h3>
      <TagPin :key="item" v-for="item in filesType">
        {{ item }}
      </TagPin>
    </div>
    <div
      class="text-upload-text gap-y-3 text-base relative bg-upload-background border border-upload-border border-dashed flex flex-col items-center justify-center w-full py-6"
    >
      {{ t("components.common.upload-file.content") }}
      <input
        ref="inputInstance"
        type="file"
        name="file"
        class="opacity-0 absolute w-full h-full top-0 left-0 cursor-pointer"
      />
      <div
        class="bg-upload-button-background rounded-[6px] px-4 py-[10px] text-base items-center justify-center text-upload-button-text flex gap-x-3"
      >
        <IconSvg name="upload" />
        {{ t("components.common.upload-file.button") }}
      </div>
    </div>
  </div>
</template>

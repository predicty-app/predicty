<script setup lang="ts">
import { useI18n } from "vue-i18n";

type PropsType = {
  filesType?: string[]
}

defineProps<PropsType>()
const { t } = useI18n()
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
      class="text-upload-text gap-x-3 text-base relative bg-upload-background border border-upload-border border-dashed flex items-center justify-center w-full py-6">
      <IconSvg name="download" class-name="w-4" />
      {{ t("components.common.upload-file.content") }}
      <input ref="inputInstance" type="file" name="file"
        class="opacity-0 absolute w-full h-full top-0 left-0 cursor-pointer" />
    </div>
  </div>
</template>

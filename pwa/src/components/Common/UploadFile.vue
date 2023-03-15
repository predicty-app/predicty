<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ref, watch, nextTick } from "vue";
import NotificationMessage from "@/components/Common/NotificationMessage.vue";

type PropsType = {
  modelValue: File | string | null;
  filesType?: string[];
};

const props = defineProps<PropsType>();
const { t } = useI18n();

const emit = defineEmits<{
  (e: "update:modelValue", value: File): void;
}>();

type NotificationType = {
  type?: "success" | "error";
  message: string;
  isVisible: boolean;
};

const fileName = ref<string | null>(null);
const inputInstance = ref<HTMLInputElement | null>(null);
const isFileUploaded = ref<boolean>(false);
const fileInstance = ref<File | null>(null);

const notificationModel = ref<NotificationType>({
  type: "success",
  message: "",
  isVisible: false
});

watch(
  () => props.modelValue,
  () => {
    isFileUploaded.value = props.modelValue ? true : false;
    if (!props.modelValue) {
      inputInstance.value.value = null;
    }
  }
);

/**
 * Function to validate file.
 * @return {boolean}
 */
function validateFile(): boolean {
  if (!fileInstance.value) {
    return false;
  }

  if (!["text/csv"].includes(fileInstance.value.type)) {
    notificationModel.value.isVisible = true;
    notificationModel.value.type = "error";
    notificationModel.value.message = t(
      "components.common.upload-file.error-type"
    );

    inputInstance.value.value = null;
    return false;
  }

  return true;
}

/**
 * Function to handle select file.
 * @param {Event} e
 */
function handleSelectFile(e: Event) {
  fileInstance.value = (e.target as HTMLInputElement).files[0];

  if (validateFile()) {
    isFileUploaded.value = true;
    nextTick(() => {
      fileName.value = fileInstance.value.name;
      emit("update:modelValue", (e.target as HTMLInputElement).files[0]);
    });
  }
}
</script>

<template>
  <NotificationMessage
    v-model="notificationModel.isVisible"
    :message="notificationModel.message"
    :type="notificationModel.type"
  />
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
      <IconSvg
        v-if="isFileUploaded"
        @click="emit('update:modelValue', null)"
        name="close"
        class-name="absolute top-3 right-3 z-30 cursor-pointer"
      />
      {{ t("components.common.upload-file.content") }}
      <input
        ref="inputInstance"
        type="file"
        name="file"
        class="opacity-0 absolute w-full h-full top-0 left-0 cursor-pointer"
        @change="handleSelectFile"
      />
      <div
        v-if="!isFileUploaded"
        class="bg-upload-button-background animate-fade-in rounded-[6px] px-4 py-[10px] text-base items-center justify-center text-upload-button-text flex gap-x-3"
      >
        <IconSvg name="upload" />
        {{ t("components.common.upload-file.button") }}
      </div>
      <div v-if="isFileUploaded" class="animate-fade-in">
        {{ fileName }}
      </div>
    </div>
  </div>
</template>

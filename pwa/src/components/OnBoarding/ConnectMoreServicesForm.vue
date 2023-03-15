<script setup lang="ts">
import { ref, watch } from "vue";
import { useOnBoardingStore } from "@/stores/onboarding";

const onBoardingStore = useOnBoardingStore();
const fileInstance = ref<File | string | null>(null);
const allowedFiles = [".csv", ".xls"];

watch(fileInstance, () => {
  onBoardingStore.handleSaveFile(fileInstance.value as File);
});
</script>

<template>
  <UploadFile v-model="fileInstance" :files-type="allowedFiles" />
</template>

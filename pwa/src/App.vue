<script setup lang="ts">
import { onMounted } from "vue";
import { handleGetAppSettings } from "@/services/api/app";

onMounted(() => {
  handleGetAppSettings();
});
</script>

<template>
  <RouterView />
</template>

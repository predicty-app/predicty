<script setup lang="ts">
const allowedFiles = [".csv", ".xls"];
</script>

<template>
  <UploadFile :files-type="allowedFiles" />
</template>

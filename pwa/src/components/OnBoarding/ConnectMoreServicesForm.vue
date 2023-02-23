<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const allowedFiles = ['.csv', '.xls']
</script>

<template>
  <UploadFile :files-type="allowedFiles"/>
</template>
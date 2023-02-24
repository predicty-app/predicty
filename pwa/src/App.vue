<script setup lang="ts">
import ContextProviders from '@/services/providers/ContextProviders'
import GoogleProviderStrategy from '@/services/providers/GoogleProviderStrategy'

const context = new ContextProviders(new GoogleProviderStrategy())

context.loginClient()
</script>
<template>

  <!-- <RouterView /> -->
</template> 
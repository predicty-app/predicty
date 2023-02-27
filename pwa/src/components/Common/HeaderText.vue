<script setup lang="ts">
type PropsType = {
  headerTitle?: string;
  headerDescription?: string;
};

defineProps<PropsType>();
</script>
<template>
  <div class="flex flex-col gap-y-6">
    <h3
      v-if="headerTitle"
      data-testid="header-text"
      class="font-ibm-sans font-bold text-3xl m-0 text-text-primary"
    >
      {{ headerTitle }}
    </h3>
    <p
      v-if="headerDescription"
      data-testid="header-description"
      class="text-base text-text-secondary"
    >
      {{ headerDescription }}
    </p>
  </div>
</template>

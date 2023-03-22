<script setup lang="ts">
import { useI18n } from "vue-i18n";
import type { AdsCollection } from "@/stores/userDashboard";

const { t } = useI18n();

type PropsType = {
  collection?: AdsCollection;
};

defineProps<PropsType>();

defineEmits<{
  (e: "close"): void;
}>();
</script>

<template>
  <div
    v-if="collection"
    class="collection-bottom-bar fixed display-flex left-0 right-0 bottom-0 z-50 rounded-t-2xl bg-text-white shadow-bottombar"
    data-testid="collection-bottom-bar"
  >
    <div class="collection-bottom-bar__header p-5 flex justify-between">
      <CollectionHeader :collection="collection" />
      <button
        class="collection-bottom-bar__close p-2 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 rounded-md m-l-auto self-baseline"
        @click="$emit('close')"
        :aria-label="t('components.user-dashboard.bottom-bar.close')"
        data-testid="collection-bottom-bar__close"
      >
        <IconSvg name="close" class="w-[12px] h-[12px]" />
      </button>
    </div>
    <div
      class="collection-bottom-bar__content max-h-[50vh] max-w-full scroll-bar whitespace-nowrap overflow-y-scroll overflow-x-hidden"
    >
      <CollectionTimeline :collection="collection" />
    </div>
  </div>
</template>

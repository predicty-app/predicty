<script setup lang="ts">
import { useI18n } from "vue-i18n";
import type { AdSetsType, AdsType } from "@/stores/userDashboard";

const { t } = useI18n();

type PropsType = {
  collection?: AdsType | AdSetsType;
};

const props = defineProps<PropsType>();

defineEmits<{
  (e: "close"): void;
}>();
</script>

<template>
  <div
    v-if="props.collection"
    class="collection-bottom-bar fixed display-flex left-0 right-0 bottom-0 z-10 p-5 rounded-t-2xl bg-text-white shadow-bottombar"
    data-testid="collection-bottom-bar"
  >
    <div class="flex justify-between">
      <h1 class="font-semibold text-xl">{{ props.collection.name }}</h1>
      <button
        class="collection-bottom-bar__close p-2 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 rounded-md m-l-auto"
        @click="$emit('close')"
        :aria-label="t('components.user-dashboard.bottom-bar.close')"
        data-testid="collection-bottom-bar__close"
      >
        <IconSvg name="close" class="w-[12px] h-[12px]" />
      </button>
    </div>
    <div>Hello collection</div>
  </div>
</template>

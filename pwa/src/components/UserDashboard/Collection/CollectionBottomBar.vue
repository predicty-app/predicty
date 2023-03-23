<script setup lang="ts">
import { ref, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import type { AdSetsType } from "@/stores/userDashboard";

type PropsType = {
  collection?: AdSetsType;
};

const props = defineProps<PropsType>();
const globalStore = useGlobalStore();
const heightContent = ref<string>("0px");

defineEmits<{
  (e: "close"): void;
}>();

watch(
  () => props.collection,
  () => {
    const height = globalStore.scrollTimeline.getBoundingClientRect().height;
    heightContent.value = `${height + 23}px`;
  }
);
</script>

<template>
  <div
    v-if="collection"
    class="collection-bottom-bar animate-fade-in-up fixed display-flex left-0 right-0 bottom-0 z-50 rounded-t-2xl bg-text-white shadow-bottombar"
    data-testid="collection-bottom-bar"
  >
    <UserDashboardDetialsLayout :height-inner="heightContent">
      <template #collection-settings>
        <CollectionHeader :collection="collection" />
      </template>
      <template #collection-ads-list>
        <CollectionAdsList :collection="collection" />
      </template>
      <template #collection-providers-list>
        <CollectionProvidersList :collection="collection" />
      </template>
      <template #collection-ads-weeks>
        <CollectionWeeks />
      </template>
      <template #collection-ads-timeline>
        <CollectionTimeline>
          <CollectionTimelineItem
            type="ad"
            :element="ad"
            :uid="ad.uid"
            :is-visible="true"
            :color="collection.color"
            v-for="ad in collection.ads"
            :key="`collection-${ad.uid}`"
            :end="globalStore.dictionaryTimeline[ad.end]"
            :start="globalStore.dictionaryTimeline[ad.start]"
          />
        </CollectionTimeline>
      </template>
    </UserDashboardDetialsLayout>

    <button
      class="p-2 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 absolute right-4 z-10 top-2 rounded-md m-l-auto self-baseline"
      @click="$emit('close')"
      data-testid="collection-bottom-bar__close"
    >
      <IconSvg name="close" class="w-[12px] h-[12px]" />
    </button>
  </div>
  <!-- <div class="collection-bottom-bar__header p-5 flex justify-between">
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
      </div> -->
</template>

<style scoped lang="scss">
.collection-bottom-bar {
  height: v-bind(heightContent);
}
</style>

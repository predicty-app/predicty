<script setup lang="ts">
import { ref, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { SelectedCollectionType, AdsType } from "@/stores/userDashboard";

type PropsType = {
  selectedCollection?: SelectedCollectionType;
};

const props = defineProps<PropsType>();
const globalStore = useGlobalStore();
const heightContent = ref<string>("0px");
const userDashboardStore = useUserDashboardStore();

const emit = defineEmits<{
  (e: "handleCloseDetails"): void;
}>();

watch(
  () => props.selectedCollection,
  () => {
    const height = globalStore.scrollTimeline.getBoundingClientRect().height;
    heightContent.value = `${height + 23}px`;
  }
);

/**
 * Function to handle close details collection.
 */
function handleCloseDetailsCollection() {
  userDashboardStore.removeVisibilityAds(
    userDashboardStore.selectedCollection.collection.ads.map(
      (ad: AdsType) => ad.id
    )
  );
  userDashboardStore.selectedCollectionAdsList.ads = [];
  emit("handleCloseDetails");

  globalStore.scrollTimeline.scrollLeft = globalStore.scrollParams.x;
}
</script>

<template>
  <div
    v-if="selectedCollection"
    class="collection-bottom-bar animate-fade-in-up bg-basic-white fixed display-flex left-0 right-0 bottom-0 z-[200] rounded-t-2xl bg-text-white shadow-bottombar"
    data-testid="collection-bottom-bar"
  >
    <UserDashboardDetialsLayout :height-inner="heightContent">
      <template #collection-settings>
        <CollectionHeader :collection="selectedCollection.collection" />
      </template>
      <template #collection-ads-list>
        <CollectionAdsList :collection="selectedCollection.collection" />
      </template>
      <template #collection-providers-list>
        <CollectionProvidersList :collection="selectedCollection.collection" />
      </template>
      <template #collection-ads-weeks>
        <CollectionWeeks />
      </template>
      <template #collection-ads-timeline>
        <CollectionTimeline>
          <CollectionTimelineItem
            type="ad"
            :element="ad"
            :uid="ad.id"
            :is-visible="true"
            :color="selectedCollection.color"
            v-for="ad in selectedCollection.collection.ads"
            :key="`collection-${ad.id}`"
            :end="globalStore.dictionaryTimeline[ad.adStats.at(-1).date]"
            :start="globalStore.dictionaryTimeline[ad.adStats.at(0).date]"
          />
        </CollectionTimeline>
      </template>
    </UserDashboardDetialsLayout>
    <button
      class="p-2 focus:bg-gray-400/50 hover:bg-gray-400/50 absolute right-4 z-10 top-2 rounded-md m-l-auto self-baseline"
      @click="() => handleCloseDetailsCollection()"
      data-testid="collection-bottom-bar__close"
    >
      <IconSvg name="close" class="w-[12px] h-[12px]" />
    </button>
  </div>
</template>

<style scoped lang="scss">
.collection-bottom-bar {
  height: v-bind(heightContent);
}
</style>

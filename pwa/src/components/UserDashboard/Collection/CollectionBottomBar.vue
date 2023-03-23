<script setup lang="ts">
import { ref, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { AdSetsType, AdsType } from "@/stores/userDashboard";

type PropsType = {
  collection?: AdSetsType;
};

const props = defineProps<PropsType>();
const globalStore = useGlobalStore();
const heightContent = ref<string>("0px");
const userDashboardStore = useUserDashboardStore();

const emit = defineEmits<{
  (e: "handleCloseDetials"): void;
}>();

watch(
  () => props.collection,
  () => {
    const height = globalStore.scrollTimeline.getBoundingClientRect().height;
    heightContent.value = `${height + 23}px`;
  }
);

/**
 * Function to handle cloe detials collection.
 */
function handleCloseDetialcCollection() {
  userDashboardStore.removeVisibilityAds(
    userDashboardStore.selectedCollection.ads.map((ad: AdsType) => ad.uid)
  );
  userDashboardStore.selectedCollectionAdsList.ads = [];
  emit("handleCloseDetials");

  globalStore.scrollTimeline.scrollLeft = globalStore.scrollParams.x;
}
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
      @click="() => handleCloseDetialcCollection()"
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

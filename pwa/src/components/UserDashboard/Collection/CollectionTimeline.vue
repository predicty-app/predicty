<script setup lang="ts">
import type { AdsCollection } from "@/stores/userDashboard";
import { useGlobalStore } from "@/stores/global";
import { onMounted, reactive } from "vue";
import { useUserDashboardStore } from "@/stores/userDashboard";
import CollectionSideItems from "./CollectionSideItems.vue";

type PropsType = {
  collection?: AdsCollection;
};

const props = defineProps<PropsType>();
const globalStore = useGlobalStore();
const userStore = useUserDashboardStore();

let state = reactive({
  ads: [],
});

onMounted(() => {
  (props.collection as AdsCollection).ads.forEach((ad) =>
    userStore.campaigns.map((campaign) =>
      campaign.ads.find((a) => {
        a.uid === ad ? state.ads.push(a) : "";
      })
    )
  );
});
</script>

<template>
  <div class="collection-timeline">
    <ChartTimelineWrapper
      :hasWeekdays="true"
      class="ml-[336px] relative w-full h-full bg-bottombar-background-light"
    >
      <div
        class="w-[336px] -ml-[336px] -mt-[50px] pt-[50px] -mb-[18px] pb-[18px] sticky float-left left-0 top-0 h-full bg-bottombar-background-light z-10"
      >
        <CollectionSideItems :ads="state.ads" />
      </div>
      <ChartTimelineContent
        :count-elements="state.ads.length"
        :fixedHeight="true"
        :key="index"
        v-for="(ad, index) in state.ads"
      >
        <ChartTimelineItem
          :element="ad"
          type="ad"
          :uid="ad.uid"
          :key="`${ad.uid}_${Math.random()}`"
          :start="globalStore.dictionaryTimeline[ad.start]"
          :end="globalStore.dictionaryTimeline[ad.end]"
          :noName="true"
        />
      </ChartTimelineContent>
    </ChartTimelineWrapper>
  </div>
</template>

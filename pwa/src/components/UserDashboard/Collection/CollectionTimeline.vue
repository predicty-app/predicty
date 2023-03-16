<script setup lang="ts">
import type { AdsCollection } from "@/stores/userDashboard";
import { useGlobalStore } from "@/stores/global";
import { onMounted, reactive } from "vue";
import { useUserDashboardStore } from "@/stores/userDashboard";

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
  <div>
    <div>
      <ChartTimelineWrapper :hasWeekdays="true">
        <ChartTimelineContent
          :count-elements="state.ads.length"
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
  </div>
</template>

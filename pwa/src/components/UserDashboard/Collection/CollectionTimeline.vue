<script setup lang="ts">
import type { AdSetsType, CampaignType } from "@/stores/userDashboard";
import { useGlobalStore } from "@/stores/global";
import { onMounted, reactive } from "vue";
import { useUserDashboardStore } from "@/stores/userDashboard";
import CollectionSideItems from "./CollectionSideItems.vue";

type PropsType = {
  collection?: AdSetsType;
};

const props = defineProps<PropsType>();
const globalStore = useGlobalStore();
const userStore = useUserDashboardStore();

let state = reactive({
  ads: []
});

// onMounted(() => {
//   (props.collection as AdSetsType).ads.forEach((ad) =>
//     userStore.campaigns.map((campaign: CampaignType) =>
//       campaign.ads.find((a) => {
//         a.uid === ad ? state.ads.push(a) : "";
//       })
//     )
//   );
// });
</script>

<template>
  <div class="collection-timeline">
    <UserDashboardLayout :singleRow="true">
      <template #ads-campaigns>
        <CollectionSideItems :ads="state.ads" />
      </template>
      <template #ads-weeks>
        <ChartTimelineWeeks :hasWeekdays="true" />
      </template>
      <template #ads-chart>
        <ChartTimelineWrapper :hasWeekdays="true">
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
              :isCollection="true"
            />
          </ChartTimelineContent>
        </ChartTimelineWrapper>
      </template>
    </UserDashboardLayout>
  </div>
</template>

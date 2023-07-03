<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";

type ScrollType = {
  x: number;
  y: number;
  direction: "horizontal" | "vertical" | null | string;
};

const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();

/**
 * Function to hanlde change scroll params.
 * @param {ScrollType} params
 */
function handleChangeScrollParams(params: ScrollType) {
  globalStore.handleChangeScrollParams(params);
  userDashboardStore.handleVirtualizeCampaignsList();
}
</script>

<template>
  <slot name="header" />
  <div
    class="select-none grid grid-cols-[336px_auto]"
    @mouseup="globalStore.isActiveActionDrag = false"
  >
    <div
      class="h-calc select-none grid grid-cols-[auto] grid-rows-[345px_80px_auto]"
    >
      <div>
        <slot name="chart-legend" />
      </div>
      <div>
        <slot name="providers-list" />
      </div>
      <ScrollbarPanel
        @onMounted="globalStore.setInstanceScrollCampaignsList"
        :scroll-y="globalStore.scrollParams.y"
      >
        <slot name="ads-campaigns" />
      </ScrollbarPanel>
    </div>
    <div
      class="relative h-calc select-none grid grid-cols-[auto] grid-rows-[70px_270px_30px_30px_auto]"
    >
      <ConversationCommentsMask />
      <ConversationCommentsLinesWrapper />
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
        <slot name="chart-weeks" />
      </ScrollbarPanel>
      <ScrollbarPanel
        :scroll-x="globalStore.scrollParams.x"
        :isOverflowHidden="false"
      >
        <slot name="chart" />
      </ScrollbarPanel>
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
        <slot name="chart-days" />
      </ScrollbarPanel>
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
        <slot name="ads-weeks" />
      </ScrollbarPanel>
      <ScrollbarPanel
        @onMounted="globalStore.setInstanceScrollTimeline"
        @onChange="handleChangeScrollParams"
        :is-vertical-scroll-visible="true"
      >
        <slot name="ads-chart" />
      </ScrollbarPanel>
    </div>
  </div>
</template>

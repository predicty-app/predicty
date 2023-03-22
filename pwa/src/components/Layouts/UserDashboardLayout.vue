<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";

type PropsType = {
  singleRow?: boolean;
};

defineProps<PropsType>();

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
    :class="[
      'select-none grid grid-cols-[336px_auto]',
      { 'h-calc': !singleRow },
    ]"
  >
    <div
      class=""
      :class="[
        {
          'h-calc select-none grid grid-cols-[auto] grid-rows-[320px_auto]':
            !singleRow,
          'pt-[44px]': singleRow,
        },
      ]"
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
      :class="[
        {
          'h-calc select-none grid grid-cols-[auto] grid-rows-[50px_270px_30px_30px_auto]':
            !singleRow,
        },
      ]"
    >
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
        <slot name="chart-weeks" />
      </ScrollbarPanel>
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
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

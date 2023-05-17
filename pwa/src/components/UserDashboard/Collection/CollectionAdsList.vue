<script setup lang="ts">
import { computed } from "vue";
import type { AdSetsType } from "@/stores/userDashboard";
import { heightCollectionContent } from "@/helpers/timeline";

type PropsType = {
  collection?: AdSetsType;
};

defineProps<PropsType>();

const pathImages = "/assets/images/providers";
const parsedHeightContent = computed<string>(
  () => `${Number(heightCollectionContent.value.slice(0, -2)) + 50}px`
);

/**
 * Function for format date.
 * @param {string} date
 * @return {string}
 */
function formatDate(date: string): string {
  let parts = date.split(/\D/g);

  return [parts[2], parts[1]].join(".");
}
</script>

<template>
  <div
    class="h-dynamic pt-[10px] relative"
    :style="{ '--height': parsedHeightContent }"
  >
    <div class="collection-ads-list absolute w-full">
      <div
        class="px-9 flex gap-x-2 [&:nth-child(2n+1)]:bg-gray-400/50 items-center h-[47px]"
        :key="`collection-ad-${ad.id}`"
        v-for="ad in collection.ads"
      >
        <div>
          <img
            class="w-5 h-5"
            :src="`${pathImages}/${ad.dataProvider[0].toLocaleLowerCase()}.png`"
          />
        </div>
        <div>
          <p class="text-xs text-gray-900 font-medium">
            {{ ad.name }}
          </p>
          <p
            class="text-xs text-gray-900"
            data-testid="collection-side-item__dates"
          >
            {{ formatDate(ad.adStats.at(0).date) }} -
            {{ formatDate(ad.adStats.at(-1).date) }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.collection-ads-list {
  display: grid;
  grid-row-gap: 5px;
}
</style>

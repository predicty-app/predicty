<script setup lang="ts">
import { ref, onMounted } from "vue";
import {
  useUserDashboardStore,
  TypeOptionsChart
} from "@/stores/userDashboard";
import * as d3Selection from "d3-selection";
import * as d3Shape from "d3-shape";

type PropsType = {
  day?: string;
  height?: number;
  type?: TypeOptionsChart;
  result?: number;
  id?: string;
};

const props = withDefaults(defineProps<PropsType>(), {
  height: 0,
  result: 0,
  type: TypeOptionsChart.DAYS
});

const isHoverElement = ref<boolean>(false);
const userDashboardStore = useUserDashboardStore();
const currentHeightActive = ref<number>(0);

onMounted(() => {
  setTimeout(() => {
    currentHeightActive.value = props.result;
  }, 100);
  drawBar();
});

function drawBar() {
  const colors = ["#5474FF", "#5CD070", "#65D9E8", "#10B9B1"];
  const data = [
    {
      day: props.day,
      all: isNaN(props.height) || !isFinite(props.height) ? 0 : props.height
    }
  ];
  const stack = d3Shape.stack().keys(["all"]);
  const stackedSeries = stack(data);

  // Series
  let g = d3Selection
    .select(`#series-${props.id}`)
    .selectAll("g.series")
    .data(stackedSeries)
    .enter()
    .append("g")
    .classed("series", true)
    .style("fill", (d, i) => colors[i]);

  // Days
  g.selectAll("rect")
    .data((d) => d)
    .join("rect")
    .attr(
      "height",
      isNaN(props.height) || !isFinite(props.height) ? 0 : props.height
    )
    .attr("width", "100%");
}
</script>

<template>
  <div class="relative flex items-end">
    <div class="h-dynamic w-full" :style="{ '--height': `${height}px` }">
      <svg
        v-if="
          userDashboardStore.selectedAdsList.ads.length === 0 &&
          !userDashboardStore.selectedCollection &&
          userDashboardStore.visualTypeChart === 'bar'
        "
        :id="`bar-${props.id}`"
        class="bar absolute bottom-0"
        width="100%"
        :height="
          isNaN(props.height) || !isFinite(props.height) ? 0 : props.height
        "
        style="border-radius: 15px"
        :class="[
          'h-dynamic absolute animate-fade-in transition-colors hover:bg-blue-300 hover:drop-shadow-[0_0px_3px_rgba(65,132,255,1)]'
        ]"
      >
        <g :id="`series-${props.id}`"></g>
      </svg>
      <div
        v-if="
          userDashboardStore.selectedAdsList.ads.length > 0 ||
          userDashboardStore.selectedCollection
        "
        :class="[
          'h-dynamic z-[1000] overflow-hidden bg-gray-400/50 absolute hover:bg-gray-400 group',
          {
            'w-[80%] rounded-3xl': type === TypeOptionsChart.DAYS,
            'w-[95%] left-1 rounded-xl': type === TypeOptionsChart.WEEKS
          }
        ]"
        :style="{ '--height': `${height}px` }"
      >
        <div
          v-if="
            userDashboardStore.selectedAdsList.ads.length > 0 ||
            userDashboardStore.selectedCollection
          "
          :class="[
            'bottom-0 h-dynamic absolute transition-all bg-green-400 w-[100%] rounded-b group-hover:bg-green-200 group-hover:drop-shadow-[0_0px_3px_rgba(65,203,89,1)]'
          ]"
          :style="{ '--height': `${currentHeightActive}px` }"
        ></div>
      </div>
      <div
        v-if="
          userDashboardStore.selectedAdsList.ads.length > 0 ||
          userDashboardStore.selectedCollection
        "
        :class="[
          'h-dynamic overflow-hidden absolute',
          {
            'w-[80%] rounded-3xl': type === TypeOptionsChart.DAYS,
            'w-[95%] left-1 rounded-xl': type === TypeOptionsChart.WEEKS
          }
        ]"
        :style="{ '--height': `${height}px` }"
      >
        <div
          v-if="
            userDashboardStore.selectedAdsList.ads.length > 0 ||
            userDashboardStore.selectedCollection
          "
          :class="[
            'bottom-0 h-dynamic absolute transition-all w-[100%] rounded-b',
            {
              ' drop-shadow-lg': isHoverElement
            }
          ]"
          :style="{ '--height': `${currentHeightActive}px` }"
        ></div>
      </div>
    </div>
  </div>
</template>

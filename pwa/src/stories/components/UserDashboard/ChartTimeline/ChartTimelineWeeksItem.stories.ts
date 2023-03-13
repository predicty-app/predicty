import ChartTimelineWeeksItemComponent from "@/components/UserDashboard/ChartTimeline/ChartTimelineWeeksItem.vue";
import ChartTimelineWeeks from "@/components/UserDashboard/ChartTimeline/ChartTimelineWeeks.vue";
type ComponentPropsType = {};

export default {
  component: ChartTimelineWeeksItemComponent,
  title: "Components/UserDashboard/ChartTimeline/ChartTimelineWeeksItem",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for content weeks item timeline",
      },
    },
  },
};

export const ChartTimelineWeeksItem = (args: ComponentPropsType) => ({
  components: { ChartTimelineWeeksItemComponent, ChartTimelineWeeks },
  setup() {
    return { args };
  },
  template: `
    <ChartTimelineWeeks>
      <ChartTimelineWeeksItemComponent v-bind="args" class="col-start-dynamic col-end-dynamic" :style="{ '--start':  1, '--end': 6 }">
        test
      </ChartTimelineWeeksItemComponent>
    </ChartTimelineWeeks>
  `,
});

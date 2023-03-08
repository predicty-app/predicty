import ChartTimelineWrapperComponent from "@/components/UserDashboard/ChartTimeline/ChartTimelineWrapper.vue";
import ScrollbarPanel from "@/components/Common/ScrollbarPanel.vue";

type ComponentPropsType = {};

export default {
  component: ChartTimelineWrapperComponent,
  title: "Components/UserDashboard/ChartTimeline/ChartTimelineWrapper",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for wrapper to timeline",
      },
    },
  },
};

export const ChartTimelineWrapper = (args: ComponentPropsType) => ({
  components: { ScrollbarPanel, ChartTimelineWrapperComponent },
  setup() {
    return { args };
  },
  template: `
    <ScrollbarPanel class="h-full">
      <ChartTimelineWrapperComponent v-bind="args" />
    </ScrollbarPanel>
  `,
});

import ChartTimelineItemComponent from "@/components/UserDashboard/ChartTimeline/ChartTimelineItem.vue";
import ChartTimelineWrapper from "@/components/UserDashboard/ChartTimeline/ChartTimelineWrapper.vue";

type ComponentPropsType = {};

export default {
  component: ChartTimelineItemComponent,
  title: "Components/UserDashboard/ChartTimeline/ChartTimelineItem",
  argTypes: {
    type: {
      options: ["ad", "collection"],
      control: { type: "select" },
      description: "Type timeline item.",
      name: "type",
      type: { name: "string", required: false },
    },
    start: {
      name: "start",
      control: { type: "number" },
      description: "Props for set start item.",
      type: { name: "number", required: true },
    },
    end: {
      name: "end",
      control: { type: "number" },
      description: "Props for set end item.",
      type: { name: "number", required: true },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for item for timeline",
      },
    },
  },
};

export const ChartTimelineItem = (args: ComponentPropsType) => ({
  components: { ChartTimelineWrapper, ChartTimelineItemComponent },
  setup() {
    return { args };
  },
  template: `
    <ChartTimelineWrapper>
      <ChartTimelineItemComponent v-bind="args">
        Lore ipsum
      </ChartTimelineItemComponent>
    </ChartTimelineWrapper>
  `,
});

ChartTimelineItem.args = {
  start: 6,
  end: 10,
  type: "ad",
};

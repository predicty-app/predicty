import ChartTimelineItemComponent from "@/components/UserDashboard/ChartTimeline/ChartTimelineItem.vue";
import ChartTimelineContent from "@/components/UserDashboard/ChartTimeline/ChartTimelineContent.vue";
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
    color: {
      name: "color",
      control: { type: "string" },
      description: "Props for set color.",
      type: { name: "string", required: false },
    },
    isVisible: {
      name: "isVisible",
      control: { type: "boolean" },
      description: "Props for set is visible element.",
      type: { name: "boolean", required: false },
    },
    campaingUid: {
      name: "campaingUid",
      control: { type: "string" },
      description: "Props for set campaign uid",
      type: { name: "string", required: false },
    },
    element: {
      name: "element",
      control: { type: "object" },
      description: "Props for collection or ad",
      type: { name: "object", required: true },
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
  components: { ChartTimelineWrapper, ChartTimelineItemComponent, ChartTimelineContent },
  setup() {
    const campaign = { 
      uid: '1',
      name: 'Test',
      ads: [],
      collection: []
    }

    return { args, campaign };
  },
  template: `
    <ChartTimelineWrapper>
      <ChartTimelineContent :campaign="campaign">
        <ChartTimelineItemComponent v-bind="args">
          Lore ipsum
        </ChartTimelineItemComponent>
      </ChartTimelineContent>
    </ChartTimelineWrapper>
  `,
});

ChartTimelineItem.args = {
  start: 1,
  end: 5,
  type: "ad",
  element: {
    uid: '1',
    name: 'test',
    start: '2023-12-12',
    end: '2023-12-12',
    creation: '',
    cost_total: 1,
    cost_per_day: 1
  }
};

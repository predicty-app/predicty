import ChartTimelineContentComponent from "@/components/UserDashboard/ChartTimeline/ChartTimelineContent.vue";
type ComponentPropsType = {};

export default {
  component: ChartTimelineContentComponent,
  title: "Components/UserDashboard/ChartTimeline/ChartTimelineContent",
  argTypes: {
    campaign: {
      description: "Campaign type",
      name: "campaign",
      type: { name: "object", required: true },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for content timeline",
      },
    },
  },
};

export const ChartTimelineContent = (args: ComponentPropsType) => ({
  components: { ChartTimelineContentComponent },
  setup() {
    return { args };
  },
  template: `
    <ChartTimelineContentComponent v-bind="args" />
  `,
});

ChartTimelineContent.args = {
  campaign: {
    uid: "1",
    name: "Test",
    ads: [],
    collection: [],
  },
};

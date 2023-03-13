import ChartTimelineWeeksComponent from "@/components/UserDashboard/ChartTimeline/ChartTimelineWeeks.vue";
type ComponentPropsType = {};

export default {
  component: ChartTimelineWeeksComponent,
  title: "Components/UserDashboard/ChartTimeline/ChartTimelineWeeks",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for content weeks timeline"
      }
    }
  }
};

export const ChartTimelineWeeks = (args: ComponentPropsType) => ({
  components: { ChartTimelineWeeksComponent },
  setup() {
    return { args };
  },
  template: `
    <ChartTimelineWeeksComponent v-bind="args" />
  `
});

import BarChartPoleContentComponent from "@/components/UserDashboard/BarChart/BarChartPoleContent.vue";

type ComponentPropsType = {};

export default {
  component: BarChartPoleContentComponent,
  title: "Components/UserDashboard/BarChart/BarChartPoleContent",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for bar pole chart content",
      },
    },
  },
};

export const BarChartPoleContent = (args: ComponentPropsType) => ({
  components: { BarChartPoleContentComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartPoleContentComponent v-bind="args" />
  `,
});

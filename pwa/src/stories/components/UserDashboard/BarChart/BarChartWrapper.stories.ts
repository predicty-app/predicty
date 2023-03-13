import BarChartWrapperComponent from "@/components/UserDashboard/BarChart/BarChartWrapper.vue";

type ComponentPropsType = {};

export default {
  component: BarChartWrapperComponent,
  title: "Components/UserDashboard/BarChart/BarChartWrapper",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for bar chart"
      }
    }
  }
};

export const BarChartWrapper = (args: ComponentPropsType) => ({
  components: { BarChartWrapperComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartWrapperComponent v-bind="args" />
  `
});

import BarChartPoleWrapperComponent from "@/components/UserDashboard/BarChart/BarChartPoleWrapper.vue";

type ComponentPropsType = {};

export default {
  component: BarChartPoleWrapperComponent,
  title: "Components/UserDashboard/BarChart/BarChartPoleWrapper",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for bar pole chart wrapper"
      }
    }
  }
};

export const BarChartPoleWrapper = (args: ComponentPropsType) => ({
  components: { BarChartPoleWrapperComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartPoleWrapperComponent v-bind="args" />
  `
});

import BarChartWeeksComponent from "@/components/UserDashboard/BarChart/BarChartWeeks.vue";

type ComponentPropsType = {};

export default {
  component: BarChartWeeksComponent,
  title: "Components/UserDashboard/BarChart/BarChartWeeks",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for bar pole chart weeks"
      }
    }
  }
};

export const BarChartWeeks = (args: ComponentPropsType) => ({
  components: { BarChartWeeksComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartWeeksComponent v-bind="args" />
  `
});

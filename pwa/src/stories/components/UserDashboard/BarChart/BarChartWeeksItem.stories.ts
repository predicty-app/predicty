import BarChartWeeksItemComponent from "@/components/UserDashboard/BarChart/BarChartWeeksItem.vue";

type ComponentPropsType = {};

export default {
  component: BarChartWeeksItemComponent,
  title: "Components/UserDashboard/BarChart/BarChartWeeksItem",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for bar pole chart weeks item"
      }
    }
  }
};

export const BarChartWeeksItem = (args: ComponentPropsType) => ({
  components: { BarChartWeeksItemComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartWeeksItemComponent v-bind="args" />
  `
});

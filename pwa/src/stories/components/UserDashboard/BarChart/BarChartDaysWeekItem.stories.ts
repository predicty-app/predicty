import BarChartDaysWeekItemComponent from "@/components/UserDashboard/BarChart/BarChartDaysWeekItem.vue";

type ComponentPropsType = {};

export default {
  component: BarChartDaysWeekItemComponent,
  title: "Components/UserDashboard/BarChart/BarChartDaysWeekItem",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for bar pole chart day week",
      },
    },
  },
};

export const BarChartDaysWeekItem = (args: ComponentPropsType) => ({
  components: { BarChartDaysWeekItemComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartDaysWeekItemComponent v-bind="args" />
  `,
});

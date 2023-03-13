import BarChartDaysWeekComponent from "@/components/UserDashboard/BarChart/BarChartDaysWeek.vue";

type ComponentPropsType = {};

export default {
  component: BarChartDaysWeekComponent,
  title: "Components/UserDashboard/BarChart/BarChartDaysWeek",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for bar pole chart days week",
      },
    },
  },
};

export const BarChartDaysWeek = (args: ComponentPropsType) => ({
  components: { BarChartDaysWeekComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartDaysWeekComponent v-bind="args" />
  `,
});

BarChartDaysWeek.args = {
  height: 450,
};

import BarChartPoleComponent from "@/components/UserDashboard/BarChart/BarChartPole.vue";

type ComponentPropsType = {};

export default {
  component: BarChartPoleComponent,
  title: "Components/UserDashboard/BarChart/BarChartPole",
  argTypes: {
    height: {
      name: "height",
      description: "Height of bar.",
      control: { type: "number" },
      type: { name: "number", required: false },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for bar pole chart",
      },
    },
  },
};

export const BarChartPole = (args: ComponentPropsType) => ({
  components: { BarChartPoleComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartPoleComponent v-bind="args" />
  `,
});

BarChartPole.args = {
  height: 450,
};

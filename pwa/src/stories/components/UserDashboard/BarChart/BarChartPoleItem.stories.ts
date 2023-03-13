import BarChartPoleItemComponent from "@/components/UserDashboard/BarChart/BarChartPoleItem.vue";

type ComponentPropsType = {};

export default {
  component: BarChartPoleItemComponent,
  title: "Components/UserDashboard/BarChart/BarChartPoleItem",
  argTypes: {
    height: {
      name: "height",
      description: "Height of bar.",
      control: { type: "number" },
      type: { name: "number", required: false }
    },
    date: {
      name: "date",
      description: "Date for element.",
      control: { type: "string" },
      type: { name: "string", required: false }
    },
    day: {
      name: "day",
      description: "Day for element.",
      control: { type: "string" },
      type: { name: "string", required: false }
    },
    sales: {
      name: "sales",
      description: "Sales for element.",
      control: { type: "number" },
      type: { name: "number", required: false }
    },
    investment: {
      name: "investment",
      description: "Investment for element.",
      control: { type: "number" },
      type: { name: "number", required: false }
    }
  },
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for bar pole chart item"
      }
    }
  }
};

export const BarChartPoleItem = (args: ComponentPropsType) => ({
  components: { BarChartPoleItemComponent },
  setup() {
    return { args };
  },
  template: `
    <BarChartPoleItemComponent v-bind="args" />
  `
});

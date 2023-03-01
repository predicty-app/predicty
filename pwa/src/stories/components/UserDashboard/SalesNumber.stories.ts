import SalesNumberComponent from "@/components/UserDashboard/SalesNumber.vue";

type ComponentPropsType = {};

export default {
  component: SalesNumberComponent,
  title: "Components/UserDashboard/SalesNumber",
  argTypes: {
    date: {
      description: "Date for tooltip",
      name: "date",
      type: { name: "string", required: false },
    },
    sales: {
      description: "Sales for tooltip",
      name: "sales",
      type: { name: "string", required: false },
    },
    investment: {
      description: "Investment for tooltip",
      name: "investment",
      type: { name: "string", required: false },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for floating switch view form",
      },
    },
  },
};

export const SalesNumber = (args: ComponentPropsType) => ({
  components: { SalesNumberComponent },
  setup() {
    return { args };
  },
  template: `
    <div class="h-full relative">
      <SalesNumberComponent v-bind="args" />
    </div>
  `,
});

SalesNumber.args = {
  date: "2023.03.01",
  sales: "$5,345",
  investment: "$345",
}

import HeaderDashboardComponent from "@/components/UserDashboard/HeaderDashboard.vue";

type ComponentPropsType = {};

export default {
  component: HeaderDashboardComponent,
  title: "Components/UserDashboard/HeaderDashboard",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for header dashboard",
      },
    },
  },
};

export const HeaderDashboard = (args: ComponentPropsType) => ({
  components: { HeaderDashboardComponent },
  setup() {
    return { args };
  },
  template: `
    <HeaderDashboardComponent v-bind="args" />
  `,
});

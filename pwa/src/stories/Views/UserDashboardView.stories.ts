import UserDashboardViewComponent from "@/views/UserDashboardView.vue";

type ComponentPropsType = {};

export default {
  component: UserDashboardViewComponent,
  title: "Views/UserDashboardView",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component start screen view",
      },
    },
  },
};

export const UserDashboardView = (args: ComponentPropsType) => ({
  components: { UserDashboardViewComponent },
  setup() {
    return { args };
  },
  template: `
    <UserDashboardViewComponent v-bind="args" />
  `,
});

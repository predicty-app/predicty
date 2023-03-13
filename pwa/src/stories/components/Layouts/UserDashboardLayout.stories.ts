import UserDashboardLayoutComponent from "@/components/Layouts/UserDashboardLayout.vue";

type ComponentPropsType = {};

export default {
  component: UserDashboardLayoutComponent,
  title: "Components/Layouts/UserDashboardLayout",
  parameters: {
    status: {
      type: "todo"
    },
    docs: {
      description: {
        component: "Component for on user dashboard layout"
      }
    }
  }
};

export const UserDashboardLayout = (args: ComponentPropsType) => ({
  components: { UserDashboardLayoutComponent },
  setup() {
    return { args };
  },
  template: `
    <UserDashboardLayoutComponent v-bind="args">
      <template #header>header</template>
      <template #chart-legend>chart-legend</template>
      <template #chart>chart</template>
      <template #ads-campaigns>ads-campaigns</template>
      <template #ads-chart>ads-chart</template>
    </UserDashboardLayoutComponent>
  `
});

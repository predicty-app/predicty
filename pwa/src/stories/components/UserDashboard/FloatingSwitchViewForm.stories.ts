import FloatingSwitchViewFormComponent from "@/components/UserDashboard/FloatingSwitchViewForm.vue";

type ComponentPropsType = {};

export default {
  component: FloatingSwitchViewFormComponent,
  title: "Components/UserDashboard/FloatingSwitchViewForm",
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

export const FloatingSwitchViewForm = (args: ComponentPropsType) => ({
  components: { FloatingSwitchViewFormComponent },
  setup() {
    return { args };
  },
  template: `
    <div class="h-full relative">
      <FloatingSwitchViewFormComponent v-bind="args" />
    </div>
  `,
});

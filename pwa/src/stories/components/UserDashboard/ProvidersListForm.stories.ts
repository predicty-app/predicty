import ProvidersListFormComponent from "@/components/UserDashboard/ProvidersListForm.vue";

type ComponentPropsType = {};

export default {
  component: ProvidersListFormComponent,
  title: "Components/UserDashboard/ProvidersListForm",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for show providers list."
      }
    }
  }
};

export const ProvidersListForm = (args: ComponentPropsType) => ({
  components: { ProvidersListFormComponent },
  setup() {
    return { args };
  },
  template: `<ProvidersListFormComponent v-bind="args" />`
});

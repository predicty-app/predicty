import MoreMediaIntegrationViewComponent from "@/views/MoreMediaIntegrationView.vue";

type ComponentPropsType = {};

export default {
  component: MoreMediaIntegrationViewComponent,
  title: "Views/MoreMediaIntegrationView",
  parameters: {
    status: {
      type: "todo"
    },
    docs: {
      description: {
        component: "Component more media integration view"
      }
    }
  }
};

export const MoreMediaIntegrationView = (args: ComponentPropsType) => ({
  components: { MoreMediaIntegrationViewComponent },
  setup() {
    return { args };
  },
  template: `
    <MoreMediaIntegrationViewComponent v-bind="args" />
  `
});

import BasicMediaIntegrationViewComponent from "@/views/BasicMediaIntegrationView.vue";

type ComponentPropsType = {};

export default {
  component: BasicMediaIntegrationViewComponent,
  title: "Views/BasicMediaIntegrationView",
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component basic media integration view",
      },
    },
  },
};

export const BasicMediaIntegrationView = (args: ComponentPropsType) => ({
  components: { BasicMediaIntegrationViewComponent },
  setup() {
    return { args };
  },
  template: `
    <BasicMediaIntegrationViewComponent v-bind="args" />
  `,
});

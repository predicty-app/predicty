import StartScreenViewComponent from "@/views/StartScreenView.vue";

type ComponentPropsType = {};

export default {
  component: StartScreenViewComponent,
  title: "Views/StartScreenView",
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

export const StartScreenView = (args: ComponentPropsType) => ({
  components: { StartScreenViewComponent },
  setup() {
    return { args };
  },
  template: `
    <StartScreenViewComponent v-bind="args" />
  `,
});

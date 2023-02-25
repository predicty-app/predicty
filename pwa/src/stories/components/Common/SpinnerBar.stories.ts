import SpinnerBarComponent from "@/components/Common/SpinnerBar.vue";

type ComponentPropsType = {};

export default {
  component: SpinnerBarComponent,
  title: "Components/Common/SpinnerBar",
  argTypes: {
    isVisible: {
      name: "isVisible",
      description: "Param for toogle visible spinner.",
      type: { name: "boolean", required: true },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for spinner bar",
      },
    },
  },
};

export const SpinnerBar = (args: ComponentPropsType) => ({
  components: { SpinnerBarComponent },
  setup() {
    return { args };
  },
  template: `
    <SpinnerBarComponent v-bind="args" />
  `,
});

SpinnerBar.args = {
  isVisible: true,
};

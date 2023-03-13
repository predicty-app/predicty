import SpinnerBarComponent from "@/components/Common/SpinnerBar.vue";

type ComponentPropsType = {};

export default {
  component: SpinnerBarComponent,
  title: "Components/Common/SpinnerBar",
  argTypes: {
    isVisible: {
      name: "isVisible",
      description: "Param for toogle visible spinner.",
      type: { name: "boolean", required: false },
    },
    isGlobal: {
      name: "isGlobal",
      description: "Param for toogle global spinner.",
      type: { name: "boolean", required: false },
    },
  },
  parameters: {
    jest: ["SpinnerBar.spec.ts"],
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

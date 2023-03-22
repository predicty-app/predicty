import ProgressBarComponent from "@/components/Common/ProgressBar.vue";

type ComponentPropsType = {};

export default {
  component: ProgressBarComponent,
  title: "Components/Common/ProgressBar",
  argTypes: {
    countSteps: {
      name: "countSteps",
      control: { type: "number" },
      description: "Count of current step for progress bar",
      type: { name: "number", required: true }
    },
    activeStep: {
      name: "activeStep",
      control: { type: "number" },
      description: "Current active step in progress bar",
      type: { name: "number", required: false }
    }
  },
  parameters: {
    jest: ["ProgressBar.spec.ts"],
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for progress bar"
      }
    }
  }
};

export const ProgressBar = (args: ComponentPropsType) => ({
  components: { ProgressBarComponent },
  setup() {
    return { args };
  },
  template: `
    <ProgressBarComponent v-bind="args" />
  `
});

ProgressBar.args = {
  countSteps: 5,
  activeStep: 3
};

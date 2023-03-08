import ConnectAccountFormComponent from "@/components/OnBoarding/ConnectAccountForm.vue";

type ComponentPropsType = {};

export default {
  component: ConnectAccountFormComponent,
  title: "Components/OnBoarding/ConnectAccountForm",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for providers list",
      },
    },
  },
};

export const ConnectAccountForm = (args: ComponentPropsType) => ({
  components: { ConnectAccountFormComponent },
  setup() {
    return { args };
  },
  template: `
    <div class="h-full bg-layout-onboarding-border">
      <ConnectAccountFormComponent v-bind="args" />
    </div>
    <div id="next-button"></div>
  `,
});

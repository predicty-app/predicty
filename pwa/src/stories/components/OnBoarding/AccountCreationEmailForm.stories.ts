import AccountCreationEmailFormComponent from "@/components/OnBoarding/AccountCreationEmailForm.vue";

type ComponentPropsType = {};

export default {
  component: AccountCreationEmailFormComponent,
  title: "Components/OnBoarding/AccountCreationEmailForm",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for account creation form",
      },
    },
  },
};

export const AccountCreationEmailForm = (args: ComponentPropsType) => ({
  components: { AccountCreationEmailFormComponent },
  setup() {
    return { args };
  },
  template: `
    <AccountCreationEmailFormComponent v-bind="args" />
  `,
});

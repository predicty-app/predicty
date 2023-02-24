import AccountCreationPaswordComponent from '@/components/OnBoarding/AccountCreationPasswordForm.vue'

type ComponentPropsType = {}

export default {
  component: AccountCreationPaswordComponent,
  title: 'Components/OnBoarding/AccountCreationPasword',
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for account creation form',
      },
    },
  },
}

export const AccountCreationPasword = (args: ComponentPropsType) => ({
  components: { AccountCreationPaswordComponent },
  setup() {
    return { args }
  },
  template: `
    <AccountCreationPaswordComponent v-bind="args" />
  `,
})

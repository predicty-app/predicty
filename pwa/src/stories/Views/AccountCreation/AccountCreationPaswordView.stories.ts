import AccountCreationPaswordViewComponent from '@/views/AccountCreation/AccountCreationPasswordView.vue'

type ComponentPropsType = {}

export default {
  component: AccountCreationPaswordViewComponent,
  title: 'Views/AccountCreation/AccountCreationPaswordView',
  parameters: {
    status: {
      type: 'todo',
    },
    docs: {
      description: {
        component: 'Component account creation view',
      },
    },
  },
}

export const AccountCreationPaswordView = (args: ComponentPropsType) => ({
  components: { AccountCreationPaswordViewComponent },
  setup() {
    return { args }
  },
  template: `
    <AccountCreationPaswordViewComponent v-bind="args" />
  `,
})

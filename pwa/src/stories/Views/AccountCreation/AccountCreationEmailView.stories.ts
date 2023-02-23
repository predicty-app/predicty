import AccountCreationEmailViewComponent from '@/views/AccountCreation/AccountCreationEmailView.vue'

type ComponentPropsType = {}

export default {
  component: AccountCreationEmailViewComponent,
  title: 'Views/AccountCreation/AccountCreationEmailView',
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

export const AccountCreationEmailView = (args: ComponentPropsType) => ({
  components: { AccountCreationEmailViewComponent },
  setup() {
    return { args }
  },
  template: `
    <AccountCreationEmailViewComponent v-bind="args" />
  `,
})

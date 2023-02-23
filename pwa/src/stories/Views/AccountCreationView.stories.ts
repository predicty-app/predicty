import AccountCreationViewComponent from '@/views/AccountCreationView.vue'

type ComponentPropsType = {}

export default {
  component: AccountCreationViewComponent,
  title: 'Views/AccountCreationView',
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

export const AccountCreationView = (args: ComponentPropsType) => ({
  components: { AccountCreationViewComponent },
  setup() {
    return { args }
  },
  template: `
    <AccountCreationViewComponent v-bind="args" />
  `,
})

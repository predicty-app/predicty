import AppLogoComponent from '@/components/Common/AppLogo.vue'

type ComponentPropsType = {}

export default {
  component: AppLogoComponent,
  title: 'Components/Common/AppLogo',
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component main logo application',
      },
    },
  },
}

export const AppLogo = (args: ComponentPropsType) => ({
  components: { AppLogoComponent },
  setup() {
    return { args }
  },
  template: `
    <AppLogoComponent v-bind="args" />
  `,
})

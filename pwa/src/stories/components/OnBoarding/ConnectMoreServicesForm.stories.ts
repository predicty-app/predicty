import ConnectMoreServicesFormComponent from '@/components/OnBoarding/ConnectMoreServicesForm.vue'

type ComponentPropsType = {}

export default {
  component: ConnectMoreServicesFormComponent,
  title: 'Components/OnBoarding/ConnectMoreServicesForm',
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for connect more services form',
      },
    },
  },
}

export const ConnectMoreServicesForm = (args: ComponentPropsType) => ({
  components: { ConnectMoreServicesFormComponent },
  setup() {
    return { args }
  },
  template: `
    <ConnectMoreServicesFormComponent v-bind="args" />
  `,
})

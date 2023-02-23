import OnBoardingLayoutComponent from '@/components/Layouts/OnBoardingLayout.vue'

type ComponentPropsType = {}

export default {
  component: OnBoardingLayoutComponent,
  title: 'Components/Layouts/OnBoardingLayout',
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for on boarding layout',
      },
    },
  },
}

export const OnBoardingLayout = (args: ComponentPropsType) => ({
  components: { OnBoardingLayoutComponent },
  setup() {
    return { args }
  },
  template: `
    <OnBoardingLayoutComponent v-bind="args">
      <template #header>
        Header
      </template>
      <template #content>
        Content
      </template>
      <template #footer>
        Footer
      </template>
    </OnBoardingLayoutComponent>
  `,
})

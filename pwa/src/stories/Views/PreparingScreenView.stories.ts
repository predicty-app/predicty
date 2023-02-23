import PreparingScreenViewComponent from '@/views/PreparingScreenView.vue'

type ComponentPropsType = {}

export default {
  component: PreparingScreenViewComponent,
  title: 'Views/PreparingScreenView',
  parameters: {
    status: {
      type: 'todo',
    },
    docs: {
      description: {
        component: 'Component preparing screen view',
      },
    },
  },
}

export const PreparingScreenView = (args: ComponentPropsType) => ({
  components: { PreparingScreenViewComponent },
  setup() {
    return { args }
  },
  template: `
    <PreparingScreenViewComponent v-bind="args" />
  `,
})

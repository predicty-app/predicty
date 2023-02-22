import TagPinComponent from '@/components/Common/TagPin.vue'

type ComponentPropsType = {}

export default {
  component: TagPinComponent,
  title: 'Common/TagPin',
  argTypes: {
    type: {
      options: ['default', 'success', 'primary'],
      control: { type: 'select' },
      description: 'Tag type.',
      defaultValue: 'primary',
      name: 'type',
      type: { name: 'string', required: false },
    },
  },
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for tag pin',
      },
    },
  },
}

export const TagPin = (args: ComponentPropsType) => ({
  components: { TagPinComponent },
  setup() {
    return { args }
  },
  template: `
    <TagPinComponent v-bind="args">Lorem ipsum</TagPinComponent>
  `,
})

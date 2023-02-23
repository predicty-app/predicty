import IconSvgComponent from '@/components/Common/IconSvg.vue'

type ComponentPropsType = {}

export default {
  component: IconSvgComponent,
  title: 'Components/Common/IconSvg',
  argTypes: {
    name: {
      name: 'name',
      options: ['search'],
      control: { type: 'select' },
      description: 'Icon name.',
      type: { name: 'string', required: true },
    }
  },
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for icon svg',
      },
    },
  },
}

export const IconSvg = (args: ComponentPropsType) => ({
  components: { IconSvgComponent },
  setup() {
    return { args }
  },
  template: `
    <IconSvgComponent v-bind="args" />
  `,
})

IconSvg.args = {
  name: 'search'
}
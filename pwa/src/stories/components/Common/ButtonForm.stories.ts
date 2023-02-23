import ButtonFormComponent from '@/components/Common/ButtonForm.vue'

type ComponentPropsType = {}

export default {
  component: ButtonFormComponent,
  title: 'Components/Common/ButtonForm',
  argTypes: {
    type: {
      options: ['default', 'success'],
      control: { type: 'select' },
      description: 'Button type.',
      defaultValue: 'primary',
      name: 'type',
      type: { name: 'string', required: false },
    }
  },
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for button form',
      },
    },
  },
}

export const ButtonForm = (args: ComponentPropsType) => ({
  components: { ButtonFormComponent },
  setup() {
    return { args }
  },
  template: `
    <ButtonFormComponent v-bind="args" >Lorem ipsum</ButtonFormComponent>
  `,
})

ButtonForm.args = {
  type: 'default'
}

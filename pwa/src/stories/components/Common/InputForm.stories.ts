import InputFormComponent from '@/components/Common/InputForm.vue'

type ComponentPropsType = {}

export default {
  component: InputFormComponent,
  title: 'Components/Common/InputForm',
  argTypes: {
    type: {
      options: ['default'],
      control: { type: 'select' },
      description: 'Input type.',
      defaultValue: 'primary',
      name: 'type',
      type: { name: 'string', required: false },
    },
    placeholder: {
      name: 'placeholder',
      description: 'Placeholder for input element.',
      type: { name: 'string', required: false },
    },
    errorMessage: {
      name: 'errorMessage',
      description: 'Error message when validation is false.',
      type: { name: 'string', required: false },
    },
    label: {
      name: 'label',
      description: 'Label for input element.',
      type: { name: 'string', required: false },
    },
    required: {
      name: 'required',
      control: { type: 'boolean' },
      description: 'Set element is required.',
      type: { name: 'boolean', required: false },
    }
  },
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for input element',
      },
    },
  },
}

export const InputForm = (args: ComponentPropsType) => ({
  components: { InputFormComponent },
  setup() {
    return { args }
  },
  template: `
    <InputFormComponent v-bind="args" />
  `,
})

InputForm.args = {
  label: 'Lorem Ipsum:',
  type: 'default',
  placeholder: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
  errorMessage: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...'
}
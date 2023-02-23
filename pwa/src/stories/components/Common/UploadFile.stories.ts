import UploadFileComponent from '@/components/Common/UploadFile.vue'

type ComponentPropsType = {}

export default {
  component: UploadFileComponent,
  title: 'Components/Common/UploadFile',
  parameters: {
    status: {
      type: 'todo',
    },
    docs: {
      description: {
        component: 'Component for upload file',
      },
    },
  },
}

export const UploadFile = (args: ComponentPropsType) => ({
  components: { UploadFileComponent },
  setup() {
    return { args }
  },
  template: `
    <UploadFileComponent v-bind="args" />
  `,
})

UploadFile.args = {
  filesType: ['.csv', '.xls']
}

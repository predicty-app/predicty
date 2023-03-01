import FloatingPanelComponent from "@/components/Common/FloatingPanel.vue";

type ComponentPropsType = {};

export default {
  component: FloatingPanelComponent,
  title: "Components/Common/FloatingPanel",
  argTypes: {
    options: {
      name: 'options',
      description: 'Options for floating panel.',
      control: { type: 'array' },
      type: { name: 'string', required: true },
    },
    activeKey: {
      name: 'activeKey',
      description: 'Witch button actual is active.',
      type: { name: 'string', required: false },
    },
  },
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component for floating panel",
      },
    },
  },
};

export const FloatingPanel = (args: ComponentPropsType) => ({
  components: { FloatingPanelComponent },
  setup() {
    return { args };
  },
  template: `
    <FloatingPanelComponent v-bind="args" />
  `,
});

FloatingPanel.args = {
  options: [{
    key: '1',
    label: 'Lorem ipsum 1'
  }, {
    key: '2',
    label: 'Lorem ipsum 2'
  }],
  activeKey: '2'
}

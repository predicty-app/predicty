import FloatingPanelComponent from "@/components/Common/FloatingPanel.vue";

type ComponentPropsType = {};

export default {
  component: FloatingPanelComponent,
  title: "Components/Common/FloatingPanel",
  argTypes: {
    options: {
      name: "options",
      description: "Options for floating panel.",
      control: { type: "array" },
      type: { name: "string", required: true }
    },
    selectedElements: {
      name: "selectedElements",
      description: "Count of selected elements.",
      type: { name: "number", required: false }
    }
  },
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for floating panel"
      }
    }
  }
};

export const FloatingPanel = (args: ComponentPropsType) => ({
  components: { FloatingPanelComponent },
  setup() {
    return { args };
  },
  template: `
    <FloatingPanelComponent v-bind="args" />
  `
});

FloatingPanel.args = {
  options: [
    {
      key: "1",
      label: "Lorem ipsum 1"
    },
    {
      key: "2",
      label: "Lorem ipsum 2"
    }
  ],
  selectedElements: 5
};

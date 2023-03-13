import DividerLineComponent from "@/components/Common/DividerLine.vue";

type ComponentPropsType = {};

export default {
  component: DividerLineComponent,
  title: "Components/Common/DividerLine",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for divider line"
      }
    }
  }
};

export const DividerLine = (args: ComponentPropsType) => ({
  components: { DividerLineComponent },
  setup() {
    return { args };
  },
  template: `
    <br/>
    <DividerLineComponent v-bind="args" />
  `
});

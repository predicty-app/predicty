import HeaderTextComponent from "@/components/Common/HeaderText.vue";

type ComponentPropsType = {};

export default {
  component: HeaderTextComponent,
  title: "Components/Common/HeaderText",
  argTypes: {
    headerTitle: {
      description: "Props for show title header.",
      name: "headerTitle",
      type: { name: "string", required: false },
    },
    headerDescription: {
      description: "Props for show description header.",
      name: "headerDescription",
      type: { name: "string", required: false },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component header text",
      },
    },
  },
};

export const HeaderText = (args: ComponentPropsType) => ({
  components: { HeaderTextComponent },
  setup() {
    return { args };
  },
  template: `
    <HeaderTextComponent v-bind="args" />
  `,
});

HeaderText.args = {
  headerTitle: "Lorem ipsum dolor sit amet",
  headerDescription:
    "orem ipsum dolor sit amet, consectetur adipiscing elit. Sed vehicula, lorem vel efficitur aliquam, sem nisl euismod dolor, ac luctus dolor dolor mattis purus. Fusce placerat, dui ut luctus mattis, ",
};

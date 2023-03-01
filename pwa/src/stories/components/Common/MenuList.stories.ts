import MenuListComponent from "@/components/Common/MenuList.vue";

type ComponentPropsType = {};

export default {
  component: MenuListComponent,
  title: "Components/Common/MenuList",
  argTypes: {
    options: {
      name: "options",
      description: "Options for menu list.",
      control: { type: "array" },
      type: { name: "string", required: true },
    },
  },
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component for menu list",
      },
    },
  },
};

export const MenuList = (args: ComponentPropsType) => ({
  components: { MenuListComponent },
  setup() {
    return { args };
  },
  template: `
    <MenuListComponent v-bind="args" />
  `,
});

MenuList.args = {
  options: [
    {
      key: "1",
      icon: "cogs",
      label: "Settings 1",
    },
    {
      key: "2",
      icon: "logout",
      label: "Settings 2",
      color: "#E24963",
    },
  ],
};

import ButtonFormComponent from "@/components/Common/ButtonForm.vue";

type ComponentPropsType = {};

export default {
  component: ButtonFormComponent,
  title: "Components/Common/ButtonForm",
  argTypes: {
    type: {
      options: ["default", "success", "disabled"],
      control: { type: "select" },
      description: "Button type.",
      name: "type",
      type: { name: "string", required: false },
    },
  },
  parameters: {
    jest: ["ButtonForm.spec.ts"],
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for button form",
      },
    },
  },
};

export const ButtonForm = (args: ComponentPropsType) => ({
  components: { ButtonFormComponent },
  setup() {
    return { args };
  },
  template: `
    <ButtonFormComponent v-bind="args" >Lorem ipsum</ButtonFormComponent>
  `,
});

ButtonForm.args = {
  type: "default",
};

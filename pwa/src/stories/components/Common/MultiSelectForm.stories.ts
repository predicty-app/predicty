import { ref } from "vue";
import MultiSelectFormComponent from "@/components/Common/MultiSelectForm.vue";

type ComponentPropsType = {};

export default {
  component: MultiSelectFormComponent,
  title: "Components/Common/MultiSelectForm",
  argTypes: {
    options: {
      name: "options",
      description: "Options for multiselect.",
      control: { type: "array" },
      type: { name: "string", required: true }
    },
    position: {
      options: ["top", "bottom"],
      control: { type: "select" },
      description: "Overflow position type.",
      name: "position",
      type: { name: "string", required: false }
    },
    minOptions: {
      description: "Min options must leave selected",
      name: "minOptions",
      type: { name: "number", required: false }
    }
  },
  parameters: {
    status: {
      type: "todo"
    },
    docs: {
      description: {
        component: "Component for multiselect list"
      }
    }
  }
};

export const MultiSelectForm = (args: ComponentPropsType) => ({
  components: { MultiSelectFormComponent },
  setup() {
    const model = ref<string[]>([]);
    return { args, model };
  },
  template: `
    <MultiSelectFormComponent v-bind="args" v-model="model" />
  `
});

MultiSelectForm.args = {
  label: "Lorem Ipsum:",
  options: [
    {
      key: "test",
      label: "Option1",
      icon: "/public/assets/images/providers/google-ads-provider.png"
    },
    {
      key: "test2",
      label: "Option2"
    },
    {
      key: "test3",
      label: "Option3"
    },
    {
      key: "test4",
      label: "Option4"
    }
  ],
  minOptions: 1,
  placeholder: "Lorem ipsum dolor sit amet, consectetur adipiscing elit..."
};

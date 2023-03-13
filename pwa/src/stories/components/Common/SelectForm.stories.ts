import { ref } from "vue";
import SelectFormComponent from "@/components/Common/SelectForm.vue";

type ComponentPropsType = {};

export default {
  component: SelectFormComponent,
  title: "Components/Common/SelectForm",
  argTypes: {
    options: {
      control: { type: "array" },
      description: "Select options.",
      name: "options",
      type: { name: "string", required: true }
    },
    position: {
      options: ["top", "bottom"],
      control: { type: "select" },
      description: "Overflow position type.",
      name: "position",
      type: { name: "string", required: false }
    },
    placeholder: {
      description: "Placeholder for select element.",
      name: "placeholder",
      type: { name: "string", required: false }
    }
  },
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component select form"
      }
    }
  }
};

export const SelectForm = (args: ComponentPropsType) => ({
  components: { SelectFormComponent },
  setup() {
    const model = ref<string>("");
    return { args, model };
  },
  template: `
    <SelectFormComponent v-bind="args" v-model="model"/>
  `
});

SelectForm.args = {
  options: [
    {
      label: "Test 1",
      key: "1"
    },
    {
      label: "Test 2",
      key: "2"
    }
  ],
  position: "bottom",
  placeholder: "Select element..."
};

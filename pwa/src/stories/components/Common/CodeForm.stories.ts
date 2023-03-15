import { ref } from "vue";
import CodeFormComponent from "@/components/Common/CodeForm.vue";

type ComponentPropsType = {};

export default {
  component: CodeFormComponent,
  title: "Components/Common/CodeForm",
  argTypes: {
    errorMessage: {
      name: "errorMessage",
      description: "Error message when validation is false.",
      type: { name: "string", required: false }
    },
    label: {
      name: "label",
      description: "Label for code element.",
      type: { name: "string", required: false }
    },
    required: {
      name: "required",
      control: { type: "boolean" },
      description: "Set element is required.",
      type: { name: "boolean", required: false }
    }
  },
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for code element"
      }
    }
  }
};

export const CodeForm = (args: ComponentPropsType) => ({
  components: { CodeFormComponent },
  setup() {
    const model = ref<string>("");
    return { args, model };
  },
  template: `
   {{ model }}
    <CodeFormComponent v-bind="args" v-model="model"/>
  `
});

CodeForm.args = {
  label: "Lorem Ipsum:",
  errorMessage: "Lorem ipsum dolor sit amet, consectetur adipiscing elit..."
};

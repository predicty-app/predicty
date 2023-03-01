import { ref } from 'vue'
import CheckboxFormComponent from "@/components/Common/CheckboxForm.vue";

type ComponentPropsType = {};

export default {
  component: CheckboxFormComponent,
  title: "Components/Common/CheckboxForm",
  argTypes: {
    isChecked: {
      control: { type: "boolean" },
      description: "State of checkbox.",
      name: "isChecked",
      type: { name: "boolean", required: false },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component checkbox form.",
      },
    },
  },
};

export const CheckboxForm = (args: ComponentPropsType) => ({
  components: { CheckboxFormComponent },
  setup() {
    const state = ref<boolean>(false)

    return { args, state };
  },
  template: `
    <div class="h-full p-10" style="background:#56ce6b">
      <CheckboxFormComponent v-bind="args" :is-checked="state" @on-change="(value) => state = value"/>
    </div>
  `,
});

CheckboxForm.args = {
  isChecked: false
}

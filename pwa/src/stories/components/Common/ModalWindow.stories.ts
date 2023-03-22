import { ref } from "vue";
import ModalWindowComponent from "@/components/Common/ModalWindow.vue";

type ComponentPropsType = {};

export default {
  component: ModalWindowComponent,
  title: "Components/Common/ModalWindow",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for modal window"
      }
    }
  }
};

export const ModalWindow = (args: ComponentPropsType) => ({
  components: { ModalWindowComponent },
  setup() {
    const model = ref<boolean>(true);
    return { args, model };
  },
  template: `
    <ModalWindowComponent v-bind="args" v-model="model">sdasfsd</ModalWindowComponent>
  `
});

import PopoverPanelComponent from "@/components/Common/PopoverPanel.vue";

type ComponentPropsType = {};

export default {
  component: PopoverPanelComponent,
  title: "Components/Common/PopoverPanel",
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component for popover overlayer.",
      },
    },
  },
};

export const PopoverPanel = (args: ComponentPropsType) => ({
  components: { PopoverPanelComponent },
  setup() {
    return { args };
  },
  template: `
    <div class="h-full flex items-center justify-center" style="background:#f4f4f6">
      <PopoverPanelComponent v-bind="args">
        T
        <template #overlayer>
          overlayer
        </template>
      </PopoverPanelComponent>
    </div>
  `,
});

PopoverPanel.args = {
  isVisible: true
}

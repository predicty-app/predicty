import DropdownMenuComponent from "@/components/Common/DropdownMenu.vue";

type ComponentPropsType = {};

export default {
  component: DropdownMenuComponent,
  title: "Components/Common/DropdownMenu",
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component for drop down menu",
      },
    },
  },
};

export const DropdownMenu = (args: ComponentPropsType) => ({
  components: { DropdownMenuComponent },
  setup() {
    return { args };
  },
  template: `
    <div class="text-center p-5">
      <DropdownMenuComponent v-bind="args">Lorem ipsum
        <template #overlayer>
          overlayer
        </template>
      </DropdownMenuComponent>
    </div>
  `,
})

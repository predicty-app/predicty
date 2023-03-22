import AvatarUserComponent from "@/components/Common/AvatarUser.vue";

type ComponentPropsType = {};

export default {
  component: AvatarUserComponent,
  title: "Components/Common/AvatarUser",
  argTypes: {
    src: {
      control: { type: "string" },
      description: "Props for src.",
      name: "src",
      type: { name: "string", required: false }
    }
  },
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for avatar user"
      }
    }
  }
};

export const AvatarUser = (args: ComponentPropsType) => ({
  components: { AvatarUserComponent },
  setup() {
    return { args };
  },
  template: `
    <AvatarUserComponent v-bind="args" />
  `
});

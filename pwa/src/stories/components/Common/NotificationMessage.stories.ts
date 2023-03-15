import NotificationMessageComponent from "@/components/Common/NotificationMessage.vue";

type ComponentPropsType = {};

export default {
  component: NotificationMessageComponent,
  title: "Components/Common/NotificationMessage",
  argTypes: {
    type: {
      options: ["success", "info", "warning", "error"],
      control: { type: "select" },
      description: "Notification type.",
      defaultValue: "primary",
      name: "type",
      type: { name: "string", required: false }
    },
    message: {
      name: "message",
      description: "Message for notification.",
      type: { name: "string", required: true }
    }
  },
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component atoms for notification atom"
      }
    },
    source: {
      code: "<NotificationMessageComponent />"
    }
  }
};

export const NotificationMessage = (args: ComponentPropsType) => ({
  components: { NotificationMessageComponent },
  setup() {
    return { args };
  },
  template: `
    <NotificationMessageComponent v-bind="args" />
  `
});

NotificationMessage.args = {
  type: "success",
  message:
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean hendrerit et libero venenatis lobortis. "
};

import CampaningListFormComponent from "@/components/UserDashboard/CampaningListForm.vue";

type ComponentPropsType = {};

export default {
  component: CampaningListFormComponent,
  title: "Components/UserDashboard/CampaningListForm",
  parameters: {
    status: {
      type: "stable"
    },
    docs: {
      description: {
        component: "Component for campaining list"
      }
    }
  }
};

export const CampaningListForm = (args: ComponentPropsType) => ({
  components: { CampaningListFormComponent },
  setup() {
    return { args };
  },
  template: `
    <CampaningListFormComponent v-bind="args" />
  `
});

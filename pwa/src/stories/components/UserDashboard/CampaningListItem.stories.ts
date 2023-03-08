import CampaningListItemComponent from "@/components/UserDashboard/CampaningListItem.vue";

type ComponentPropsType = {};

export default {
  component: CampaningListItemComponent,
  title: "Components/UserDashboard/CampaningListItem",
  argTypes: {
    date: {
      header: "Header for item",
      name: "header",
      type: { name: "string", required: false },
    },
    color: {
      name: "color",
      description: "Color for campaing.",
      type: { name: "string", required: false },
    },
  },
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for item campaining list",
      },
    },
  },
};

export const CampaningListItem = (args: ComponentPropsType) => ({
  components: { CampaningListItemComponent },
  setup() {
    return { args };
  },
  template: `
    <CampaningListItemComponent v-bind="args">
      Lorem ipsum <br/>
      Lorem ipsum <br/>
      Lorem ipsum <br/>
    </CampaningListItemComponent>
  `,
});

CampaningListItem.args = {
  header: "Lorem ipsum",
  color: "#4DC962",
};

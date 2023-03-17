import CollectionSideItemsComponent from "@/components/UserDashboard/Collection/CollectionSideItems.vue";

type ComponentPropsType = {};

export default {
  component: CollectionSideItemsComponent,
  title: "Components/UserDashboard/Collection/CollectionSideItems",
  argTypes: {
    ads: {
      control: { type: "object" },
      description: "Ad's data",
      name: "ads",
      type: { name: "object[]", required: false },
    },
  },
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component for ad's content display",
      },
    },
  },
};

export const CollectionSideItems = (args: ComponentPropsType) => ({
  components: { CollectionSideItemsComponent },
  setup() {
    return { args };
  },
  template: `
  <CollectionSideItemsComponent v-bind="args" />
  `,
});

CollectionSideItems.args = {
  ads: [
    {
      uid: "uid",
      name: "Ad",
      start: "2023-01-01",
      end: "2023-05-01",
      creation: "",
      cost_total: 0,
      cost_per_day: 0,
    },
  ],
};

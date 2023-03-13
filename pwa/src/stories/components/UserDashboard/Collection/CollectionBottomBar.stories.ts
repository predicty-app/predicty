import CollectionBottomBarComponent from "@/components/UserDashboard/Collection/CollectionBottomBar.vue";

type ComponentPropsType = {};

export default {
  component: CollectionBottomBarComponent,
  title: "Components/UserDashboard/Collection/CollectionBottomBar",
  argTypes: {
    collection: {
      control: { type: "object" },
      description: "Collection object data",
      name: "collection",
      type: { name: "object", required: false }
    }
  },
  parameters: {
    status: {
      type: "wip"
    },
    docs: {
      description: {
        component: "Component for a collection's content display"
      }
    }
  }
};

export const CollectionBottomBar = (args: ComponentPropsType) => ({
  components: { CollectionBottomBarComponent },
  setup() {
    return { args };
  },
  template: `
  <CollectionBottomBarComponent v-bind="args" />
  `
});

CollectionBottomBar.args = {
  collection: {
    name: "Collection example",
    uid: "",
    ads: [""],
    start: "",
    end: ""
  }
};

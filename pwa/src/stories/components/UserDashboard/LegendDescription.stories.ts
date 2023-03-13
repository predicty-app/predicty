import LegendDescriptionComponent from "@/components/UserDashboard/LegendDescription.vue";

type ComponentPropsType = {};

export default {
  component: LegendDescriptionComponent,
  title: "Components/UserDashboard/LegendDescription",
  argTypes: {
    typeChartOptions: {
      name: "typeChartOptions",
      description: "Types option of chart.",
      control: { type: "array" },
      type: { name: "string", required: false },
    },
    options: {
      name: "options",
      description: "Options for legend list.",
      control: { type: "array" },
      type: { name: "string", required: false },
    },
    amountScale: {
      name: "amountScale",
      description: "Amount scale for legend list.",
      control: { type: "array" },
      type: { name: "string", required: false },
    },
  },
  parameters: {
    status: {
      type: "todo",
    },
    docs: {
      description: {
        component: "Component for legend chart",
      },
    },
  },
};

export const LegendDescription = (args: ComponentPropsType) => ({
  components: { LegendDescriptionComponent },
  setup() {
    return { args };
  },
  template: `
    <div class="h-full">
      <LegendDescriptionComponent v-bind="args" />
    </div>
  `,
});

LegendDescription.args = {
  header: "week of the year",
  typeChartOptions: [
    {
      key: 'weeks',
      label: 'Weeks',
    },
    {
      key: 'days',
      label: 'Days',
    },
  ],
  options: [
    {
      label: "Overall sales",
      color: "#4184FF",
    },
    {
      label: "Overall investment",
      color: "#FFAE4F",
    },
  ],
};

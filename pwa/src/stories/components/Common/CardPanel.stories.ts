import CardPanelComponent from '@/components/Common/CardPanel.vue'

type ComponentPropsType = {}

export default {
  component: CardPanelComponent,
  title: 'Components/Common/CardPanel',
  parameters: {
    status: {
      type: 'stable',
    },
    docs: {
      description: {
        component: 'Component for card panel',
      },
    },
  },
}

export const CardPanel = (args: ComponentPropsType) => ({
  components: { CardPanelComponent },
  setup() {
    return { args }
  },
  template: `
    <CardPanelComponent v-bind="args">Lorem ipsum dolor sit amet</CardPanelComponent>
  `,
})

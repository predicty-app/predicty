import ScrollbarPanelComponent from "@/components/Common/ScrollbarPanel.vue";

type ComponentPropsType = {};

export default {
  component: ScrollbarPanelComponent,
  title: "Components/Common/ScrollbarPanel",
  parameters: {
    status: {
      type: "stable",
    },
    docs: {
      description: {
        component: "Component for custom scrollbar",
      },
    },
  },
};

export const ScrollbarPanel = (args: ComponentPropsType) => ({
  components: { ScrollbarPanelComponent },
  setup() {
    return { args };
  },
  template: `
  <div class=" select-none grid grid-cols-[336px_1fr] grid-rows-[360px_1fr] h-screen">
    <div>1</div>
    <ScrollbarPanelComponent>
      DD DSD ADSADasd adasd adsd ASD Saddas asd asdad asda dasd asda a asd  dad fdsfasd f sdfsdf f asfdafs dfasf dfasdf dfafsd fds fdasfs dfsdf asfa ssf asdffa sff sfd
    </ScrollbarPanelComponent>
    <ScrollbarPanelComponent>
      vavd<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
      dfsdd v<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
      dfsdf
    </ScrollbarPanelComponent>
    <div>4</div>
  </div>
  `,
});

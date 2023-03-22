import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import CollectionBottomBar from "@/components/UserDashboard/Collection/CollectionBottomBar.vue";
import IconSvg from "@/components/Common/IconSvg.vue";
import type { AdSetsType, AdsType } from "@/stores/userDashboard";

describe("Tests for CollectionBottomBar component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(CollectionBottomBar, {
      props,
      global: {
        plugins: plugins,
        stubs: {
          IconSvg
        }
      }
    });

    const bottomBar = wrapper.find<HTMLDivElement>(
      '[data-testid="collection-bottom-bar"]'
    );
    const button = wrapper.find<HTMLButtonElement>(
      '[data-testid="collection-bottom-bar__close"]'
    );

    return {
      wrapper,
      bottomBar,
      button
    };
  }

  it("should close on Close button click", async () => {
    type PropsType = {
      collection?: AdsType | AdSetsType;
    };

    const { wrapper, button } = prepareElementsToTests<PropsType>({
      collection: {
        uid: "",
        name: "Collection",
        ads: [],
        start: "",
        end: ""
      } as AdSetsType
    });

    await button.trigger("click");
    expect(wrapper.emitted().close).toBeTruthy();
  });
});

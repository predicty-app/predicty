import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import CardPanel from "@/components/Common/CardPanel.vue";

describe("Tests for CardPanel component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(CardPanel, {
      props,
      global: {
        plugins: plugins,
      },
    });

    const card = wrapper.find<HTMLDivElement>('[data-testid="card-panel"]');

    return {
      card,
    };
  }

  it('should have type = "default" when props type not set', () => {
    const { card } = prepareElementsToTests();
    expect(card.attributes("data-type")).toBe("default");
  });

  it('should have type = "success" when props type set', () => {
    type PropsType = {
      type: string;
    };

    const { card } = prepareElementsToTests<PropsType>({
      type: "success",
    });
    expect(card.attributes("data-type")).toBe("success");
  });

  it('should have type = "default" when props type set', () => {
    type PropsType = {
      type: string;
    };

    const { card } = prepareElementsToTests<PropsType>({
      type: "default",
    });
    expect(card.attributes("data-type")).toBe("default");
  });
});

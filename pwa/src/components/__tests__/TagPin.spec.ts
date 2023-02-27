import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import TagPin from "@/components/Common/TagPin.vue";
import IconSvg from "@/components/Common/IconSvg.vue";

describe("Tests for TagPin component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(TagPin, {
      props,
      global: {
        plugins: plugins,
        stubs: {
          IconSvg,
        },
      },
    });

    const tag = wrapper.find<HTMLDivElement>('[data-testid="tag-pin"]');

    return {
      tag,
    };
  }

  it('should have type = "default" when props type not set', () => {
    const { tag } = prepareElementsToTests();
    expect(tag.attributes("data-type")).toBe("default");
  });

  it('should have type = "success" when props type set', () => {
    type PropsType = {
      type: string;
    };

    const { tag } = prepareElementsToTests<PropsType>({
      type: "success",
    });
    expect(tag.attributes("data-type")).toBe("success");
  });

  it('should have type = "primary" when props type set', () => {
    type PropsType = {
      type: string;
    };

    const { tag } = prepareElementsToTests<PropsType>({
      type: "primary",
    });
    expect(tag.attributes("data-type")).toBe("primary");
  });

  it('should have type = "default" when props type set', () => {
    type PropsType = {
      type: string;
    };

    const { tag } = prepareElementsToTests<PropsType>({
      type: "default",
    });
    expect(tag.attributes("data-type")).toBe("default");
  });
});

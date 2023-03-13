import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import HeaderText from "@/components/Common/HeaderText.vue";

describe("Tests for HeaderText component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(HeaderText, {
      props,
      global: {
        plugins: plugins
      }
    });

    const title = wrapper.find<HTMLHeadingElement>(
      '[data-testid="header-text"]'
    );

    const description = wrapper.find<HTMLParagraphElement>(
      '[data-testid="header-description"]'
    );

    return {
      title,
      description
    };
  }

  it("should have no visible h3 tag when props headerTitle not set", () => {
    const { title } = prepareElementsToTests();

    expect(title.exists()).toBeFalsy();
  });

  it("should have no visible p tag when props headerDescrption not set", () => {
    const { description } = prepareElementsToTests();

    expect(description.exists()).toBeFalsy();
  });

  it('should have title = "test" when props headerTitle set', () => {
    type PropsType = {
      headerTitle: string;
    };

    const { title } = prepareElementsToTests<PropsType>({
      headerTitle: "test"
    });

    expect(title.text()).toBe("test");
  });

  it('should have description = "test" when props headerDescription set', () => {
    type PropsType = {
      headerDescription: string;
    };

    const { description } = prepareElementsToTests<PropsType>({
      headerDescription: "test"
    });

    expect(description.text()).toBe("test");
  });
});

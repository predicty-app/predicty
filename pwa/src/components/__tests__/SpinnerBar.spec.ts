import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import SpinnerBar from "@/components/Common/SpinnerBar.vue";

describe("Tests for SpinnerBar component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(SpinnerBar, {
      props,
      global: {
        plugins: plugins
      }
    });

    const spinner = wrapper.find<HTMLButtonElement>(
      '[data-testid="spinner-bar"]'
    );

    return {
      spinner
    };
  }

  it("should have no visible spinner when props isVisible not set", () => {
    const { spinner } = prepareElementsToTests();

    expect(spinner.exists()).toBeFalsy();
  });

  it("should have  visible spinner when props isVisible set to true", () => {
    type PropsType = {
      isVisible: boolean;
    };

    const { spinner } = prepareElementsToTests<PropsType>({
      isVisible: true
    });

    expect(spinner.exists()).toBeTruthy();
  });

  it("should have position relative when not set props isGlobal", () => {
    type PropsType = {
      isVisible: boolean;
    };

    const { spinner } = prepareElementsToTests<PropsType>({
      isVisible: true
    });

    expect(spinner.classes()).contains("relative");
  });

  it("should have position absolute when not set props isGlobal", () => {
    type PropsType = {
      isVisible: boolean;
      isGlobal: boolean;
    };

    const { spinner } = prepareElementsToTests<PropsType>({
      isVisible: true,
      isGlobal: true
    });

    expect(spinner.classes()).contains("absolute");
  });
});

import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import ProgressBar from "@/components/Common/ProgressBar.vue";

describe("Tests for ProgressBar component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(ProgressBar, {
      props: {
        countSteps: 1,
        ...props
      },
      global: {
        plugins: plugins
      }
    });

    const lines = wrapper.findAll<HTMLDivElement>(
      '[data-testid="progress-bar-lines"]'
    );

    return {
      lines
    };
  }

  it("should have 2 lines when props count-steps is set to 3", () => {
    type PropsType = {
      countSteps: number;
    };

    const { lines } = prepareElementsToTests<PropsType>({
      countSteps: 6
    });

    expect(lines.length).toBe(6);
  });

  it("should have active 1 line when props active-step set to 2", () => {
    type PropsType = {
      countSteps: number;
      activeStep: number;
    };

    const { lines } = prepareElementsToTests<PropsType>({
      countSteps: 3,
      activeStep: 2
    });

    expect(lines[0].attributes("data-active")).toBe("true");
    expect(lines[1].attributes("data-active")).toBe("false");
  });
});

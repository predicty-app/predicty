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
        ...props,
      },
      global: {
        plugins: plugins,
      },
    });

    const dots = wrapper.findAll<HTMLDivElement>(
      '[data-testid="progress-bar-dots"]'
    );

    const lines = wrapper.findAll<HTMLDivElement>(
      '[data-testid="progress-bar-lines"]'
    );

    return {
      dots,
      lines,
    };
  }

  it("should have 3 dots when props count-steps is set", () => {
    type PropsType = {
      countSteps: number;
    };

    const { dots } = prepareElementsToTests<PropsType>({
      countSteps: 3,
    });

    expect(dots.length).toBe(3);
  });

  it("should have 2 lines when props count-steps is set to 3", () => {
    type PropsType = {
      countSteps: number;
    };

    const { lines } = prepareElementsToTests<PropsType>({
      countSteps: 3,
    });

    expect(lines.length).toBe(2);
  });

  it("should have active 2 dots when props active-step set to 2", () => {
    type PropsType = {
      countSteps: number;
      activeStep: number;
    };

    const { dots } = prepareElementsToTests<PropsType>({
      countSteps: 3,
      activeStep: 2,
    });

    expect(dots[0].attributes("data-active")).toBe("true");
    expect(dots[1].attributes("data-active")).toBe("true");
    expect(dots[2].attributes("data-active")).toBe("false");
  });

  it("should have active 1 line when props active-step set to 2", () => {
    type PropsType = {
      countSteps: number;
      activeStep: number;
    };

    const { lines } = prepareElementsToTests<PropsType>({
      countSteps: 3,
      activeStep: 2,
    });

    expect(lines[0].attributes("data-active")).toBe("true");
    expect(lines[1].attributes("data-active")).toBe("false");
  });
});

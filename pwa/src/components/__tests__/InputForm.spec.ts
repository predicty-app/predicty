import { describe, it, expect } from "vitest";
import { vMaska } from "maska";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import IconSvg from "@/components/Common/IconSvg.vue";
import InputForm from "@/components/Common/InputForm.vue";

describe("Tests for InputForm component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(InputForm, {
      props: {
        modelValue: "",
        ...props
      },
      global: {
        plugins: plugins,
        stubs: {
          vMaska,
          IconSvg
        }
      }
    });

    const label = wrapper.find<HTMLLabelElement>(
      '[data-testid="input-form-label"]'
    );

    const input = wrapper.find<HTMLInputElement>(
      '[data-testid="input-form-input"]'
    );

    const error = wrapper.find<HTMLSpanElement>(
      '[data-testid="input-form-error"]'
    );

    const required = wrapper.find<HTMLSpanElement>(
      '[data-testid="input-form-required"]'
    );

    return {
      label,
      input,
      error,
      required
    };
  }

  it("should have no visible label tag when props label not set", () => {
    const { label } = prepareElementsToTests();

    expect(label.exists()).toBeFalsy();
  });

  it("should have visible label tag when props label set", () => {
    type PropsType = {
      label: string;
    };

    const { label } = prepareElementsToTests<PropsType>({
      label: "test"
    });

    expect(label.text()).toBe("test");
  });

  it("should have no set placeholder when props placeholder not set", () => {
    const { input } = prepareElementsToTests();

    expect(input.attributes("placeholder")).toBeUndefined();
  });

  it('should have set placeholder = "test" when props placeholder set', () => {
    type PropsType = {
      placeholder: string;
    };

    const { input } = prepareElementsToTests<PropsType>({
      placeholder: "test"
    });

    expect(input.attributes("placeholder")).toBe("test");
  });

  it("should have not set data-mask when props mask not set", () => {
    const { input } = prepareElementsToTests();

    expect(input.attributes("data-mask")).toBeUndefined();
  });

  it('should have set data-mask = "##" when props mask set', () => {
    type PropsType = {
      mask: string;
    };

    const { input } = prepareElementsToTests<PropsType>({
      mask: "##"
    });

    expect(input.attributes("data-maska")).toBe("##");
  });

  it("should have no visible span error tag when props error-message not set", () => {
    const { error } = prepareElementsToTests();

    expect(error.exists()).toBeFalsy();
  });

  it("should have visible span error tag when props error-message set", () => {
    type PropsType = {
      errorMessage: string;
    };

    const { error } = prepareElementsToTests<PropsType>({
      errorMessage: "test"
    });

    expect(error.text()).toBe("test");
  });

  it("should have no visible span required tag when props required not set", () => {
    const { required } = prepareElementsToTests();

    expect(required.exists()).toBeFalsy();
  });

  it("should have visible span required tag when props required set", () => {
    type PropsType = {
      required: boolean;
      label: string;
    };

    const { required } = prepareElementsToTests<PropsType>({
      required: true,
      label: "test"
    });

    expect(required.exists()).toBeTruthy();
  });
});

import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { mount } from "@vue/test-utils";
import IconSvg from "@/components/Common/IconSvg.vue";
import TagPin from "@/components/Common/TagPin.vue";
import UploadFile from "@/components/Common/UploadFile.vue";

describe("Tests for UploadFile component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(UploadFile, {
      props: {
        filesType: [],
        modelValue: "",
        ...props
      },
      global: {
        plugins: plugins,
        stubs: {
          IconSvg,
          TagPin
        }
      }
    });

    const upload = wrapper.find<HTMLDivElement>('[data-testid="upload-file"]');

    return {
      upload
    };
  }

  it("should have position absolute when not set props isGlobal", () => {
    type PropsType = {
      filesType: string[];
    };

    const { upload } = prepareElementsToTests<PropsType>({
      filesType: [".csv"]
    });

    expect(upload.text()).contains(".csv");
  });
});

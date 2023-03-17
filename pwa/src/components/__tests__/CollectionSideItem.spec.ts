import { describe, it, expect } from "vitest";
import { mount } from "@vue/test-utils";
import CollectionSideItems from "@/components/UserDashboard/Collection/CollectionSideItems.vue";
import type { AdsType } from "@/stores/userDashboard";

describe("Tests for CollectionSideItems component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(CollectionSideItems, {
      props,
    });

    const dateRange = wrapper.find<HTMLParagraphElement>(
      '[data-testid="collection-side-item__dates"]'
    );

    return {
      dateRange,
    };
  }

  it("should render date in a correct format", () => {
    type PropsType = {
      ads: AdsType[];
    };

    const { dateRange } = prepareElementsToTests<PropsType>({
      ads: [
        {
          uid: "uid",
          name: "Ad",
          start: "2023-01-01",
          end: "2023-05-01",
          creation: "",
          cost_total: 0,
          cost_per_day: 0,
        },
      ],
    });

    expect(dateRange.text()).toBe("01.01 - 01.05");
  });
});

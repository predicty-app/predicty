import { describe, it, expect } from "vitest";
import plugins from "@/helpers/plugins";
import { flushPromises, mount, shallowMount } from "@vue/test-utils";
import Conversation from "@/components/UserDashboard/Comments/Conversation.vue";
import type { ConversationsType } from "@/stores/userDashboard";

describe("Tests for Conversation component", () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = shallowMount(Conversation, {
      props: {
        modelValue: true,
        ...props
      },
      global: {
        plugins: plugins
      }
    });

    const deleteButton = wrapper.find<HTMLButtonElement>(
      '[data-testid="conversation__delete"]'
    );

    const closeButton = wrapper.find<HTMLButtonElement>(
      '[data-testid="conversation__close"]'
    );

    const heading = wrapper.find<HTMLHeadingElement>(
      '[data-testid="conversation__heading"]'
    );

    const comments = wrapper.findAll<HTMLDivElement>(
      '[data-testid="conversation__comment"]'
    );

    return {
      wrapper,
      deleteButton,
      closeButton,
      heading,
      comments
    };
  }

  it("should emit on close button click", async () => {
    const { wrapper, closeButton } = prepareElementsToTests();
    await closeButton.trigger("click");

    expect(wrapper.emitted("update:modelValue")).toBeTruthy();
  });

  it("should delete conversation on button click", async () => {
    type PropsType = {
      conversation: ConversationsType;
    };

    const { wrapper, deleteButton } = prepareElementsToTests<PropsType>({
      conversation: {
        id: "1",
        date: "",
        color: {
          hex: ""
        },
        comments: [
          {
            createdAt: "",
            comment: "Hello"
          },
          {
            createdAt: "",
            comment: "Hello 2"
          }
        ]
      }
    });

    await deleteButton.trigger("click");
    await flushPromises();
    await flushPromises();
    await flushPromises();

    expect(wrapper.emitted("update:modelValue")).toBeTruthy();
    expect(wrapper.emitted("updated")).toBeTruthy();
  });

  it("should display proper date formats", () => {
    type PropsType = {
      conversation: ConversationsType;
    };

    const { heading, comments } = prepareElementsToTests<PropsType>({
      conversation: {
        id: "1",
        date: "2023-04-28",
        color: {
          hex: ""
        },
        comments: [
          {
            createdAt: "2023-04-28",
            comment: "Hello"
          }
        ]
      }
    });

    expect(heading.text()).toBe("Friday 28.04");
    expect(comments[0].text()).toContain("Today");
  });

  it("doesn't show comments if conversation is new", () => {
    const { comments } = prepareElementsToTests();

    expect(comments.length).toBe(0);
  });

  it("shows comments if they exist", () => {
    type PropsType = {
      conversation: ConversationsType;
    };

    const { comments } = prepareElementsToTests<PropsType>({
      conversation: {
        id: "",
        date: "",
        color: {
          hex: ""
        },
        comments: [
          {
            createdAt: "",
            comment: "Hello"
          },
          {
            createdAt: "",
            comment: "Hello 2"
          }
        ]
      }
    });

    expect(comments.length).toBe(2);
  });
});

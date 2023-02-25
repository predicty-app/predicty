import { describe, it, expect } from 'vitest'
import plugins from '@/helpers/plugins'
import { mount } from '@vue/test-utils'
import ButtonForm from '@/components/Common/ButtonForm.vue'

describe('Tests for ButtonForm component', () => {
  /**
   * Function to prepare element for tests.
   * @param <T> props
   */
  function prepareElementsToTests<T>(props?: T) {
    const wrapper = mount(ButtonForm, {
      props,
      global: {
        plugins: plugins,
      },
    })

    const button = wrapper.find<HTMLButtonElement>(
      '[data-testid="button-form"]'
    )

    return  {
      button
    }
  }

  it('should have type = "default" when props type not set', () => {
    const { button } = prepareElementsToTests()
    expect(button.attributes('data-type')).toBe('default')
  })

  it('should have type = "success" when props type set', () => {
    type PropsType = {
      type: string
    }

    const { button } = prepareElementsToTests<PropsType>({
      type: 'success'
    })
    expect(button.attributes('data-type')).toBe('success')
  })


  it('should have type = "default" when props type set', () => {
    type PropsType = {
      type: string
    }

    const { button } = prepareElementsToTests<PropsType>({
      type: 'default'
    })
    expect(button.attributes('data-type')).toBe('default')
  })
})
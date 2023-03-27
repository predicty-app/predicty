<script setup lang="ts">
type PropsType = {
  isVisible?: boolean;
  isGlobal?: boolean;
};

withDefaults(defineProps<PropsType>(), {
  isVisible: false,
  isGlobal: false
});
</script>

<template>
  <div
    v-if="isVisible"
    data-testid="spinner-bar"
    :class="[
      'animate-fade-in',
      {
        relative: !isGlobal,
        'fixed w-full h-full top-0 left-0 z-[9999]': isGlobal
      }
    ]"
  >
    <div
      :class="[
        'spinner left-0 right-0 m-auto z-[9999]',
        {
          'fixed top-0 bottom-0': isGlobal
        }
      ]"
    >
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
    <div
      :class="[
        'w-full h-full z-20',
        {
          'grayscale opacity-50': isVisible,
          'absolute bg-upload-border top-0 left-0': isGlobal
        }
      ]"
    >
      <div class="w-full h-full z-10 absolute top-0 left-0" />
      <slot />
    </div>
  </div>
</template>

<style lang="scss">
.spinner {
  width: 80px;
  height: 80px;
  position: absolute;
  display: inline-block;

  div {
    animation: lds-spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    transform-origin: 40px 40px;

    &:after {
      content: " ";
      display: block;
      position: absolute;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #2e0c63;
      margin: -4px 0 0 -4px;
    }

    &:nth-child(1) {
      animation-delay: -0.036s;

      &:after {
        top: 63px;
        left: 63px;
      }
    }

    &:nth-child(2) {
      animation-delay: -0.072s;

      &:after {
        top: 68px;
        left: 56px;
      }
    }

    &:nth-child(3) {
      animation-delay: -0.108s;

      &:after {
        top: 71px;
        left: 48px;
      }
    }

    &:nth-child(4) {
      animation-delay: -0.144s;

      &:after {
        top: 72px;
        left: 40px;
      }
    }

    &:nth-child(5) {
      animation-delay: -0.18s;

      &:after {
        top: 71px;
        left: 32px;
      }
    }

    &:nth-child(6) {
      animation-delay: -0.216s;

      &:after {
        top: 68px;
        left: 24px;
      }
    }

    &:nth-child(7) {
      animation-delay: -0.252s;

      &:after {
        top: 63px;
        left: 17px;
      }
    }

    &:nth-child(8) {
      animation-delay: -0.288s;

      &:after {
        top: 56px;
        left: 12px;
      }
    }
  }

  @keyframes lds-spinner {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
}
</style>

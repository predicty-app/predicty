<script setup lang="ts">
type PropsType = {
  isVisible: boolean
  isGlobal: boolean
}

withDefaults(defineProps<PropsType>(), {
  isVisible: false,
  isGlobal: false
})
</script>

<template>
  <div v-if="isVisible"  :class="['animate-fade-in', {
    'relative': !isGlobal,
    'absolute w-full h-full top-0 left-0 z-40': isGlobal
  }]">
    <div :class="['spinner', {
      'absolute top-0 bottom-0 left-0 right-0 m-auto z-40': isGlobal
    }]"></div>
    <div :class="['w-full h-full z-20', {
      'grayscale opacity-50': isVisible,
      'absolute bg-upload-border top-0 left-0': isGlobal
    }]">
      <div  class="w-full h-full z-10 absolute top-0 left-0" />
      <slot />
    </div>
  </div>
</template>

<style lang="scss">
.spinner {
  font-size: 10px;
  text-indent: -9999em;
  width: 11em;
  height: 11em;
  border-radius: 50%;
  background: #2E0C63;
  background: -moz-linear-gradient(left, #2E0C63 10%, rgba(255, 255, 255, 0) 42%);
  background: -webkit-linear-gradient(left, #2E0C63 10%, rgba(255, 255, 255, 0) 42%);
  background: -o-linear-gradient(left, #2E0C63 10%, rgba(255, 255, 255, 0) 42%);
  background: -ms-linear-gradient(left, #2E0C63 10%, rgba(255, 255, 255, 0) 42%);
  background: linear-gradient(to right, #2E0C63 10%, rgba(255, 255, 255, 0) 42%);
  -webkit-animation: load3 1.4s infinite linear;
  animation: load3 1.4s infinite linear;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);

  &::before {
    width: 50%;
    height: 50%;
    background: transparent;
    border-radius: 100% 0 0 0;
    position: absolute;
    top: 0;
    left: 0;
    content: '';
  }

  &::after {
    background: rgba(237,237,241, 1);
    width: 75%;
    height: 75%;
    border-radius: 50%;
    content: '';
    margin: auto;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
  }

  @-webkit-keyframes load3 {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }

  @keyframes load3 {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
}</style>

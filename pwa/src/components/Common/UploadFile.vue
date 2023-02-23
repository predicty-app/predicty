<script setup lang="ts">
type PropsType = {
  filesType: string[]
}

defineProps<PropsType>()
</script>

<template>
  <div class="">
    <div></div>
    <div class="relative bg-white-text ">
      <input ref="inputInstance" type="file" name="file"
        class="opacity-0 absolute w-full h-full top-0 left-0 cursor-pointer" />
    </div>
  </div>
</template>
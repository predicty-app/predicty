<script setup lang="ts">
type PropsType = {
  isChecked: boolean;
  color?: string;
};

withDefaults(defineProps<PropsType>(), {
  isChecked: false,
  color: "#56CE6B"
});

const emit = defineEmits<{
  (e: "onChange", value: boolean): void;
}>();
</script>

<template>
  <div
    @click="emit('onChange', !isChecked)"
    :class="[
      'w-[17px] h-[17px] min-w-[17px] min-h-[17px] flex items-center justify-center rounded-[3px] border-basic-white opacity-90 border-2 cursor-pointer',
      {
        'bg-basic-white': isChecked
      }
    ]"
  >
    <IconSvg
      v-if="isChecked"
      name="check"
      class-name="fill-dynamic w-2.5 h-2.5"
      :style="{ '--fill': color }"
    />
  </div>
</template>

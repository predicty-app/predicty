<script setup lang="ts">
type OptionsType = {
  key: string;
  label: string;
  icon?: string;
  color?: string;
};

type PropsType = {
  options: OptionsType[];
};

defineProps<PropsType>();

defineEmits<{
  (e: "onClick", value: string): void;
}>();
</script>

<template>
  <div>
    <div
      @click="$emit('onClick', option.key)"
      :key="option.key"
      v-for="option in options"
      :style="{ '--color': option.color }"
      :class="[
        'flex gap-x-3 text-base px-5 py-3 items-center cursor-pointer hover:bg-gray-300',
        {
          'text-gray-1200': !option.color,
          'text-dynamic': option.color
        }
      ]"
    >
      <IconSvg
        v-if="option.icon"
        :name="option.icon"
        :style="{ '--fill': option.color }"
        :class-name="[
          'w-[14px] h-[14px]',
          {
            'fill-gray-1200': !option.color,
            'fill-dynamic': option.color
          }
        ]"
      />
      {{ option.label }}
    </div>
  </div>
</template>

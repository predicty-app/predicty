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
        'flex gap-x-3 text-base px-5 py-3 items-center cursor-pointer hover:bg-menuList-hover-background',
        {
          'text-menuList-text': !option.color,
          'text-menuList-color': option.color,
        },
      ]"
    >
      <IconSvg
        v-if="option.icon"
        :name="option.icon"
        :class-name="[
          'w-[14px] h-[14px]',
          {
            'fill-menuList-text': !option.color,
            'fill-menuList-color': option.color,
          },
        ]"
      />
      {{ option.label }}
    </div>
  </div>
</template>

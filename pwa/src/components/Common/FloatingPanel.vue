<script setup lang="ts">
import { ref } from "vue";
import { useI18n } from "vue-i18n";

type OptionsType = {
  key: string | number;
  label: string;
};

type PropsType = {
  selectedElements?: number;
  options: OptionsType[];
};

const { t } = useI18n();
const currentSelectedAction = ref<string>("");

withDefaults(defineProps<PropsType>(), {
  selectedElements: 0,
});

const emit = defineEmits<{
  (e: "onClick", value: string): void;
}>();
</script>

<template>
  <div
    class="bg-floatingPanel-background text- rounded-lg px-3 py-2 shadow-lg flex items-center max-w-max gap-x-[10px] floating-panel"
  >
    <div class="text-floatingPanel-text font-bold text-sm">
      {{ selectedElements }}
      {{
        t("components.common.foating-panel.count-elements", {
          s: selectedElements > 1 ? "s" : "",
        })
      }}
    </div>
    <div>
      <SelectForm
        class="w-44"
        v-model="currentSelectedAction"
        position="top"
        :options="options"
        :placeholder="t('components.common.foating-panel.select-placeholder')"
      />
    </div>
    <div>
      <ButtonForm
        type="success"
        class="text-xs px-3 py-2 rounded"
        @click="emit('onClick', currentSelectedAction)"
      >
        {{ t("components.common.foating-panel.button") }}
      </ButtonForm>
    </div>
  </div>
</template>

<style scoped lang="scss">
.floating-panel {
  :deep(button) {
    @apply py-2 rounded;
  }
}
</style>

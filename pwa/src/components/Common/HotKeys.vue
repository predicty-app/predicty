<script setup lang="ts">
import { onMounted } from "vue";
import { ActionHotkeyType } from "@/stores/conversations";

enum KeyDownType {
  ESCAPE = "Escape",
  C = "c"
}

onMounted(() => {
  window.addEventListener("keydown", handleKeyDownAction);
});

const emit = defineEmits<{
  (e: "handleAction", type: ActionHotkeyType): void;
}>();

/**
 * Function to handle keydown action.
 * @param {KeyboardEvent} e
 */
function handleKeyDownAction(e: KeyboardEvent) {
  if (![KeyDownType.ESCAPE, KeyDownType.C].includes(e.key as KeyDownType)) {
    return;
  }

  emit(
    "handleAction",
    e.key === KeyDownType.ESCAPE
      ? ActionHotkeyType.DEACTIVATION_CONVERSATION
      : ActionHotkeyType.ACTIVE_CONVERSATION
  );
}
</script>
<template><span></span></template>

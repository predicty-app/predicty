<script setup lang="ts">
import { ref, onMounted } from 'vue'

type PropsType = {
  src?: string
}

const props = defineProps<PropsType>()
const avatarInstance = ref<HTMLImageElement | null>(null)

function dynamicalLoadingImage(image: HTMLImageElement) {
  image.onerror = () => {
    image.src = '/assets/images/avatar.png'
  }
  image.src = props.src as string
}

onMounted(() => {
  const image = avatarInstance.value
  dynamicalLoadingImage(image as HTMLImageElement)
})

</script>

<template>
  <div class="rounded-full overflow-hidden w-8 h-8">
    <img ref="avatarInstance" class="w-full" />
  </div>
</template>
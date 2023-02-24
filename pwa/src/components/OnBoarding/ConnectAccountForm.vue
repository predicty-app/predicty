<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

type ProviderType = {
  name: string
  logoPath: string
  status: boolean
  token?: string
}

const providersList = ref<ProviderType[]>([
  {
    name: 'Google Analytics',
    logoPath: '/assets/images/providers/google-analytics-provider.png',
    status: false
  },
  {
    name: 'Google Ads',
    logoPath: '/assets/images/providers/google-ads-provider.png',
    status: false
  },
  {
    name: 'Meta Ads',
    logoPath: '/assets/images/providers/meta-ads-provider.png',
    status: false
  }
])
const isComponentMounted = ref<boolean>(false)

onMounted(() => nextTick(() => isComponentMounted.value = true))
</script>

<template>
  <div v-if="isComponentMounted" class="flex w-full gap-x-[30px]">
    <CardPanel class="flex flex-col  gap-y-[10px] justify-center items-center w-[180px] h-[168px]" :key="provider.name"
      v-for="provider in providersList">
      <img :src="provider.logoPath" class="w-12" />
      <h3 class="text-base w-16 text-center">{{ provider.name }}</h3>
      <TagPin :type="provider.status ? 'success' : 'primary'" :class="[{
        'cursor-pointer': !provider.status,
      }]">
        {{ t(`components.on-boarding.connect-account-form.pin.${provider.status ? 'connected' : 'click-to-connect'}`) }}
      </TagPin>
    </CardPanel>
    <Teleport to="#next-button">
      <ButtonForm type="success" class="w-full">
        <div class="relative">
          {{ t('components.on-boarding.connect-account-form.button') }}
          <IconSvg name="arrownext" class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3" />
        </div>
      </ButtonForm>
    </Teleport>
  </div>
</template>

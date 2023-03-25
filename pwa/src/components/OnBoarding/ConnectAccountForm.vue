<script setup lang="ts">
import { useRouter } from "vue-router";
import { ref, onMounted, nextTick } from "vue";
import { useOnBoardingStore } from "@/stores/onboarding";
import ContextProviders from "@/services/providers/ContextProviders";
import MetaProviderStrategy from "@/services/providers/MetaProviderStrategy";
import GoogleProviderStrategy from "@/services/providers/GoogleProviderStrategy";
import type { StrategyProviders } from "@/services/providers/ContextProviders";

import { useI18n } from "vue-i18n";

const { t } = useI18n();

type ProviderType = {
  name: string;
  logoPath: string;
  status: boolean;
  token?: string;
};

const onBoardingStore = useOnBoardingStore();
const providersImagesPath = "/assets/images/providers/";
const router = useRouter();
const nextStepPath = "/onboarding/more-media-integration";

const providersList = ref<ProviderType[]>([
  {
    name: "Google Analytics",
    logoPath: "google-analytics-provider",
    status: false
  },
  {
    name: "Google Ads",
    logoPath: "google-ads-provider",
    status: false
  },
  {
    name: "Meta Ads",
    logoPath: "meta-ads-provider",
    status: false
  }
]);
const isComponentMounted = ref<boolean>(false);

onMounted(() => nextTick(() => (isComponentMounted.value = true)));

/**
 * Function to connect selected provider.
 * @param {ProviderType} provider
 */
async function handleToogleConnecttoProvider(provider: ProviderType) {
  let strategy: StrategyProviders | null = null;
  if (provider.status) {
    provider.status = false;
    provider.token = null;

    onBoardingStore.handleToogleAddProvider(provider);
    return;
  }

  switch (provider.name) {
    case "Google Analytics":
      {
        strategy = new GoogleProviderStrategy();
      }
      break;
    case "Google Ads":
      {
        strategy = new GoogleProviderStrategy();
      }
      break;
    case "Meta Ads":
      {
        strategy = new MetaProviderStrategy();
      }
      break;
  }

  const context = new ContextProviders(strategy);
  const token = await context.getAccessToken();

  if (token) {
    provider.token = token;
    provider.status = true;
    onBoardingStore.handleToogleAddProvider(provider);
  }
}
</script>

<template>
  <div v-if="isComponentMounted" class="flex w-full gap-x-[30px]">
    <CardPanel
      :type="provider.status ? 'success' : 'default'"
      @click="handleToogleConnecttoProvider(provider)"
      class="flex flex-col gap-y-[10px] justify-center cursor-pointer items-center w-[180px] h-[168px]"
      :key="provider.name"
      v-for="provider in providersList"
    >
      <img
        :src="`${providersImagesPath}${provider.logoPath}${
          provider.status ? '-active' : ''
        }.png`"
        :class="[
          'w-12',
          {
            'opacity-70': provider.status
          }
        ]"
      />
      <h3
        :class="[
          'text-base w-16 text-center',
          {
            'font-bold': provider.status,
            'font-normal': !provider.status
          }
        ]"
      >
        {{ provider.name }}
      </h3>
      <div
        v-if="provider.status"
        class="w-7 h-7 min-h-[26px] bg-text-white rounded-full flex items-center justify-center"
      >
        <IconSvg name="check" class-name="w-4 h-4" />
      </div>
      <TagPin
        v-if="!provider.status"
        type="primary"
        :class="[
          {
            'cursor-pointer': !provider.status
          }
        ]"
      >
        {{
          t(
            `components.on-boarding.connect-account-form.pin.${
              provider.status ? "connected" : "click-to-connect"
            }`
          )
        }}
      </TagPin>
    </CardPanel>
    <Teleport to="#next-button">
      <div class="flex items-center gap-x-[27px]">
        <div
          class="text-base font-normal w-[200px] cursor-pointer"
          @click="router.push(nextStepPath)"
        >
          {{ t("views.basic-media-integration.skip") }}
        </div>
        <ButtonForm
          @click="router.push(nextStepPath)"
          :type="providersList.find((provider: ProviderType) => provider.status) ? 'success' : 'disabled'"
          class="w-full"
        >
          <div class="relative">
            {{ t("components.on-boarding.connect-account-form.button") }}
            <IconSvg
              name="arrownext"
              class-name="absolute right-5 top-0 bottom-0 m-auto h-3 w-3"
            />
          </div>
        </ButtonForm>
      </div>
    </Teleport>
  </div>
</template>

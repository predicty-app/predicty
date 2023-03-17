<script setup lang="ts">
import type { AdsType } from "@/stores/userDashboard";
import { ref } from "vue";

type PropsType = {
  ads: AdsType[];
};

defineProps<PropsType>();

type ProviderType = {
  name: string;
  logoPath: string;
  status: boolean;
  token?: string;
};

const imagesPath = "/assets/images";

const provider = ref<ProviderType>({
  name: "Meta Ads",
  logoPath: "meta-ads-provider",
  status: false,
});

const formatDate = (date: string) => {
  let parts = date.split(/\D/g);

  return [parts[2], parts[1]].join(".");
};
</script>

<template>
  <div
    :key="index"
    v-for="(ad, index) in ads"
    :class="[
      'h-[52px] flex items-center',
      {
        'bg-bottombar-background-light': index % 2 !== 0,
        'bg-bottombar-dark_grey': index % 2 === 0,
      },
    ]"
  >
    <img
      :src="`${imagesPath}/providers/${provider.logoPath}${
        provider.status ? '-active' : ''
      }.png`"
      :class="[
        'w-6 ml-4',
        {
          'opacity-70': provider.status,
        },
      ]"
      :alt="provider.name"
    />
    <img
      :src="`${imagesPath}/book-ad.png`"
      :class="[
        'w-10 ml-2 rounded',
        {
          'opacity-70': provider.status,
        },
      ]"
    />
    <div class="ml-2">
      <p class="text-xs text-bottombar-side-dark_grey">{{ ad.name }}</p>
      <p
        class="text-xs text-bottombar-side-grey"
        data-testid="collection-side-item__dates"
      >
        {{ formatDate(ad.start) }} - {{ formatDate(ad.end) }}
      </p>
    </div>
  </div>
</template>

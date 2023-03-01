<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { MenuNames } from "@/stores/userDashboard";

type OptionsType = {
  key: string;
  icon: string;
  label: string;
  color?: string;
};

const { t } = useI18n();

const optionsMenu: OptionsType[] = [
  {
    key: MenuNames.SETTINGS,
    icon: "cogs",
    label: t("components.user-dashboard.header-dashboard.menu-list.settings"),
  },
  {
    key: MenuNames.LOGOUT,
    icon: "logout",
    color: "#E24963",
    label: t("components.user-dashboard.header-dashboard.menu-list.logout"),
  },
];

/**
 * Function to handle click on menu.
 * @param {MenuNames} menuName
 */
function handleChangeView(menuName: MenuNames) {
  console.log(menuName);
}
</script>

<template>
  <div
    class="h-[60px] flex items-center bg-text-white shadow-md px-5 justify-between"
  >
    <AppLogo class="ml-6" />
    <DropdownMenu>
      <span class="text-base text-header-text inline-block"
        >Data update: Today at 6:24 AM</span
      >
      <AvatarUser />
      <template #overlayer>
        <MenuList :options="optionsMenu" @on-click="handleChangeView" />
      </template>
    </DropdownMenu>
  </div>
</template>

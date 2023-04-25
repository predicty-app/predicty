<script setup lang="ts">
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { MenuNames } from "@/stores/userDashboard";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { handleDeleteAuthenticationUser } from "@/services/api/authentication";

type OptionsType = {
  key: string;
  icon: string;
  label: string;
  color?: string;
};

const { t } = useI18n();
const router = useRouter();
const isModalWindowVisible = ref<boolean>(false);
const userDashboardStore = useUserDashboardStore();
const isSpinnerLoadingVisible = ref<boolean>(false);

const optionsMenu: OptionsType[] = [
  // {
  //   key: MenuNames.FILES,
  //   icon: "files",
  //   color: "#4E5B72",
  //   label: t("components.user-dashboard.header-dashboard.menu-list.files")
  // },
  {
    key: MenuNames.IMPORTS,
    icon: "checkmark",
    color: "#4E5B72",
    label: t("components.user-dashboard.header-dashboard.menu-list.imports")
  },
  {
    key: MenuNames.LOGOUT,
    icon: "logout",
    color: "#E24963",
    label: t("components.user-dashboard.header-dashboard.menu-list.logout")
  }
];

/**
 * Function to handle click on menu.
 * @param {MenuNames} menuName
 */
function handleFiredAction(menuName: MenuNames) {
  switch (menuName) {
    case MenuNames.LOGOUT:
      {
        isModalWindowVisible.value = true;
      }
      break;
    case MenuNames.IMPORTS:
      {
        router.push("/dashboard/import-history");
      }
      break;
  }
}

/**
 * Function to logout user from application.
 */
async function handleLogoutUser() {
  isSpinnerLoadingVisible.value = true;
  await handleDeleteAuthenticationUser();
  router.push("/onboarding/start-screen");
}
</script>

<template>
  <SpinnerBar :is-visible="isSpinnerLoadingVisible" :is-global="true" />
  <div
    class="header-dashboard h-[60px] flex items-center bg-text-white drop-shadow-md px-5 justify-between relative z-10"
  >
    <AppLogo class="ml-6" />
    <DropdownMenu>
      <div class="flex flex-col items-end gap leading-3">
        <span class="text-sm text-header-text inline-block"
          >Data update: Today at 6:24 AM</span
        >
        <span class="text-[10px]">
          {{ userDashboardStore.authenticatedUserParams?.email || "----" }}
        </span>
      </div>
      <AvatarUser />
      <template #overlayer>
        <MenuList :options="optionsMenu" @on-click="handleFiredAction" />
      </template>
    </DropdownMenu>
    <Teleport to="body">
      <ModalWindow v-model="isModalWindowVisible">
        <div
          class="flex flex-col gap-y-6 justify-center text-center header-dashboard__modal"
        >
          <HeaderText
            :header-title="
              t(
                'components.user-dashboard.header-dashboard.logout-modal.content.title'
              )
            "
            :header-description="
              t(
                'components.user-dashboard.header-dashboard.logout-modal.content.description'
              )
            "
          />
          <div class="flex gap-x-4 w-96 justify-center items-center m-auto">
            <ButtonForm @click="isModalWindowVisible = false">{{
              t(
                "components.user-dashboard.header-dashboard.logout-modal.buttons.cancel"
              )
            }}</ButtonForm>
            <ButtonForm @click="handleLogoutUser" type="success">{{
              t(
                "components.user-dashboard.header-dashboard.logout-modal.buttons.logout"
              )
            }}</ButtonForm>
          </div>
        </div>
      </ModalWindow>
    </Teleport>
  </div>
</template>

<style scoped lang="scss">
.header-dashboard {
  &__modal {
    :deep(h3) {
      @apply text-[24px];
    }

    :deep(div) {
      @apply gap-y-3;
    }

    :deep(button) {
      @apply py-3;
    }
  }
}
</style>

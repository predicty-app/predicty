import { useOnBoardingStore } from "@/stores/onboarding";
import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/onboarding/",
    name: "on-boarding",
    component: () => import("@/views/OnBoardingView.vue"),
    redirect: () => "/onboarding/start-screen",
    children: [
      {
        path: "/onboarding/start-screen",
        name: "on-boarding-start-screen",
        component: () => import("@/views/StartScreenView.vue"),
      },
      {
        path: "/onboarding/account-creation",
        name: "on-boarding-account-creation",
        redirect: () => "/onboarding/account-creation/email",
        component: () =>
          import("@/views/AccountCreation/AccountCreationView.vue"),
        children: [
          {
            path: "/onboarding/account-creation/email",
            name: "on-boarding-account-creation-email",
            component: () =>
              import("@/views/AccountCreation/AccountCreationEmailView.vue"),
          },
          {
            path: "/onboarding/account-creation/password",
            name: "on-boarding-account-creation-password",
            component: () =>
              import("@/views/AccountCreation/AccountCreationPasswordView.vue"),
            beforeEnter: () => {
              const onboardingStore = useOnBoardingStore();
              if (!onboardingStore.email) {
                return { path: "/onboarding/account-creation/email" };
              }
            },
          },
        ],
      },
      {
        path: "/onboarding/basic-media-integration",
        name: "on-boarding-basic-media-integration",
        component: () => import("@/views/BasicMediaIntegrationView.vue"),
        beforeEnter: () => {
          const onboardingStore = useOnBoardingStore();
          if (!onboardingStore.password) {
            return { path: "/onboarding/account-creation/password" };
          }
        },
      },
      {
        path: "/onboarding/more-media-integration",
        name: "on-boarding-more-media-integration",
        component: () => import("@/views/MoreMediaIntegrationView.vue"),
        beforeEnter: () => {
          const onboardingStore = useOnBoardingStore();
          if (onboardingStore.providers.length === 0) {
            return { path: "/onboarding/basic-media-integration" };
          }
        },
      },
      {
        path: "/onboarding/preparing-screen",
        name: "on-boarding-preparing-screen",
        component: () => import("@/views/PreparingScreenView.vue"),
        beforeEnter: () => {
          const onboardingStore = useOnBoardingStore();
          if (onboardingStore.providers.length === 0) {
            return { path: "/onboarding/basic-media-integration" };
          }
        },
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;

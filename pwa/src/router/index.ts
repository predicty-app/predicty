import { useOnBoardingStore } from "@/stores/onboarding";
import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import GuardService from "@/services/guard";

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
        component: () => import("@/views/StartScreenView.vue")
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
            meta: {
              authentication: false,
              authorizationType: "login"
            },
            component: () =>
              import("@/views/AccountCreation/AccountCreationEmailView.vue")
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
            }
          }
        ]
      },
      {
        path: "/onboarding/basic-media-integration",
        name: "on-boarding-basic-media-integration",
        meta: {
          authentication: true
        },
        component: () => import("@/views/BasicMediaIntegrationView.vue")
      },
      {
        path: "/onboarding/more-media-integration",
        name: "on-boarding-more-media-integration",
        meta: {
          authentication: true
        },
        component: () => import("@/views/MoreMediaIntegrationView.vue")
      },
      {
        path: "/onboarding/more-media-integration/file-settings",
        name: "on-boarding-more-media-integration-file-settings",
        meta: {
          authentication: true
        },
        component: () =>
          import("@/views/MoreMediaIntegrationFileSettingsView.vue"),
        beforeEnter: () => {
          const onboardingStore = useOnBoardingStore();
          if (!onboardingStore.file.file) {
            return { path: "/onboarding/preparing-screen" };
          }
        }
      },
      {
        path: "/onboarding/preparing-screen",
        name: "on-boarding-preparing-screen",
        meta: {
          authentication: true
        },
        component: () => import("@/views/PreparingScreenView.vue"),
        beforeEnter: () => {
          const onboardingStore = useOnBoardingStore();
          if (
            Object.keys(onboardingStore.providers).length === 0 &&
            !onboardingStore.file.file
          ) {
            return { path: "/" };
          }
        }
      }
    ]
  },
  {
    path: "/",
    name: "user-dashboard",
    meta: {
      authentication: true,
      authorizationType: "after"
    },
    component: () => import("@/views/UserDashboardView.vue")
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

GuardService.checkAuthentication(router);

export default router;

import type { Router, RouteRecordRaw } from "vue-router";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { handleAuthenticatedUser } from "@/services/api/authentication";

type GuardRouteType = {
  path: string;
};

export enum TypeOfAction {
  POSITIVE = "positive",
  NEGATION = "negation"
}

/**
 * RouterService
 * Router service to check authorization.
 */
export default class GuardService {
  /**
   * Method to check is onboarding completed.
   * @param {string} redirect
   * @returns {GuardRouteType}
   */
  public static checkIsOnboardingCompleted(
    redirect: string,
    type: TypeOfAction = TypeOfAction.POSITIVE
  ): GuardRouteType {
    const userDashboardStore = useUserDashboardStore();
    if (
      userDashboardStore.authenticatedUserParams &&
      (type === TypeOfAction.POSITIVE
        ? userDashboardStore.authenticatedUserParams?.isOnboardingComplete
        : !userDashboardStore.authenticatedUserParams?.isOnboardingComplete)
    ) {
      return { path: redirect };
    }
  }

  /**
   * Method to check is user authorization.
   * @param {Router} router
   */
  public static checkAuthentication(router: Router): void {
    if (import.meta.env.NODE_ENV === "test") {
      return;
    }

    const pathWithLogin = this.getPathByName(
      router.options.routes,
      "login",
      null
    );
    router.beforeEach((to, _, next) => {
      const pathThatRequiresAuth = to.matched.some(
        (record) => record.meta.authentication
      );
      (async () => {
        if (pathThatRequiresAuth) {
          const state = await handleAuthenticatedUser();
          if (state) {
            next();
          } else {
            next(pathWithLogin as any);
          }
        } else if (to.name === (pathWithLogin && pathWithLogin.name)) {
          const state = await handleAuthenticatedUser();
          if (state) {
            next(
              this.getPathByName(router.options.routes, "after", null) as any
            );
          } else {
            next();
          }
        } else {
          const state = await handleAuthenticatedUser();
          if (state) {
            const isDashboard = to.matched.some(
              (record) => record.meta.authorizationType === "dashboard"
            );

            if (isDashboard) {
              next(
                this.getPathByName(router.options.routes, "after", null) as any
              );
            } else {
              next();
            }
          } else {
            next();
          }
        }
      })();
    });
  }

  /**
   * Method to get path by name.
   * @param {RouteRecordRaw[]} routes
   * @param {String} name
   * @param {RouteRecordRaw} route
   * @return {RouteRecordRaw | undefined}
   */
  private static getPathByName(
    routes: readonly RouteRecordRaw[],
    name: string,
    route: RouteRecordRaw
  ) {
    routes.forEach((record: RouteRecordRaw) => {
      if (record.meta && record?.meta.authorizationType === name) {
        route = record;
      }

      if (record.children) {
        route = this.getPathByName(record.children, name, route);
      }
    });

    return route;
  }
}

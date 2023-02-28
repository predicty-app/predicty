import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import mkcert from'vite-plugin-mkcert';


import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import { ElementPlusResolver } from "unplugin-vue-components/resolvers";

import { VitePWA } from "vite-plugin-pwa";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    mkcert(),
    AutoImport({
      resolvers: [ElementPlusResolver()],
    }),
    Components({
      resolvers: [ElementPlusResolver()],
    }),
    //   , VitePWA({
    //   mode: "development",
    //   base: "/",
    //   srcDir: "src",
    //   filename: "sw.ts",
    //   includeAssets: ["/favicon.png"],
    //   strategies: "injectManifest",
    //   manifest: {
    //     name: "Predicty",
    //     short_name: "Predicty",
    //     theme_color: "#ffffff",
    //     start_url: "/",
    //     display: "standalone",
    //     background_color: "#ffffff",
    //     icons: [
    //       {
    //         src: "icon-192.png",
    //         sizes: "192x192",
    //         type: "image/png",
    //       },
    //       {
    //         src: "/icon-512.png",
    //         sizes: "512x512",
    //         type: "image/png",
    //       },
    //       {
    //         src: "icon-512.png",
    //         sizes: "512x512",
    //         type: "image/png",
    //         purpose: "any maskable",
    //       },
    //     ],
    //   },
    // })
  ],
  server: {
    https: true,
    host: 'localhost',
  },
  resolve: {
    alias: [
      {
        find: "@",
        replacement: fileURLToPath(new URL("./src", import.meta.url)),
      },
      {
        find: "vue-i18n",
        replacement: "vue-i18n/dist/vue-i18n.cjs.js",
      },
    ],
  },
});

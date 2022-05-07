import { fileURLToPath, URL } from "url";
import path from 'path'

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import Pages from "vite-plugin-pages";
import Layouts from "vite-plugin-vue-layouts";
import Components from "unplugin-vue-components/vite";
import Unocss from 'unocss/vite'
import AutoImport from 'unplugin-auto-import/vite'
import VueI18n from '@intlify/vite-plugin-vue-i18n'
import {
  QuasarResolver,
  // VuetifyResolver,
  PrimeVueResolver,
  AntDesignVueResolver,
  VantResolver


} from 'unplugin-vue-components/resolvers'

import pugPlugin from 'vite-plugin-pug'
import { createStyleImportPlugin, AntdResolve, VantResolve } from 'vite-plugin-style-import';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    createStyleImportPlugin({
      resolves: [AntdResolve(), VantResolve()],
    }),
    Pages({
      pagesDir: 'src/views',
      extensions: ['vue', 'ts'],
    }),
    Unocss(),
    Layouts({
      layoutsDir: 'src/layouts',
      defaultLayout: 'default'
    }),
    Components({
      resolvers: [AntDesignVueResolver(), QuasarResolver(), VantResolver()],
      // allow auto load markdown components under `./src/components/`
      extensions: ["vue", "md"],
      // allow auto import and register components used in markdown
      include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
      dts: "src/components.d.ts",
    }),

    AutoImport({
      imports: [
        'vue',
        'vue-router',
        'vue-i18n',
        'vue/macros',
        '@vueuse/head',
        '@vueuse/core',
      ],
      dts: 'src/auto-imports.d.ts',
    }),
    VueI18n({
      runtimeOnly: true,
      compositionOnly: true,
      include: [path.resolve(__dirname, 'locales/**')],
    }),

    pugPlugin(),

  ],

  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
      '~/': `${path.resolve(__dirname, 'src')}/`,
    },
  },

  server: {
    https: false,
    port: 3001,
    proxy: {
      '/api': {
        target: 'http://localhost:8600',
        changeOrigin: true,
        secure: false,
        rewrite: path => path.replace(/^\/api/, ''),
      },
    },
  },
});

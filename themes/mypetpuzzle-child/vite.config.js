import { defineConfig } from 'vite';
import { resolve } from 'path';
import purgecss from '@fullhuman/postcss-purgecss';

const IS_PURGE = process.env.PURGE === 'true';

export default defineConfig({
  base: '',

  css: {
    postcss: IS_PURGE
      ? {
          plugins: [
            purgecss({
              content: [
                resolve(__dirname, '**/*.php'),
                resolve(__dirname, 'assets/js/modules/*.js'),
                resolve(__dirname, '../plugins/mypetpuzzle-core/**/*.js'),
              ],
              safelist: {
                standard: [
                  /^woocommerce-/,
                  /^cart_item/,
                  /^variation_/,
                  /^is-/,
                  /^has-/,
                  /^header--/,
                  /^current-menu-item/,
                  /^current_page_item/,
                  /^admin-bar/,
                  /^wp-/,
                  /^screen-reader-text/,
                  /^select2-/,
                  /^country_select/,
                  /^state_select/,
                  /^payment/,
                  /^place-order/,
                  /^coupon/,
                  /^checkout/,
                  /^billing/,
                  /^shipping/,
                  /^order/,
                  /^card--/,
                  /^badge--/,
                  /^btn--/,
                  /^cpz-/,
                  /^hero__/,
                  /^puzzle-/,
                  /^email-/,
                  /^cookie-/,
                  /^top-banner/,
                  /^auth-/,
                  /^contact-page/,
                  /^bestsellers/,
                  /^comparison/,
                  /^gifts/,
                  /^cta-/,
                  /^faq/,
                  /^review/,
                  /^footer__/,
                  /^header__/,
                  /^section__/,
                  /^grid--/,
                  /^step/,
                  /^tab/,
                  /^panel/,
                  /^site-/,
                  /^col-/,
                  /^(no|js)-/,
                ],
              },
              keyframes: true,
              fontFace: true,
            }),
          ],
        }
      : undefined,
  },

  build: {
    cssCodeSplit: false,
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/main.js'),
        product: resolve(__dirname, 'src/product.js'),
        cart: resolve(__dirname, 'src/cart.js'),
        checkout: resolve(__dirname, 'src/checkout.js'),
      },
      output: {
        entryFileNames: 'js/[name].[hash].js',
        chunkFileNames: 'js/[name].[hash].js',
        assetFileNames: (info) => {
          if (info.name.endsWith('.css')) {
            return 'css/[name].[hash][extname]';
          }
          return 'assets/[name].[hash][extname]';
        },
      },
    },
  },
});

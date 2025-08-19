// vite.config.js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      // tắt full reload khi đổi Blade/PHP:
      refresh: false,
      // hoặc chỉ reload khi đổi view:
      // refresh: ['resources/views/**/*.blade.php'],
    }),
  ],
})

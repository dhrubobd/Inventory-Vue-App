import { defineConfig } from 'vite';
import vue from "@vitejs/plugin-vue";
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// import tailwindcss from "@tailwindcss/vite";
export default defineConfig({
plugins: [
vue(),
],
build: {
manifest: true,
outDir: "public/build",
},
});

// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php', // Pastikan ini mencakup folder view kamu
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/Filament/**/*.php', // Penting untuk Filament
    './vendor/filament/**/*.blade.php', // Penting untuk Filament
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

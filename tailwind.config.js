/**
 * Tailwind CSS configuration scoped to the Whippet plugin.
 *
 * @type {import('tailwindcss').Config}
 */
module.exports = {
  content: [
    "./resources/**/*.{php,js,jsx,ts,tsx,vue,scss,css}",
    "./inc/**/*.php",
    "./whippet.php",
  ],
  prefix: "wpt-", // Prefix all Tailwind classes to avoid conflicts
  important: "#whippet", // Make Tailwind styles important only within #whippet
  corePlugins: {
    preflight: false, // Disable Tailwind's CSS reset
  },
  theme: {
    extend: {},
  },
  plugins: [],
};

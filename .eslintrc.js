module.exports = {
  root: true,
  extends: ["plugin:@wordpress/eslint-plugin/recommended"],
  env: {
    browser: true,
    es2021: true,
  },
  parserOptions: {
    ecmaVersion: "latest",
    sourceType: "module",
  },
  globals: {
    jQuery: "readonly",
    $: "readonly",
    wp: "readonly",
  },
  rules: {
    // You can customize rules here
    "no-console": "warn",
  },
  ignorePatterns: [
    "node_modules/",
    "vendor/",
    "dist/",
    "inc/cache/",
    "resources/js/lazy/",
  ],
};

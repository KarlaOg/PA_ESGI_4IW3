const colors = require('tailwindcss/colors');

module.exports = {
  future: {},
  purge: [
      '.templates/**/*.html.twig'
  ],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [require('@tailwindcss/aspect-ratio')],
  theme: {
    extend: {
      colors: {
        orange: colors.orange,
        rose: colors.rose,
        cyan: colors.cyan,
      },
    },
  },
};

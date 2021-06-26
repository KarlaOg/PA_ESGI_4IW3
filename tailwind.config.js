const colors = require('tailwindcss/colors');

module.exports = {
  future: {},
  purge: ['./src/**/*.html', './src/**/*.vue', './src/**/*.jsx'],
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

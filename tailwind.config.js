const colors = require('tailwindcss/colors');

module.exports = {
  future: {},
  purge: {
    enabled: true,
  },
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

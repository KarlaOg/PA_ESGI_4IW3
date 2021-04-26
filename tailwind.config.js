const colors = require('tailwindcss/colors');

module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [require('@tailwindcss/aspect-ratio')],
  theme: {
    extend: {
      colors: {
        orange: colors.orange,
      },
    },
  },
};

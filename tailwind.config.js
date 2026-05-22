/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        muci: {
          green:        '#00B26B',
          'green-mid':  '#61B87C',
          'green-light':'#94CA9E',
          'green-pale': '#BCDDC0',
          orange:       '#F37043',
          'orange-mid': '#F18A5D',
          'orange-light':'#F6AA85',
          'orange-pale':'#FAC8AE',
          dark:         '#575756',
          gray:         '#878787',
          'gray-light': '#B2B2B2',
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      }
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

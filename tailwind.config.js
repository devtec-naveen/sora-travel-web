/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#ECFAFF',
          100: '#D5F2FF',
          200: '#B5EBFF',
          300: '#82E0FF',
          400: '#48CBFF',
          500: '#1DAEFF',
          600: '#068FFF',
          700: '#0078F6',
          800: '#075FC6',
          900: '#0D529B',
          950: '#0F3869',
        },
        secondary: {
          50: '#FDF9E9',
          100: '#FCF4C5',
          200: '#FBE48D',
          300: '#F8CF4C',
          400: '#F3B515',
          500: '#E39F0F',
          600: '#C4790A',
          700: '#9D560B',
          800: '#824411',
          900: '#6E3815',
          950: '#401C08',
        },
      }
    }
  },
  plugins: [],
}
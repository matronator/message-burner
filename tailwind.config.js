module.exports = {
  purge: [
    "./app/modules/Front/**/*.latte",
    "./app/modules/Front/**/*.js",
    "./dev/front/js/**/*.js",
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    screens: {
      "2xl": { 'max': "1536px" },
      xl: { 'max': "1392px" },
      lg: { 'max': "1024px" },
      md: { 'max': "768px" },
      sm: { 'max': "640px" },
    },
    container: {
      center: true,
    },
    fontSize: {
      button: [
        {
          fontSize: "11px",
          lineHeight: "22px",
          letterSpacing: "2px",
        },
      ],
      xs: ["12px", "22px"],
      sm: ["14px", "24px"],
      base: ["16px", "28px"],
      lead: ["22px", "42px"],
      lg: ["28px", "40px"],
      xl: ["38px", "50px"],
      "2xl": ["48px", "60px"],
      "3xl": ["58px", "70px"],
      "4xl": ["80px", "92px"],
      hd: ["120px", "132px"],
    },
    extend: {
      screens: {
        "3xl": "1920px",
      },
      gap: {
        30: "30px",
      },
      borderWidth: {
        half: "0.5px",
        quarter: "0.25px",
      },
      colors: {
        transparent: "transparent",
        current: "currentColor",
        dark: {
          DEFAULT: "#000000",
          1: "#131313",
          2: "#222222",
        },
        light: {
          DEFAULT: "#ffffff",
          1: "#f5f5f5",
          2: "#acacac",
        },
        grey: {
          3: "#ababab",
        },
        white: "#ffffff",
        yellow: {
          DEFAULT: "#ffc400",
          light: "#ffd06a",
          lighter: "#faeec7",
        },
        red: {
          DEFAULT: "#9f2934",
          danger: "#db0000",
          "danger-2": "#ff0000",
        },
        green: {
          DEFAULT: "#13C6B0",
        },
        orange: {
          DEFAULT: "#ff9216",
        },
        purple: {
          DEFAULT: "#44105C",
          light: "#A288E3",
        },
        primary: "#ffc400",
        secondary: "#ff9216",
        ternary: "#9f2934",
      },
      width: {
        55: "220px",
      },
      height: {
        55: "220px",
      },
    },
  },
  variants: {},
  plugins: [],
}

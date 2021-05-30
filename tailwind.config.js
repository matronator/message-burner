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
        "10px",
        {
          lineHeight: "22px",
          letterSpacing: "3px",
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
        black: {
          DEFAULT: "#000000",
          100: "#111111",
        },
        grey: {
          3: "#ababab",
        },
        white: "#ffffff",
        yellow: {
          DEFAULT: "#FFCE41",
        },
        red: {
          DEFAULT: "#E65069",
        },
        green: {
          DEFAULT: "#13C6B0",
        },
        orange: {
          DEFAULT: "#FABA71",
        },
        purple: {
          DEFAULT: "#44105C",
          light: "#A288E3",
        },
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

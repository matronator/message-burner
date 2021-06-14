module.exports = {
  // mode: 'jit',
  purge: [
    "./app/modules/Front/**/*.{js,css,html,latte}",
    "./dev/front/**/*.{js,css,html,latte}",
    // "./app/modules/Front/**/*.latte",
    // "./app/modules/Front/**/*.js",
    // "./dev/front/js/**/*.js",
  ],
  darkMode: 'class', // or 'media' or 'class'
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
          fontSize: "10px",
          lineHeight: "22px",
          letterSpacing: "1px",
          fontWeight: "600",
        },
      ],
      xs: ["12px", "22px"],
      sm: ["14px", "24px"],
      base: ["16px", "20px"],
      input: ["18px", "24px"],
      lead: ["22px", "42px"],
      lg: ["28px", "40px"],
      xl: ["38px", "50px"],
      "2xl": ["48px", "60px"],
      "3xl": ["58px", "70px"],
      "4xl": ["80px", "92px"],
      hd: ["120px", "132px"],
    },
    extend: {
      fontFamily: {
        'body': ['Jost', 'sans'],
      },
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
        light: {
          DEFAULT: "#ffffff",
          1: "#f5f5f5",
          2: "#acacac",
          3: "#757575",
        },
        grey: {
          DEFAULT: "#656666",
          darker: "#070A0A",
          dark: "#4F4F4F",
          light: "#B0AEAE",
          lighter: "#CFC2C2",
          3: "#ababab",
        },
        white: "#ffffff",
        yellow: {
          DEFAULT: "#FFC000",
          dark: "#d19e06",
          light: "#FCCB38",
          lighter: "#FFE387",
          lightest: "#FCEAAC",
        },
        red: {
          DEFAULT: "#C90A1D",
          darkest: "#4d0202",
          darker: "#990F1D",
          dark: "#A80314",
          light: "#EB0017",
          lighter: "#FA0F26",
          danger: "#db0000",
          "danger-2": "#ff0000",
        },
        green: {
          DEFAULT: "#13C6B0",
        },
        orange: {
          DEFAULT: "#FA7F0C",
          darker: "#DB6C04",
          dark: "#f77902",
          light: "#FA8A20",
          lighter: "#FCB674",
          lightest: "#fcf5de",
        },
        purple: {
          DEFAULT: "#9327A8",
          darker: "#4E1559",
          dark: "#5D046E",
          light: "#A324BD",
          lighter: "#D270E6",
        },
        blue: {
          DEFAULT: "#1C6CB8",
          darker: "#1D2461",
          dark: "#0A5194",
          light: "#4D9FEB",
          lighter: "#B5DBFF",
        },
        primary: "#A80314",
        secondary: "#FFC000",
        ternary: "#FA8A20",
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

// https://coolors.co/12355b-420039-d72638-ffffff-ff570a-d8c99b

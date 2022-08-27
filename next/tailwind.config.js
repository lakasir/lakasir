module.exports = {
  content: [
    "./pages/**/*.{js,ts,jsx,tsx}",
    "./components/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        lakasir : {
          primary: "#FF6600"
        }
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
}

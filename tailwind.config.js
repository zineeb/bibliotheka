/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue"
    ],
    safelist: [
        'bg-custom-red',
        'text-custom-yellow',
    ],
    theme: {
        extend: {
            colors: {
                'ffd234': '#FFD234',
            }
        },
    },
    plugins: [require("daisyui")],
}


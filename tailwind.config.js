/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php', // Ini adalah jalur utama untuk file Blade Anda
        './resources/js/**/*.js',
        './app/Filament/**/*.php', // Penting untuk Filament Resources
        './app/Forms/Components/**/*.php',
        './app/Tables/Columns/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', 'sans-serif'],
            },
            colors: {
                'primary-red': '#DC2626',
                'secondary-gray': '#4B5563',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'), // Plugin untuk styling form dasar
    ],
};
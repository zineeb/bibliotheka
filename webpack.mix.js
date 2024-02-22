const mix = require('laravel-mix');
const path = require('path');
const tailwindcss = require('tailwindcss');

mix.ts('resources/ts/app.ts', 'public/js')
    .vue({version: 3})
    .postCss('resources/css/app.css', 'public/css', [
        tailwindcss('./tailwind.config.js'),
    ])
    // .sass('resources/sass/app.scss', 'public/css')
    // .copy('node_modules/@fortawesome/fontawesome-free/webfonts','public/fonts');

mix.webpackConfig({
    resolve: {
        extensions: [".ts", ".tsx", ".js", ".jsx", ".vue", "*"],

        alias: {
            '@': path.resolve(__dirname, 'resources/ts/'),
            '@components': path.resolve(__dirname, 'resources/ts/components/'),
        }
    }
});

if (mix.inProduction()) {
    mix.version();
}

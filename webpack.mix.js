const mix = require('laravel-mix');
const path = require('path');

mix.ts('resources/ts/app.ts', 'public/js')
    .vue({version: 3})
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts','public/fonts');

mix.webpackConfig({
    resolve: {
        extensions: [".ts", ".tsx", ".js", ".jsx", ".vue", "*"],

        alias: {
            '@': path.resolve(__dirname, 'resources/ts/'),
            '@components': path.resolve(__dirname, 'resources/ts/components/'),
        }
    }
});

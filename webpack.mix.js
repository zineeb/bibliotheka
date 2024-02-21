const mix = require('laravel-mix');

mix.ts('resources/ts/app.ts', 'public/js')
    .vue({version: 3})
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts','public/fonts');


// const mix = require('laravel-mix');
//
// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');
//     // .postCss('resources/css/app.css', 'public/css', [
//     //     //
//     // ])
//
// mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fonts');

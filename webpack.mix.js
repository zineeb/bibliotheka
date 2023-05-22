const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
    // .postCss('resources/css/app.css', 'public/css', [
    //     //
    // ])

mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fonts');
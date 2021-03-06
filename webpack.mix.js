const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/admin.scss', 'public/css')
    .less('resources/assets/less/AdminLTE.less', 'public/css')
    .copy('resources/assets/js/jquery.nestable.js', 'public/js')
    .copy('resources/assets/js/fontawesome-iconpicker.js', 'public/js')
    .copy('node_modules/bootstrap/dist/css/bootstrap.css', 'public/css')
    .copy('resources/assets/css/style.css', 'public/css')
    .version();

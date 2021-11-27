const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts('public/cms-assets/plugins/global/plugins.bundle.js', 'public/cms-assets/plugins/global/plugins.bundle.js');
mix.postCss('public/cms-assets/css/style.bundle.css', 'public/cms-assets/css/style.bundle.css');

mix.minify(['public/cms-assets/plugins/global/plugins.bundle.js', 'public/cms-assets/css/style.bundle.css']);

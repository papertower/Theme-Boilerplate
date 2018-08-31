/**
 * This file sets up the compilation of JS and SASS for the project using Laravel Mix. That is a
 * framework built on top of Webpack, greatly simplifying doing the same thing in vanilla Webpack.
 *
 * Use `npm run dev` to compile once
 * Use `npm run watch` to watch the files, compile, and refresh the proxy below
 * Use `npm run production` to compile the files for production
 *
 * @link https://github.com/JeffreyWay/laravel-mix/tree/master/docs
 */

const mix = require('laravel-mix');
var LiveReloadPlugin = require('webpack-livereload-plugin');

// Setup asset compilation
mix
    .js('assets/js/front-scripts.js', 'assets/dist/')
    .js('assets/js/admin-scripts.js', 'assets/dist/')

    .sass('assets/scss/style.scss', 'assets/dist/')
    .sass('assets/scss/admin.scss', 'assets/dist/')

    .options({
        processCssUrls: false
    });

// Refresh the browser at the following domain when files change
mix.browserSync({
    proxy:  'http://theme-boilerplate.local/',
    files:  [
        '**/*.php',
        'style.css',
        'dist/app.js',
    ],
    open:   false,
    notify: false,
});

// Load jQuery into every script and resolve package aliases
mix.autoload({
    jQuery: ['$', 'window.jQuery'],
})
    .webpackConfig({
        externals: {
            jQuery: 'jQuery',
            jquery: 'jQuery'
        },
        resolve:   {
            alias: {
                'handlebars':    'handlebars/dist/handlebars.js',
                'select2-css':   'select2/dist/css/select2.min.css',
                'flatpickr-css': 'flatpickr/dist/flatpickr.min.css',
            }
        },
        plugins:   [
            new LiveReloadPlugin()
        ]
    });

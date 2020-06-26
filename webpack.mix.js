const path = require('path');
const fs = require('fs-extra');
const mix = require('laravel-mix');

mix
    .js('resources/js/app.js', 'public/dist/js')
    .sass('resources/sass/app.scss', 'public/dist/css')
    .disableNotifications();

if (mix.inProduction()) {
    // Use `laravel-mix-versionhash` to generate hashed file names (instead of query strings).
    require('laravel-mix-versionhash');    
    mix.versionHash();
    
} else {
    mix.sourceMaps();
}

mix.webpackConfig({
    plugins: [],
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '~': path.join(__dirname, './resources/js')
        }
    },
    output: {
        chunkFilename: 'dist/js/[chunkhash].js',
        path: mix.config.hmr ? '/' : path.resolve(__dirname, './public/build')
    }
});

mix.then(() => {
    if (!mix.config.hmr) {
        process.nextTick(() => publishAssets())
    }
});

function publishAssets() {
    const publicDir = path.resolve(__dirname, './public');

    if (mix.inProduction()) {
        fs.removeSync(path.join(publicDir, 'dist'))
    }

    fs.copySync(path.join(publicDir, 'build', 'dist'), path.join(publicDir, 'dist'));
    fs.removeSync(path.join(publicDir, 'build'));
}

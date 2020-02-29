// webpack.config.js
const path = require('path');
const webpack = require('webpack');

const PATHS = {
    source: path.join(__dirname, 'resources/js'),
    build: path.join(__dirname, 'web')
};

const {VueLoaderPlugin} = require('vue-loader');

module.exports = (env, argv) => {
    let config = {
        production: argv.mode === 'production'
    };

    return {
        mode: 'development',
        entry: [
            './resources/js/app.js'
        ],
        output: {
            path: PATHS.build,
            filename: config.production ? 'assets/inertia/js/app.min.js' : 'assets/inertia/js/app.js'
        },
        resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
                '@': '/' + path.resolve(__dirname, 'resources/js')
            }
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    use: 'vue-loader'
                },
                {
                    test: /\.css$/,
                    loader: ['style-loader', 'css-loader']
                }
            ]
        },
        plugins: [
            new VueLoaderPlugin()
        ]
    };
};

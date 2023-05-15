const path = require('path');
const webpack = require('webpack');
const ManifestPlugin = require('webpack-manifest-plugin');
require('graceful-fs').gracefulify(require('fs'));

let ASSET_PATH = '';
module.exports = env => {

    let DOMAIN = JSON.stringify('https://localhost/evento');
    if (env.NODE_ENV === 'evento') {
        DOMAIN = JSON.stringify('https://sampel.com.br/evento');
        ASSET_PATH = '';
    }

    return {
        entry: {
            main: './view/src/js/init.js',
        },
        output: {
            filename: "[name].[contenthash].js",
            path: path.resolve(__dirname, 'view/src/dist'),
            publicPath: ASSET_PATH + '/view/src/dist/',
        },
        plugins: [
            new webpack.DefinePlugin({
                DOMAIN,
            }),
            new ManifestPlugin(),
        ],
        devtool: false,
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /nodemodules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },
            ]
        },
        resolve: {
            extensions: ['.js'],
        },
        optimization: {
            minimize: true,
        },
    }
}
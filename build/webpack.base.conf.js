'use strict';

const autoprefixer = require('autoprefixer');
const path = require('path');
const eslintFriendlyFormatter = require('eslint-friendly-formatter');

function resolve(dir) {
    return path.resolve(__dirname, '..', dir);
};

module.exports = {
    entry: {
        cart_history: resolve('formwidgets/carthistory/components'),
        categories: resolve('controllers/categories'),
        driver_configs: resolve('formwidgets/driverconfigs/components'),
        options_inventories: resolve('formwidgets/optionsinventories/components'),
        products: resolve('controllers/products'),
        relationships: resolve('formwidgets/relationships/components'),
        status_selector: resolve('formwidgets/statusselector'),
    },
    output: {
        filename: '[name].min.js',
        path: resolve('assets/dist'),
        publicPath: '/plugins/bedard/shop/assets/dist',
    },
    resolve: {
        extensions: ['.js', '.vue', '.scss'],
        modules: [
            resolve(''),
            resolve('assets/scss'),
            resolve('node_modules'),
        ],
    },
    module: {
        rules: [
            {
                test: /\.(js|vue)$/,
                loader: 'eslint-loader',
                enforce: "pre",
                include: [
                    resolve(''),
                ],
                options: {
                    fix: true,
                    formatter: eslintFriendlyFormatter,
                },
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        'scss': 'style-loader!css-loader!sass-loader?includePaths[]=' + resolve('assets/scss'),
                    },
                    postcss: [
                        autoprefixer({ browsers: ['last 2 versions'] }),
                    ],
                },
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                include: [
                    resolve(''),
                ],
                exclude: /node_modules/,
            },
            {
                test: /\.json$/,
                loader: 'json-loader',
            },
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: resolve('assets/dist/img/[name].[ext]'),
                },
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: resolve('assets/dist/fonts/[name].[ext]'),
                },
            },
            {
                test: /\.scss$/,
                loaders: [
                    {
                        loader: 'style-loader',
                    },
                    {
                        loader: 'css-loader',
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            includePaths: [
                                resolve('assets/scss'),
                            ],
                        },
                    },
                ],
            },
        ],
    },
};

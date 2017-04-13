'use strict';

const autoprefixer = require('autoprefixer');
const path = require('path');
const eslintFriendlyFormatter = require('eslint-friendly-formatter');

module.exports = {
    entry: {
        cart_history: path.resolve(__dirname, '../formwidgets/carthistory/components'),
        categories: path.resolve(__dirname, '../controllers/categories'),
        driver_configs: path.resolve(__dirname, '../formwidgets/driverconfigs/components'),
        options_inventories: path.resolve(__dirname, '../formwidgets/optionsinventories/components'),
        products: path.resolve(__dirname, '../controllers/products'),
        relationships: path.resolve(__dirname, '../formwidgets/relationships/components'),
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, '../assets/dist'),
        publicPath: '/plugins/bedard/shop/assets/dist',
    },
    resolve: {
        extensions: ['.js', '.vue', '.scss'],
        modules: [
            path.resolve(__dirname, '../'),
            path.resolve(__dirname, '../assets/scss'),
            path.resolve(__dirname, '../node_modules'),
        ],
    },
    module: {
        rules: [
            {
                test: /\.(js|vue)$/,
                loader: 'eslint-loader',
                enforce: "pre",
                include: [
                    path.resolve(__dirname, '../'),
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
                        'scss': 'style-loader!css-loader!sass-loader?includePaths[]=' + path.resolve(__dirname, '../assets/scss'),
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
                    path.resolve(__dirname, '../'),
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
                    name: path.resolve(__dirname, '../assets/dist/img/[name].[ext]'),
                },
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: path.resolve(__dirname, '../assets/dist/fonts/[name].[ext]'),
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
                                path.resolve(__dirname, '../assets/scss'),
                            ],
                        },
                    },
                ],
            },
        ],
    },
};

'use strict';

const path = require('path');

module.exports = {
    entry: {
        categories: path.resolve(__dirname, '../controllers/categories'),
        products: path.resolve(__dirname, '../controllers/products'),
        options_inventories: path.resolve(__dirname, '../formwidgets/optionsinventories/components'),
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
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        'scss': 'style!css!sass',
                    },
                    postcss: [
                        require('autoprefixer')({ browsers: ['last 2 versions'] }),
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
                    'style-loader',
                    'css-loader',
                    'sass-loader',
                ],
            },
        ],
    },
};

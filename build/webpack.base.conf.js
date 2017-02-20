'use strict';

const path = require('path');

module.exports = {
    entry: {
        categories: path.resolve(__dirname, '../controllers/categories'),
        products: path.resolve(__dirname, '../controllers/products'),
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, '../assets/dist'),
        publicPath: '/plugins/bedard/shop/assets/dist',
    },
    resolve: {
        extensions: ['.js', '.scss'],
        modules: [
            path.resolve(__dirname, '../'),
            path.resolve(__dirname, '../node_modules'),
        ],
        alias: {
            'assets': path.resolve(__dirname, '../assets'),
        },
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                include: [
                    path.resolve(__dirname, '../'),
                ],
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

'use strict';

const { resolve } = require('./utilities');
const path = require('path');

module.exports = {
    entry: {
        app: path.resolve(__dirname, '../assets/shop.js'),
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, '../assets/dist'),
    },
    resolve: {
        extensions: ['.js'],
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
        ],
    },
};

'use strict';

const merge = require('webpack-merge');
const webpack = require('webpack');
const webpackConfig = require('./webpack.base.conf');
const FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin');

// @todo: add hot-reload related code to entry chunks
Object.keys(webpackConfig.entry).forEach(function (name) {
    webpackConfig.entry[name] = ['./build/dev-client'].concat(webpackConfig.entry[name]);
});

module.exports = merge(webpackConfig, {
    devtool: '#cheap-module-eval-source-map',
    plugins: [
        // set our node environment to "development"
        new webpack.DefinePlugin({ 'process.env': { NODE_ENV: '"development"' }}),

        // @todo: turn on hot reloading
        new webpack.HotModuleReplacementPlugin(),

        // disable default errors and replace them with friendly errors
        new webpack.NoEmitOnErrorsPlugin(),
        new FriendlyErrorsPlugin(),
    ],
});

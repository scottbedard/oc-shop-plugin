var merge = require('webpack-merge');
var path = require('path');
var webpackBaseConfig = require('./webpack.base.conf');
var webpack = require('webpack');

// add hot-reload related code to entry chunks
Object.keys(webpackBaseConfig.entry).forEach(function (name) {
    webpackBaseConfig.entry[name] = ['./build/dev_client'].concat(webpackBaseConfig.entry[name])
})

module.exports = merge(webpackBaseConfig, {
    devtool: '#eval-source-map',
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"development"'
            },
        }),
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NoErrorsPlugin(),
    ],
});

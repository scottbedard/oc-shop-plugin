var merge = require('webpack-merge');
var path = require('path');
var webpackBaseConfig = require('./webpack.base.conf');
var webpack = require('webpack');

module.exports = merge(webpackBaseConfig, {
    devtool: '#eval-source-map',
    plugins: [

    ],
});

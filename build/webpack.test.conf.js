var baseConfig = require('./webpack.base.conf');
var merge = require('webpack-merge');
// var utils = require('./utils');
var webpack = require('webpack');

var webpackConfig = merge(baseConfig, {
    // use inline sourcemap for karma-sourcemap-loader
    devtool: '#inline-source-map',
    module: {
        // rules: utils.styleLoaders(),
    },
    plugins: [
        // set our node environment to "test"
        new webpack.DefinePlugin({ 'process.env': { NODE_ENV: '"test"' }}),
    ],
    resolve: {
        alias: {
            // include the template compiler in our test environment
            'vue$': 'vue/dist/vue.common.js',
        },
    },
});

// no need for app entry during tests
delete webpackConfig.entry;

module.exports = webpackConfig;

'use strict';

const merge = require('webpack-merge');
const path = require('path');
const webpack = require('webpack');
const webpackConfig = require('./webpack.base.conf');

module.exports = merge(webpackConfig, {
    devtool: '#source-map',
    plugins: [
        // set our node environment to "production"
        new webpack.DefinePlugin({ 'process.env': { NODE_ENV: '"production"' }}),

        // minify our javascript
        new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }}),

        // @todo: extract css chunks into their own files
        // new ExtractTextPlugin("style.css"),

        // only use the parts of moment we need
        new webpack.ContextReplacementPlugin(/moment[\\\/]locale$/, /^\.\/(en)$/),

        // extract core dependencies into their own bundle
        new webpack.optimize.CommonsChunkPlugin({
            name: 'vendor',
            minChunks: function (module) {
                return module.resource && /\.js$/.test(module.resource) && (
                    module.resource.indexOf(path.join(__dirname, '../assets')) === 0 ||
                    module.resource.indexOf(path.join(__dirname, '../node_modules')) === 0
                );
            },
        }),
    ],
});

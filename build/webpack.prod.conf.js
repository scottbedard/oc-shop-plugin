var ExtractTextPlugin = require("extract-text-webpack-plugin");
var merge = require('webpack-merge');
var path = require('path');
var utils = require('./utils');
var webpackBaseConfig = require('./webpack.base.conf');
var webpack = require('webpack');

module.exports = merge(webpackBaseConfig, {
    devtool: '#source-map',
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            },
        }),

        // minify javascript
        new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }}),

        // @todo: extract css chunks into their own files
        // new ExtractTextPlugin("style.css"),

        // extract node_module dependencies into their own bundle
        new webpack.optimize.CommonsChunkPlugin({
            name: 'vendor',
            minChunks: function (module) {
                return module.resource
                    && /\.js$/.test(module.resource)
                    && (
                        module.resource.indexOf(path.join(__dirname, '../assets')) === 0 ||
                        module.resource.indexOf(path.join(__dirname, '../node_modules')) === 0
                    );
            }
        }),
    ],
});

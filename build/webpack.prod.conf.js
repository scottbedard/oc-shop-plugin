var merge = require('webpack-merge');
var path = require('path');
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

        // extract node_module dependencies into their own bundle
        new webpack.optimize.CommonsChunkPlugin({
            name: 'vendor',
            minChunks: function (module) {
                return module.resource
                    && /\.js$/.test(module.resource)
                    && module.resource.indexOf(path.join(__dirname, '../node_modules')) === 0;
            }
        }),
    ],
});

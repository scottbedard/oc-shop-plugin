var express = require('express');
var path = require('path');
var proxyMiddleware = require('http-proxy-middleware');
var proxyTable = require('./dev_proxy');
var webpack = require('webpack');
var webpackConfig = require('./webpack.dev.conf');

var app = express();
var compiler = webpack(webpackConfig);

var devMiddleware = require('webpack-dev-middleware')(compiler, {
    publicPath: webpackConfig.output.publicPath,
    stats: {
        colors: true,
        chunks: false,
    },
});

Object.keys(proxyTable).forEach(function (context) {
    var options = proxyTable[context];
    if (typeof options === 'string') {
        options = { target: options };
    }

    app.use(proxyMiddleware(context, options));
});

app.use(devMiddleware);

var port = 8080;
module.exports = app.listen(port, function (err) {
    if (err) {
        console.log(err);
        return;
    }

    console.log('Listening at http://localhost:' + port + '\n');
});

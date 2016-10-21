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

var hotMiddleware = require('webpack-hot-middleware')(compiler);

Object.keys(proxyTable).forEach(function (context) {
    var options = proxyTable[context];
    if (typeof options === 'string') {
        options = { target: options };
    }

    app.use(proxyMiddleware(context, options));
});

var filter = function (pathname) {
    return pathname.match(/^(?!\/__webpack).*$/) && pathname.indexOf('bedard/shop/assets') === -1;
};

app.use(proxyMiddleware(filter, { target: 'http://www.beeasyboards.dev' }));

// app.use(proxyMiddleware([
//     '!__webpack_*',
//     '*(backend|plugins|modules)/**/*'
// ], { target: 'http://beeasyboards.dev' }));

app.use(devMiddleware);

app.use(hotMiddleware);

var port = 8080;
module.exports = app.listen(port, function (err) {
    if (err) {
        console.log(err);
        return;
    }

    console.log('Listening at http://localhost:' + port + '\n');
});

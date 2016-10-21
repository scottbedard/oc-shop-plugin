var express = require('express');
var fs = require('fs');
var path = require('path');
var proxyMiddleware = require('http-proxy-middleware');
var webpack = require('webpack');
var webpackConfig = require('./webpack.dev.conf');

// before we do anything, make sure the dev environment has been properly configured
try {
    fs.statSync(path.resolve(__dirname, '../.october_proxy')).isFile();
} catch (e) {
    console.error('    Error: An .october_proxy file must be created to use the dev server.');
    process.exit(1);
}

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

// proxy our shared bundle to prevent annoying 404 errors
app.use(proxyMiddleware('**/bedard/shop/assets/js/{manifest,vendor}.js', { target: '/' }));

// and proxy everything else we don't need to our October site
var octoberProxy = fs.readFileSync(path.resolve(__dirname, '../.october_proxy'), 'utf8');
app.use(proxyMiddleware(function(pathname) {
    return pathname.indexOf('__webpack') === -1 && pathname.indexOf('bedard/shop/assets') === -1;
}, { target: octoberProxy }));

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

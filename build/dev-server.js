'use strict';

const chalk = require('chalk');
const express = require('express');
const fs = require('fs');
const path = require('path');
const webpack = require('webpack');
const webpackConfig = require('./webpack.dev.conf');
const proxyMiddleware = require('http-proxy-middleware');

// set our node environment
process.env.NODE_ENV = 'development';

// make sure the .october_proxy file is present
let octoberProxy;

try {
    fs.statSync(path.resolve(__dirname, '../.october_proxy')).isFile();
    octoberProxy = fs.readFileSync(path.resolve(__dirname, '../.october_proxy'), 'utf8');
} catch (e) {
    console.log();
    console.log(chalk.red('  Error:') + ' An .october_proxy file must be created to use the dev server.');
    console.log();
    console.log(chalk.gray('  For more information, visit the following URL...'));
    console.log(chalk.gray('  https://github.com/scottbedard/oc-shop-plugin#local-development'));
    console.log();
    process.exit();
}

// create a server for our development assets
const app = express();
const compiler = webpack(webpackConfig);

const devMiddleware = require('webpack-dev-middleware')(compiler, {
    publicPath: webpackConfig.output.publicPath,
    quiet: true,
});

const hotMiddleware = require('webpack-hot-middleware')(compiler, {
    log: function() { /* this turns off standard error messages */ },
});

// proxy our vendor assets to void.js to silence annoying 404 errors
app.use(proxyMiddleware('/plugins/bedard/shop/assets/dist/vendor.min.js', {
    target: octoberProxy,
    pathRewrite: function() {
        return '/plugins/bedard/shop/assets/js/void.js';
    },
}));

// and proxy everything else to our October site
app.use(proxyMiddleware(
    function(pathname) {
        return pathname.indexOf('__webpack') === -1 && pathname.indexOf('bedard/shop/assets') === -1;
    },
    { target: octoberProxy }
));

app.use(devMiddleware);

app.use(hotMiddleware);

// fire up the dev server
module.exports = app.listen(8080, function (err) {
    if (err) {
        console.log(err);
        return;
    }

    console.log('Listening at http://localhost:8080' + '\n');
});

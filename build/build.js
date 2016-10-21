#!/usr/bin/env node
require('shelljs/global');
env.NODE_ENV = 'production';

var ora = require('ora');
var path = require('path');
var webpack = require('webpack');
var webpackConfig = require('./webpack.prod.conf');


var spinner = ora('building production assets...');
spinner.start();

// remove the old assets
var assetsPath = path.resolve(__dirname, '../assets/dist');
rm('-rf', assetsPath)
mkdir('-p', assetsPath)

webpack(webpackConfig, function(err, stats) {
    spinner.stop();
    if (err) {
        throw err;
    }

    process.stdout.write(stats.toString({
        children: false,
        chunkModules: false,
        chunks: false,
        colors: true,
        modules: false,
    }) + '\n');
});

'use strict';

const chalk = require('chalk');
const ora = require('ora');
const path = require('path');
const shell = require('shelljs');
const webpack = require('webpack');
const webpackConfig = require('./webpack.prod.conf');

// set our node environment
process.env.NODE_ENV = 'production';

// start our spinner
const spinner = ora('building for production...');
spinner.start();

// remove our old compiled assets
const dist = path.resolve(__dirname, '../assets/dist');
shell.rm('-rf', dist);
shell.mkdir('-p', dist);

// use webpack to compile our new production assets
webpack(webpackConfig, function (err, stats) {
    spinner.stop();

    if (err) {
        throw err;
    }

    // write the compiled files to the /dist directory
    process.stdout.write(stats.toString({
        children: false,
        chunkModules: false,
        chunks: false,
        colors: true,
        modules: false,
    }) + '\n');

    console.log ('done');
});

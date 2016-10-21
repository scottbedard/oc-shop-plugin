#!/usr/bin/env node
var webpack = require('webpack');
var webpackConfig = require('./webpack.prod.conf');

webpack(webpackConfig, function(err) {
    console.log ('done');
});

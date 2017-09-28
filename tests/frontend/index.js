// polyfill fn.bind() for phantomjs
Function.prototype.bind = require('function-bind');

// define our global variables
require('./globals');

// boot our vue app
require('assets/js/boot');

// require all test files (files that ends with .spec.js)
const testsContext = require.context('./specs', true, /\.spec$/);
testsContext.keys().forEach(testsContext);

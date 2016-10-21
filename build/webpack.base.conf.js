var path = require("path");

module.exports = {
    entry: {
        treesort: path.resolve(__dirname, '../controllers/categories/treesort/index.js'),
    },
    output: {
        filename: '[name].js',
        path: path.join(__dirname, '../assets'),
        publicPath: '/plugins/bedard/shop/assets/',
    },
    module: {
        loaders: [
            {
                test: /\.vue$/,
                loader: 'vue',
            },
            {
                test: /\.js$/,
                loader: 'babel',
                exclude: /node_modules/
            },
        ],
    },
};

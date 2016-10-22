var path = require("path");
var utils = require("./utils");

module.exports = {
    entry: {
        main: path.resolve(__dirname, '../assets/js/main.js'),
        categories: path.resolve(__dirname, '../controllers/categories/index.js'),
    },
    output: {
        filename: '[name].js',
        path: path.join(__dirname, '../assets/dist'),
        publicPath: '/plugins/bedard/shop/assets/dist',
    },
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            'assets': path.resolve(__dirname, '../assets'),
        },
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
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue',
                options: {
                    loaders: utils.cssLoaders({
                        sourceMap: true,
                        extract: false,
                    }),
                    postcss: [
                        require('autoprefixer')({
                            browsers: ['last 10 versions']
                        }),
                    ],
                },
            },
            {
                test: /\.js$/,
                loader: 'babel',
                exclude: /node_modules/
            },
        ],
    },
};

var path = require("path");
var utils = require("./utils");

module.exports = {
    entry: {
        categories: path.resolve(__dirname, '../controllers/categories/index.js'),
    },
    output: {
        filename: '[name].js',
        path: path.join(__dirname, '../assets/dist'),
        publicPath: '/plugins/bedard/shop/assets/dist',
    },
    resolve: {
        extensions: ['.js', '.scss', '.vue'],
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
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                loader: 'url',
                query: {
                    limit: 10000,
                    name: path.resolve(__dirname, '../assets/img/[name].[ext]'),
                },
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url',
                query: {
                    limit: 10000,
                    name: path.resolve(__dirname, '../assets/fonts/[name].[ext]'),
                },
            },
            {
                test: /\.scss$/,
                loaders: ['style', 'css', 'sass'],
            },
        ],
    },
};

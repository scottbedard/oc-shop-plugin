var path = require("path");
var utils = require("./utils");

module.exports = {
    entry: {
        categories: path.resolve(__dirname, '../controllers/categories/index.js'),
        driverconfig: path.resolve(__dirname, '../formwidgets/driverconfig/assets/js/driverconfig.js'),
        customers: path.resolve(__dirname, '../controllers/customers/index.js'),
        discounts: path.resolve(__dirname, '../controllers/discounts/index.js'),
        products: path.resolve(__dirname, '../controllers/products/index.js'),
        promotions: path.resolve(__dirname, '../controllers/promotions/index.js'),
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
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
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
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: path.resolve(__dirname, '../assets/img/[name].[ext]'),
                },
            },
            {
                test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'url-loader',
                query: {
                    limit: 10000,
                    name: path.resolve(__dirname, '../assets/fonts/[name].[ext]'),
                },
            },
            {
                test: /\.scss$/,
                loaders: ['style-loader', 'css-loader', 'sass-loader'],
            },
        ],
    },
};

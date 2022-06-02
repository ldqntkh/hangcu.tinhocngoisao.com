/*
    ./webpack.config.js
*/
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const devMode = process.env.NODE_ENV !== 'production';
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const CopyPlugin = require('copy-webpack-plugin');

module.exports = {
    devtool: "source-map",
    entry: {
        // 'wp-content/themes/online-shop-child/assets/js/app' : './wp-content/themes/online-shop-child/private/javascripts/app.js',
        // 'wp-content/themes/online-shop-child/assets/js/sliderPage' : './wp-content/themes/online-shop-child/private/javascripts/sliderPage.js',
        // "wp-content/themes/online-shop-child/custom-style" : "./wp-content/themes/online-shop-child/private/scss/style.scss",
        // "wp-content/themes/online-shop-child/assets/js/bundle" : "./wp-content/themes/online-shop-child/private/reactSrc/App.js",
        // "wp-content/themes/online-shop-child/assets/js/build-pc" : "./wp-content/plugins/woocommerce-build-pc/private/reactjs/App.js",
        // "wp-content/themes/online-shop-child/assets/js/primetime" : "./wp-content/plugins/woocommerce-hotdeal/assets/reactjs/App.js",
        "wp-content/plugins/woocommerce-build-pc/assets/js/build-pc-bm" : "./wp-content/plugins/woocommerce-build-pc/private/reactjsBM/App.js",

        'wp-content/plugins/sale-installment/assets/css/star-brands': './wp-content/plugins/sale-installment/private/scss/style.scss',
        'wp-content/plugins/sale-installment/assets/js/star-app': './wp-content/plugins/sale-installment/private/javascripts/app.js',

        "wp-content/themes/martfury-child/assets/css/custom-style" : "./wp-content/themes/martfury-child/private/scss/style.scss",
        "wp-content/themes/martfury-child/assets/js/bundle" : "./wp-content/themes/martfury-child/private/reactSrc/App.js",
        'wp-content/themes/martfury-child/assets/js/app': './wp-content/themes/martfury-child/private/javascripts/app.js',
        // sale accessories
        'wp-content/plugins/thns-sale-accessories/assets/js/sale-accessories': './wp-content/plugins/thns-sale-accessories/private/javascripts/app.js',
        'wp-content/plugins/thns-sale-accessories/assets/js/sale-accessories-storefront': './wp-content/plugins/thns-sale-accessories/private/javascripts/storefront/app.js',
        'wp-content/plugins/thns-sale-accessories/assets/css/sale-accessories': './wp-content/plugins/thns-sale-accessories/private/scss/style.scss',
        'wp-content/plugins/thns-sale-accessories/assets/css/sale-accessories-storefront': './wp-content/plugins/thns-sale-accessories/private/scss/style-storefront.scss',
        // compare
        'wp-content/plugins/compare-products/assets/js/product_compare': './wp-content/plugins/compare-products/private/js/app.js',
        'wp-content/plugins/compare-products/assets/css/product_compare': './wp-content/plugins/compare-products/private/scss/style.scss',
    },
    output: {
        path: path.resolve(__dirname),
        publicPath: "/",
        filename: '[name].js',
        chunkFilename: "./wp-content/themes/martfury-child/assets/js/[name].chunk.js"
    },
    mode: devMode ? 'development' : 'production',
    module: {
        rules: [
            {
                test: /\.s?[ac]ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    { loader: 'css-loader', options: { url: false, sourceMap: true } },
                    { loader: 'sass-loader', options: { sourceMap: true } }
                ],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: "babel-loader"
            }, {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                use: "babel-loader"
            },
            {
                test: /\.(png|jpg|gif)$/,
                exclude: /node_modules/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: './[path][name].[ext]',
                        emitFile: false
                    }
                }]
            }
        ]
    },
    plugins: [
        new CopyPlugin([
            { from: './wp-content/themes/martfury-child/private/assets', to: './wp-content/themes/martfury-child/assets' },
            // compare
            { from: './wp-content/plugins/compare-products/private/assets', to: './wp-content/plugins/compare-products/assets' },
        ]),
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({
            // online theme
            // filename: devMode ? './wp-content/themes/martfury-child/assets/styles/[name].css' : './wp-content/themes/martfury-child/[name].[hash].css',
            // chunkFilename: devMode ? './wp-content/themes/martfury-child/assets/styles/[id].css' : './wp-content/themes/martfury-child/[id].[hash].css'

            // electro theme
            filename: devMode ? '[name].css' : '[name].[hash].css',
            chunkFilename: devMode ? '[id].css' : '[id].[hash].css'
        })
    ],
    devtool: devMode ? 'inline-source-map' : false,
    optimization: {
        namedModules: true,
        namedChunks: true
    },
    performance: {
        hints: false,
        maxEntrypointSize: 512000,
        maxAssetSize: 512000
    }
}
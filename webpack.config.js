const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const devMode = process.env.NODE_ENV !== 'production';
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const CopyPlugin = require('copy-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const cssnano = require( 'cssnano' ); 
const OptimizeCssAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );
module.exports = {
    entry: {
        // ----------------------Plugins------------------------------------
        // 'wp-content/plugins/gearvn-compare-products/assets/js/product_compare': './wp-content/plugins/gearvn-compare-products/private/js/app.js',
        // 'wp-content/plugins/gearvn-compare-products/assets/css/product_compare': './wp-content/plugins/gearvn-compare-products/private/scss/style.scss',
        // 'wp-content/plugins/gearvn-images-360/assets/js/product_images_360': './wp-content/plugins/gearvn-images-360/private/javascripts/app.js',
        // 'wp-content/plugins/gearvn-assign-products-category/assets/javascripts/modalAssignedProduct': './wp-content/plugins/gearvn-assign-products-category/private/javascripts/app.js',
        // 'wp-content/plugins/gearvn-assign-products-category/assets/styles/gearvn-assign-product': './wp-content/plugins/gearvn-assign-products-category/private/scss/style.scss',
        
        // 'wp-content/plugins/gearvn-sale-accessories/assets/js/sale-accessories': './wp-content/plugins/gearvn-sale-accessories/private/javascripts/app.js',
        // 'wp-content/plugins/gearvn-sale-accessories/assets/js/sale-accessories-storefront': './wp-content/plugins/gearvn-sale-accessories/private/javascripts/storefront/app.js',
        // 'wp-content/plugins/gearvn-sale-accessories/assets/css/sale-accessories': './wp-content/plugins/gearvn-sale-accessories/private/scss/style.scss',
        // 'wp-content/plugins/gearvn-sale-accessories/assets/css/sale-accessories-storefront': './wp-content/plugins/gearvn-sale-accessories/private/scss/style-storefront.scss',
        // 'wp-content/plugins/gearvn-brands/assets/css/gearvn-brands': './wp-content/plugins/gearvn-brands/private/scss/style.scss',
        // 'wp-content/plugins/gearvn-brands/assets/js/gearvn-app': './wp-content/plugins/gearvn-brands/private/javascripts/app.js',


        // ---------------------GEARVN Electro theme--------------------------------
        'wp-content/themes/hangcu/assets/javascript/app': './wp-content/themes/hangcu/private/javascript/app.js',
        "wp-content/themes/hangcu/assets/styles/custom-style": "./wp-content/themes/hangcu/private/scss/style.scss",
        'wp-content/themes/hangcu/assets/admin/javascripts/app': './wp-content/themes/hangcu/private/admin/javascripts/app.js',
        "wp-content/themes/hangcu/assets/admin/styles/custom-admin-style": "./wp-content/themes/hangcu/private/admin/scss/style.scss",
        'wp-content/themes/hangcu/assets/javascript/react-installment': './wp-content/themes/hangcu/private/react-app/installment/App.js',
        'wp-content/themes/hangcu/assets/javascript/react-account': './wp-content/themes/hangcu/private/react-app/account/AppAccount.js',
        'wp-content/themes/hangcu/assets/javascript/react-mb-account': './wp-content/themes/hangcu/private/react-app/account/AppMBAccount.js',
        // mobile
        "wp-content/themes/hangcu/assets/styles/mb-custom-style": "./wp-content/themes/hangcu/private/scss/mobile/mb-style.scss",
        'wp-content/themes/hangcu/assets/javascript/mb-app': './wp-content/themes/hangcu/private/javascript/mobile/mb-app.js',
    },
    output: {
        path: path.resolve(__dirname),
        publicPath: "/",
        filename: '[name].js',
        chunkFilename: "./wp-content/themes/hangcu/assets/javascript/[name].chunk.js"
    },
    mode: devMode ? 'development' : 'production',
    module: {
        rules: [
            {
                test: /\.s?[ac]ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    { loader: 'css-loader', options: { url: false, sourceMap: true, importLoaders: 2 } },
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
            // electro theme
            // { from: './wp-content/themes/electro-child/private/assets', to: './wp-content/themes/electro-child/assets' },
            // { from: './wp-content/themes/electro-child/private/admin/assets', to: './wp-content/themes/electro-child/assets/admin' },

            // gearvn theme
            { from: './wp-content/themes/hangcu/private/assets', to: './wp-content/themes/hangcu/assets' },
            { from: './wp-content/themes/hangcu/private/assets', to: './wp-content/themes/hangcu/assets' },
            { from: './wp-content/themes/hangcu/private/admin/assets', to: './wp-content/themes/hangcu/assets/admin' },

            { from: './wp-content/plugins/gearvn-compare-products/private/assets', to: './wp-content/plugins/gearvn-compare-products/assets' },
            { from: './wp-content/plugins/gearvn-images-360/private/assets', to: './wp-content/plugins/gearvn-images-360/assets' },
            { from: './wp-content/plugins/gearvn-documents/private/assets', to: './wp-content/plugins/gearvn-documents/assets' },
        ]),
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({
            // online theme
            // filename: devMode ? './wp-content/themes/online-shop-child/assets/styles/[name].css' : './wp-content/themes/online-shop-child/[name].[hash].css',
            // chunkFilename: devMode ? './wp-content/themes/online-shop-child/assets/styles/[id].css' : './wp-content/themes/online-shop-child/[id].[hash].css'

            // electro theme
            filename: devMode ? '[name].css' : '[name].css',
            chunkFilename: devMode ? '[id].css' : '[id].css'
        })
    ],
    optimization: {
        minimizer: [
            new TerserPlugin(),
            new UglifyJsPlugin( {
                cache: false,
                parallel: true,
                sourceMap: false
            } )
        ],
        namedModules: true,
        namedChunks: true
    },
    devtool: devMode ? 'inline-source-map' : false,
    performance: {
        hints: false,
        maxEntrypointSize: 512000,
        maxAssetSize: 512000
    }
}

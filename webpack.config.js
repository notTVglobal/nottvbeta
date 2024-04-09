// This is for PHPStorm to resolve the alias path.

const path = require("path");
const webpack = require('webpack');

module.exports = {
    mode: 'development',
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        fallback: {
            'path': require.resolve('path-browserify'),
            // "path": false
        },
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@i': path.resolve('public/images'),
        },
    },
    plugins: [
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: JSON.stringify(true),
            __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false)
        }),
    ],
    optimization: {
        usedExports: true,
    },
    stats: {
        children: true, // This will provide details from child compilations
    },
};

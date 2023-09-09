// This is for PHPStorm to resolve the alias path.

const path = require("path");

module.exports = {
    mode: 'development',
    resolve: {
        fallback: {
            'path': require.resolve('path-browserify'),
            // "path": false
        },
        alias: {
            '@': path.resolve('resources/js'),
            '@i': path.resolve('public/images'),
        },
    },
};

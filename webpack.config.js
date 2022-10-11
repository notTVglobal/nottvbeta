// This is for PHPStorm to resolve the alias path.

const path = require("path");

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@i': path.resolve('public/images'),
        },
    },
}

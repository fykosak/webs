const {merge} = require('webpack-merge');
const [fol, fof] = require('./webpack.common.js');
const config = {
    mode: 'development',
    devtool: 'inline-source-map',
};
module.exports = [merge(fol, config), merge(fof, config)];

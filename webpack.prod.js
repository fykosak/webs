const {merge} = require('webpack-merge');
const [fol, fof] = require('./webpack.common.js');

const config = {
    mode: 'production',
};
module.exports = [merge(fol, config), merge(fof, config)];

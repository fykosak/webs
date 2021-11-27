const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const commonConfiguration = {
    mode: "production",
    plugins: [new MiniCssExtractPlugin()],
    module: {
        rules: [
            {
                test: /\.(css)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader']
            },
            {
                test: /\.s[ac]ss$/i,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'resolve-url-loader', {
                    loader: 'sass-loader',
                    options: {
                        sourceMap: true
                    }}],
            },
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: /node_modules/,
            },
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
};

const folConfiguration = {
    ...commonConfiguration,
    entry: {
        main: path.resolve(__dirname, './app/main-fol.js'),
        results: "./app/Components/ResultsPanel/main.tsx",
    },
    output: {
        path: path.resolve(__dirname, './www/fol/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
};

const fofConfiguration = {
    ...commonConfiguration,
    entry: {
        main: path.resolve(__dirname, './app/main-fof.js'),
    },
    output: {
        path: path.resolve(__dirname, './www/fof/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
};

module.exports = [folConfiguration, fofConfiguration];

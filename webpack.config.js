const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    mode: "production",
    entry: {
        main: path.resolve(__dirname, './app/main.js'),
        results: "./app/Components/ResultsPanel/main.tsx",
    },
    output: {
        path: path.resolve(__dirname, './www/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
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

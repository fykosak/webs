const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const commonConfiguration = {
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
                    }
                }],
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
        results: "./app/Components/ResultsPanel/main.tsx",
    },
    output: {
        path: path.resolve(__dirname, './www/fof/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
};

const dsefConfiguration = {
    ...commonConfiguration,
    entry: {
        main: path.resolve(__dirname, './app/main-dsef.js'),
    },
    output: {
        path: path.resolve(__dirname, './www/dsef/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
};

const fykosConfiguration = {
    ...commonConfiguration,
    entry: {
        main: path.resolve(__dirname, './app/main-fykos.js')
    },
    output: {
        path: path.resolve(__dirname, './www/fykos/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
};

const vyfukConfiguration = {
    ...commonConfiguration,
    entry: {
        main: path.resolve(__dirname, './app/main-vyfuk.js'),
    },
    output: {
        path: path.resolve(__dirname, './www/vyfuk/assets'),
        assetModuleFilename: 'media/[path][name][ext]',
        filename: '[name].js',
    },
};
module.exports = [folConfiguration, fofConfiguration, dsefConfiguration, fykosConfiguration, vyfukConfiguration];

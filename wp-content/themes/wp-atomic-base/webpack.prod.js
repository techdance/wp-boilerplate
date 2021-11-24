const path = require("path");
const common = require("./webpack.common");
const merge = require("webpack-merge");
const CleanWebpackPlugin = require("clean-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const CopyPlugin = require('copy-webpack-plugin');
var WebpackAssetsManifest = require('webpack-assets-manifest');
var HtmlWebpackPlugin = require("html-webpack-plugin");

module.exports = merge(common, {
  mode: "production",
  output: {
    publicPath: '/',
    filename: "[name]_[hash:8].js",
  },
  optimization: {
    minimizer: [
      new OptimizeCssAssetsPlugin(), // minimize css (overwrites default minimization of js files)
      new TerserPlugin(), // default minimizer (installed by default)
      new HtmlWebpackPlugin({
        template: "./src/template.html",
        minify: {
          removeAttributeQuotes: true,
          collapseWhitespace: true,
          removeComments: true
        }
      })
    ]
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: "./src/template.html"
    }),
    new MiniCssExtractPlugin({
      filename: "assets/styles/[name].[hash:8].css"
    }), 
    new WebpackAssetsManifest(),
    new CleanWebpackPlugin(), 
    new CopyPlugin({
      patterns: [
        { from: 'src/images', to: 'assets/images' },
      ],
    }),
  ],
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader, // 3. Extract css into files
          "css-loader", // 2. Turns css into commonjs
          "sass-loader" // 1. Turns sass into css
        ]
      },
    ]
  }
});

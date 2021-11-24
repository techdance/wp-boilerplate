const path = require('path');

module.exports = {
  entry: {
    main: './src/main.js'
  },
  target: 'web',
  output: {
    path: path.resolve(__dirname, './dist'),
    filename: 'assets/scripts/[name].js'
  },
  plugins: [
  ],
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(js)$/,
        exclude: [/node_modules/, /externalScripts/],
        use: [
          {
            loader: 'eslint-loader',
            options: {
              failOnWarning: true,
              failOnError: true
            }
          }
        ]
      },
      {
        test: /\.(js)$/,
        include: /src\/*/,
        use: [
          {
            loader: 'babel-loader'
          }
        ]
      },
      {
        test: /\.html$/,
        use: ['html-loader']
      },
      {
        test: /\.(png|jpg|gif|svg|psd|mp4|ico)$/i,
        use: {
          loader: 'file-loader',
          options: {
            name: '[name].[hash:8].[ext]',
            outputPath: 'images'
          }
        }
      },
      {
        test: /\.(woff|woff2|eot|ttf)$/i,
        use: [
          {
            loader: "url-loader",
            options: {
              fallback: 'file-loader',
              name: "[path][name].[ext]",
              context: './src/fonts',
              publicPath: '/wp-content/themes/valleywise/dist/assets/fonts',
              outputPath: './assets/fonts',
              limit: 10000
            }
          },
          {
            loader: "img-loader"
          }
        ]
      }
    ]
  }
};

var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .enableSassLoader()
  .autoProvidejQuery()
  .configureBabel(() => {}, {
    useBuiltIns: 'usage',
    corejs: 3
  })
  .configureTerserPlugin((options) => {
    options.cache = true
    options.terserOptions = {
      output: { comments: false }
    }
  })
  .configureOptimizeCssPlugin((options) => {
    options.cssProcessor = require('cssnano')
    options.cssProcessorPluginOptions = {
      preset: ['default', { discardComments: { removeAll: true } }]
    }
  })
  .addEntry('vendor', ['./assets/js/vendor.js', './assets/scss/vendor.scss'])
  .addEntry('jstree', ['./assets/js/jstree.js', './node_modules/jstree/dist/themes/default/style.min.css'])
;

module.exports = Encore.getWebpackConfig();

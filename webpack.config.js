var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('./public/web/assets/js')
    .setPublicPath('/web/assets/js')
    .addEntry('app', './assets/js/app.js')
    .addEntry('init-js', './assets/js/init-js.js')
    .addEntry('css3-mediaqueries', './assets/js/css3-mediaqueries.js')
    .addEntry('fwslider', './assets/js/fwslider.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

module.exports = Encore.getWebpackConfig();

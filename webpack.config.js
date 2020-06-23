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

Encore.reset();
Encore
    .cleanupOutputBeforeBuild()
    .disableSingleRuntimeChunk()
    .setOutputPath('./public/web/assets/css')
    .setPublicPath('/web/assets/css')
    .setManifestKeyPrefix('')
    .addStyleEntry('app', './assets/css/app.css')
    .addStyleEntry('fwslider', './assets/css/fwslider.css')
    .addStyleEntry('jquery-ui', './assets/css/jquery-ui.css')
    .addStyleEntry('JFFormStyle-1', './assets/css/JFFormStyle-1.css')
;

module.exports = Encore.getWebpackConfig();

const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/website/js')
    .setPublicPath('/build/website/js')
    .addEntry('flatpickr', './assets/js/flatpickr.js')
    .addEntry('css3-mediaqueries', './assets/js/css3-mediaqueries.js')
    .addEntry('tiny-slider', './assets/js/tiny-slider.js')
    .addEntry('modal', './assets/js/modal.js')
    .addEntry('availabilityModal', './assets/js/availabilityModal.js')
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

const jsConfig = Encore.getWebpackConfig();
jsConfig.name = 'js';

Encore.reset();
Encore
    .cleanupOutputBeforeBuild()
    .disableSingleRuntimeChunk()
    .setOutputPath('./public/build/website/css')
    .setPublicPath('/build/website/css')
    .setManifestKeyPrefix('')
    .addStyleEntry('app', './assets/css/app.css')
    .addStyleEntry('tiny-slider', './assets/css/tiny-slider.css')
    .addStyleEntry('JFFormStyle-1', './assets/css/JFFormStyle-1.css')
    .addStyleEntry('airbnb', './assets/css/airbnb.css')
    .addStyleEntry('modal', './assets/css/modal.css')
;

const cssConfig = Encore.getWebpackConfig();
cssConfig.name = 'css';

module.exports = [jsConfig, cssConfig];

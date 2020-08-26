const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/website/js')
    .setPublicPath('/build/website/js')
    .addEntry('flatpickr', './assets/js/flatpickr.js')
    .addEntry('modal', './assets/js/modal.js')
    .addEntry('availabilityModal', './assets/js/availabilityModal.js')
    .addEntry('main', './assets/js/main.js')
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
    .addStyleEntry('airbnb', './assets/css/airbnb.css')
    .addStyleEntry('modal', './assets/css/modal.css')
    .addStyleEntry('style', './assets/css/style.css')
    .addStyleEntry('icomoon', './assets/css/icomoon.css')
    .addStyleEntry('bootstrap', './assets/css/bootstrap.css')
    .addStyleEntry('superfish', './assets/css/superfish.css')
    .addStyleEntry('app', './assets/css/app.css')
    .addStyleEntry('roomGallery', './assets/css/roomGallery.css')
;

const cssConfig = Encore.getWebpackConfig();
cssConfig.name = 'css';

module.exports = [jsConfig, cssConfig];

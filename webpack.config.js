const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/website/js')
    .setPublicPath('/build/website/js')
    .addEntry('pageReservation', './assets/js/pageReservation.js')
    .addEntry('core', './assets/js/core.js')
    .addEntry('pageHomepage', './assets/js/pageHomepage.js')
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
    .addStyleEntry('core', './assets/css/core.css')
    .addStyleEntry('featureReservation', './assets/css/featureReservation.css')
    .addStyleEntry('roomGallery', './assets/css/roomGallery.css')
    .addStyleEntry('card', './assets/css/card.css')
    .addStyleEntry('map', './assets/css/map.css')
;

const cssConfig = Encore.getWebpackConfig();
cssConfig.name = 'css';

module.exports = [jsConfig, cssConfig];

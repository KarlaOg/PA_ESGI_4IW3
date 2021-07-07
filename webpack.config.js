const Encore = require('@symfony/webpack-encore');
const PurgeCssPlugin = require('purgecss-webpack-plugin');
const glob = require('glob-all');
const path = require('path');
//test si on est en environnement de dev ou de prod
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  //on va seter ou on va mettre nos fichiers
  .setOutputPath('public/build/')
  //quel est le fichier ou on va recuperer les elements
  .setPublicPath('/build')

  .copyFiles({
    from: './assets/images',

    // optional target path, relative to the output dir
    //to: 'images/[path][name].[ext]',

    // if versioning is enabled, add the file hash too
    //to: 'images/[path][name].[hash:8].[ext]',

    // only copy files matching this pattern
    //pattern: /\.(png|jpg|jpeg)$/
  })

  /*
   * ENTRY CONFIG
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
   */
  //la possibiliter de creer des especes denvironnement et on peux creer autant quon veux en appeler les fichiers js
  //splitEntryChunks permet de decouper les fichiers
  .addEntry('app', './assets/app.js')
  .enableSassLoader()
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .enablePostCssLoader()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  //lire un fichier quand il est minifié
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

if (Encore.isProduction()) {
  Encore.addPlugin(
    new PurgeCssPlugin({
      paths: glob.sync([path.join(__dirname, 'templates/**/*.html.twig')]),
      defaultExtractor: (content) => {
        return content.match(/[\w-/:]+(?<!:)/g) || []
      },
    })
  );
}
module.exports = Encore.getWebpackConfig();

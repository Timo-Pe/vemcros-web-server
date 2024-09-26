const Encore = require("@symfony/webpack-encore");
const path = require("path");
// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
  // directory where compiled assets will be stored
  .setOutputPath("public/build/")
  // public path used by the web server to access the output path
  .setPublicPath("/build")
  // only needed for CDN's or subdirectory deploy
  //.setManifestKeyPrefix('build/')

  .enablePostCssLoader()
  .copyFiles({
    from: "./assets/icons",

    // optional target path, relative to the output dir
    to: "icons/[path][name].[ext]",

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
  .addEntry("app", "./assets/app.js")

  // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
  .enableStimulusBridge("./assets/controllers.json")

  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()
  .addAliases({
    "@assets": path.resolve(__dirname, "assets"),
  })
  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

  // configure Babel
  // .configureBabel((config) => {
  //     config.plugins.push('@babel/a-babel-plugin');
  // })

  // enables and configure @babel/preset-env polyfills
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = "usage";
    config.corejs = "3.23";
  })

  // enables Sass/SCSS support
  //.enableSassLoader()

  // Configuration du Dev Server
  .configureDevServerOptions((options) => {
    options.hot = true; // Active le hot reload
    options.liveReload = true; // Active le live reload
    options.watchFiles = {
      paths: ["templates/**/*", "assets/**/*.js", "assets/**/*.jsx"],
      options: {
        usePolling: true, // Ajoutez cette ligne si vous rencontrez des problèmes avec le système de fichiers
      },
    };
    options.client = {
      overlay: true, // Affiche les erreurs dans une superposition sur la page
    };

    options.port = 8080; // Définir le port du Dev Server
    options.static = {
      directory: path.join(__dirname, "public/"),
    };
    options.proxy = [
      {
        context: () => true,
        target: "http://localhost:8000", // Port de votre serveur Symfony/PHP
        secure: false,
        changeOrigin: true,
      },
    ];
    options.allowedHosts = "all";
  });

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment if you use React
//.enableReactPreset()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()
module.exports = Encore.getWebpackConfig();

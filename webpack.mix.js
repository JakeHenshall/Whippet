/**
 * Laravel Mix configuration file.
 *
 * Laravel Mix is a layer built on top of WordPress that simplifies much of the
 * complexity of building out a Webpack configuration file. Use this file to
 * configure how your assets are handled in the build process.
 *
 * @link https://laravel.com/docs/5.6/mix
 *
 * @package   Whippet
 * @author    Jake Henshall <jakehenshall93@gmail.co.uk>
 * @copyright 2018 Jake Henshall
 * @link      jake.hen.sh/all/
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 */

// Import required packages.
const { mix }           = require( 'laravel-mix' );
const Clean             = require( 'clean-webpack-plugin' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const ImageminPlugin    = require( 'imagemin-webpack-plugin' ).default;
const imageminMozjpeg   = require( 'imagemin-mozjpeg' );
const tailwindcss       = require( 'tailwindcss' );

// require( 'laravel-mix-tailwind' );
// require( 'laravel-mix-purgecss' );

/*
 * -----------------------------------------------------------------------------
 * Build Process
 * -----------------------------------------------------------------------------
 * The section below handles processing, compiling, transpiling, and combining
 * all of the theme's assets into their final location. This is the meat of the
 * build process.
 * -----------------------------------------------------------------------------
 */

/*
 * Sets the development path to assets. By default, this is the `/resources`
 * folder in the theme.
 */
const devPath  = 'resources';

/*
 * Sets the path to the generated assets. By default, this is the `/dist` folder
 * in the theme. If doing something custom, make sure to change this everywhere.
 */
mix.setPublicPath( 'dist' );

/*
 * Set Laravel Mix options.
 *
 * @link https://laravel.com/docs/5.6/mix#postcss
 * @link https://laravel.com/docs/5.6/mix#url-processing
 */
mix.options( {
	postCss: [
		require( 'postcss-preset-env' )(),
		tailwindcss('./tailwind.config.js'),
	],
	processCssUrls: false
} );

/*
 * Builds sources maps for assets.
 *
 * @link https://laravel.com/docs/5.6/mix#css-source-maps
 */
mix.sourceMaps();

/*
 * Versioning and cache busting. Append a unique hash for production assets. If
 * you only want versioned assets in production, do a conditional check for
 * `mix.inProduction()`.
 *
 * @link https://laravel.com/docs/5.6/mix#versioning-and-cache-busting
 */
mix.version();

/*
 * Compile JavaScript.
 *
 * @link https://laravel.com/docs/5.6/mix#working-with-scripts
 */
mix.js( `${devPath}/js/app.js`, 'js' );

/*
 * Compile CSS. Mix supports Sass, Less, Stylus, and plain CSS, and has functions
 * for each of them.
 *
 * @link https://laravel.com/docs/5.6/mix#working-with-stylesheets
 * @link https://laravel.com/docs/5.6/mix#sass
 * @link https://github.com/sass/node-sass#options
 */

// Sass configuration.
var sassConfig = {
	outputStyle: 'expanded',
	indentType: 'tab',
	indentWidth: 1
};

// Compile SASS/CSS.
mix.sass( `${devPath}/scss/style-whippet.scss`, 'css', sassConfig )
   .sass( `${devPath}/scss/style.scss`,         'css', sassConfig );

// // Compile Tailwind
// mix.tailwind();

// // Run PurgeCSS
// mix.purgeCss();

/*
 * Add custom Webpack configuration.
 *
 * Laravel Mix doesn't currently minimize images while using its `.copy()`
 * function, so we're using the `CopyWebpackPlugin` for processing and copying
 * images into the distribution folder.
 *
 * @link https://laravel.com/docs/5.6/mix#custom-webpack-configuration
 * @link https://webpack.js.org/configuration/
 */
mix.webpackConfig( {
	stats: 'minimal',
	devtool: mix.inProduction() ? false : 'source-map',
	performance: { hints: false    },
	externals: { jquery: 'jQuery' },

	plugins: [

		// @link https://github.com/johnagan/clean-webpack-plugin
		new Clean([ './dist/' ]),

		// @link https://github.com/webpack-contrib/copy-webpack-plugin
		new CopyWebpackPlugin( [
			{ from: `${devPath}/img`,   to: 'img'   }
		] ),

		// @link https://github.com/Klathmon/imagemin-webpack-plugin
		new ImageminPlugin( {
			test: /\.(jpe?g|png|gif|svg)$/i,
			disable: 'production' !== process.env.NODE_ENV,
			optipng: { optimizationLevel: 3 },
			gifsicle: { optimizationLevel: 3 },
			pngquant: {
				quality: '65-90',
				speed: 4
			},
			svgo: {
				plugins: [
					{ cleanupIDs: false },
					{ removeViewBox: false },
					{ removeUnknownsAndDefaults: false }
				]
			},
			plugins: [

				// @link https://github.com/imagemin/imagemin-mozjpeg
				imageminMozjpeg( { quality: 75 } )
			]
		} )
	]
} );

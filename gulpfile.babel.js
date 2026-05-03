/**
 * MoreConvert Compare for WooCommerce gulp
 *
 * @author MoreConvert
 * @package MoreConvert Compare for WooCommerce
 */

import gulp from 'gulp';
import { src,series, parallel } from 'gulp';
import * as sassCompiler from 'sass';
import gulpSass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import rename from 'gulp-rename';
import gcmq from 'gulp-group-css-media-queries';
import babel from 'gulp-babel';
import postcss from 'gulp-postcss';
import jshint from 'gulp-jshint';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import del from 'del';
import uglify from 'gulp-uglify';
import zip from 'gulp-zip';
import info from './package.json';
import exec from 'gulp-exec';
import yargs from 'yargs';
import { execSync } from 'child_process';

const PRODUCTION = !!yargs.argv.prod;
const sass = gulpSass(sassCompiler);
const paths      = {
	styles: {
		src: ['src/**/*.scss'],
		dest: 'assets/'
	},
	images: {
		src: ['src/**/*.{jpg,jpeg,png,svg,gif,webm}'],
		dest: 'assets/'
	},
	scripts: {
		src: ['src/**/*.js'],
		dest: 'assets/'
	},
	other: {
		src: ['src/**/*', '!src/{img,css,js}', '!src/{img,css,js}/**/*'],
		dest: 'assets/'
	},
	package: {
		src: [
			'**/*',
			'!.vscode',
			'!.github',
			'!.wordpress-org',
			'!node_modules{,/**}',
			'!packaged{,/**}',
			'!src{,/**}',
			'!.babelrc',
			'!.gitignore',
			'!.distignore',
			'!gulpfile.babel.js',
			'!package.json',
			'!package-lock.json',
			'!options/.vscode',
			'!options/node_modules{,/**}',
			'!options/packaged{,/**}',
			'!options/src{,/**}',
			'!options/.babelrc',
			'!options/.gitignore',
			'!options/gulpfile.babel.js',
			'!options/package.json',
			'!options/package-lock.json',
			'!options/class-demo.php'
		],
		dest: 'packaged'
	},
};

export const clean = () => del( ['assets'] );

export const styles = () => {
	return gulp.src( paths.styles.src )
		.pipe( gulpif( ! PRODUCTION, sourcemaps.init() ) )
		//.pipe( sass().on( 'error', sass.logError ) )
		.pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
		.pipe( postcss( [autoprefixer( 'last 30 versions', 'ie >= 10' )] ) )
		.pipe( gcmq() )
		.pipe( gulpif( ! PRODUCTION, sourcemaps.write() ) )
		.pipe( gulp.dest( paths.styles.dest ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( postcss( [autoprefixer( 'last 30 versions', 'ie >= 10' )] ) )
		.pipe( gcmq() )
		.pipe( gulpif( PRODUCTION, cleanCss( { compatibility: 'ie8' } ) ) )
		.pipe( gulp.dest( paths.styles.dest ) );
};

export const images = () => {
	return gulp.src( paths.images.src )
		.pipe( gulp.dest( paths.images.dest ) );
};

export const copy = () => {
	return gulp.src( paths.other.src )
		.pipe( gulp.dest( paths.other.dest ) );
};

export const scripts = () => {
	return gulp.src( paths.scripts.src )
		.pipe( gulpif( ! PRODUCTION, jshint() ) )
		.pipe( gulpif( ! PRODUCTION, jshint.reporter( 'default' ) ) )
		.pipe( babel() )
		.pipe( gulp.dest( paths.scripts.dest ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulpif( PRODUCTION, uglify() ) )
		.pipe( gulp.dest( paths.scripts.dest ) );
};

export const compress = () => {
	return gulp.src( paths.package.src )
		.pipe( zip( `${info.name}.zip` ) )
		.pipe( gulp.dest( paths.package.dest ) );
};

export const pot = () => {
	const headers = JSON.stringify({
		'Report-Msgid-Bugs-To': 'https://moreconvert.com/support/#support-form',
		'Last-Translator': 'MoreConvert',
		'Language-Team': 'MoreConvert'
	}).replace(/"/g, '\\"');
	const command = `wp i18n make-pot . languages/${info.i18n}.pot --slug=${info.i18n} --include=**/*.php --exclude=lib/**,vendor/**,node_modules/**,options/node_modules/**,options/class-demo.php --package-name="${info.name}" --headers="${headers}"`;

	return src('.')
		.pipe(exec(command))
		.pipe(exec.reporter());
};

export const watchChanges = () => {
	gulp.watch( 'src/**/*.scss', styles );
	gulp.watch( 'src/**/*.js', gulp.series( scripts ) );
	gulp.watch( '**/*.php' );
	gulp.watch( paths.images.src, gulp.series( images ) );
	gulp.watch( paths.other.src, gulp.series( copy ) );
};


const phpFilesGlob = [
	'**/*.php',
	'!node_modules/**',
	'!vendor/**',
	'!options/node_modules/**',
	'!options/class-demo.php',
];
export const phpcs = (done) => {
	const standard = 'WordPress';
	const ignore = 'lib/*,*/vendor/*,*/node_modules/*,options/class-demo.php';
	const command = `phpcs .`;
	try {
		execSync(command, { stdio: 'inherit' });
		done();
	} catch (error) {
		console.error('PHPCS found some issues (see above).');
		done();
	}
};

export const phpcbf = (done) => {
	const standard = 'WordPress';
	const ignore = '*/vendor/*,*/node_modules/*,options/class-demo.php';
	const command = `phpcbf .`;
	try {
		execSync(command, { stdio: 'inherit' });
		done();
	} catch (error) {
		console.error('PHPCBF encountered an error (maybe nothing to fix?).');
		done();
	}
};

export const dev    = series( clean, parallel( styles, images, copy, scripts, phpcs ), watchChanges );
export const build  = series( clean, parallel( styles, images, copy, scripts ) );
export const bundle = series( build, pot, compress );

export default dev;

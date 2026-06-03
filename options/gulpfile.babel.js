/**
 * MoreConvert Option framework
 *
 * @author MoreConvert
 * @package Option framework
 */

import gulp from 'gulp';
import { src, dest, series, parallel } from 'gulp';
import * as sassCompiler from 'sass';
import gulpSass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import rename from 'gulp-rename';
import gcmq from'gulp-group-css-media-queries';
import babel from 'gulp-babel';
import postcss from 'gulp-postcss';
import jshint from 'gulp-jshint';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import del from 'del';
import uglify from 'gulp-uglify';
import yargs from 'yargs';

const PRODUCTION =  !!yargs.argv.prod;
const sass = gulpSass(sassCompiler);
const paths      = {
	styles: {
		src: ['src/scss/option-styles.scss'],
		dest: 'assets/css'
	},
	scripts: {
		src: ['src/js/*.js' ],
		dest: 'assets/js'
	},
	fonts: {
		src: 'src/fonts/**/*.{woff,woff2,ttf,otf}',
		dest: 'assets/fonts/'
	},
	images: {
		src: 'src/img/**/*.{jpg,jpeg,png,svg,gif,webp,ico}',
		dest: 'assets/img/'
	},
	other: {
		src: ['src/**/*', '!src/{fonts,img,scss,js}','!src/{fonts,img,scss,js}/**/*'],
		dest: 'assets/'
	},
}


export const clean = () => del( ['assets'] );

export const styles = () => {
	return gulp.src( paths.styles.src )
		.pipe( gulpif( ! PRODUCTION, sourcemaps.init() ) )
		.pipe(sass({ logWarningsToConsole: true }).on('error', (err) => {
			console.error('Sass Error:', err);
			throw err;
		}))
		.pipe( postcss( [autoprefixer( 'last 30 versions', "ie >= 10" )] ) )
		.pipe( gcmq() )
		.pipe( gulpif( ! PRODUCTION, sourcemaps.write() ) )
		.pipe( gulp.dest( paths.styles.dest ) )
		.pipe( rename( {suffix: '.min'} ) )
		.pipe( postcss( [autoprefixer( 'last 30 versions', "ie >= 10" )] ) )
		.pipe( gcmq() )
		.pipe( gulpif( PRODUCTION, cleanCss( {compatibility: 'ie8'} ) ) )
		.pipe( gulp.dest( paths.styles.dest ) );
}

export const copy = () => {
	return gulp.src( paths.other.src )
		.pipe( gulp.dest( paths.other.dest ) );
}

export const fonts = () => {
	return gulp.src(paths.fonts.src, { encoding: false })
		.pipe(gulp.dest(paths.fonts.dest));
};
export const images = () => {
	return gulp.src(paths.images.src, { encoding: false })
		.pipe(gulp.dest(paths.images.dest));
};

export const scripts = () => {
	return gulp.src( paths.scripts.src )
		.pipe( gulpif( ! PRODUCTION,jshint() ) )
		.pipe( gulpif( ! PRODUCTION,jshint.reporter( 'default' ) ) )
		.pipe( babel() )
		.pipe( gulp.dest( paths.scripts.dest ) )
		.pipe( rename( {suffix: '.min'} ) )
		.pipe( gulpif( PRODUCTION, uglify() ) )
		.pipe( gulp.dest( paths.scripts.dest ) )
}

export const build = series( clean, parallel( styles, copy, fonts,images, scripts ) );

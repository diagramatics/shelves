'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browserSync = require('browser-sync');
var reload = browserSync.reload;

var AUTOPREFIXER_BROWSERS = [
'ie >= 10',
'ie_mob >= 10',
'ff >= 30',
'chrome >= 34',
'safari >= 7',
'opera >= 23',
'ios >= 7',
'android >= 4.4',
'bb >= 10'
];

gulp.task('styles', function () {
  // For best performance, don't add Sass partials to `gulp.src`
  return gulp.src([
    'public/scss/style.scss',
    ])
    .pipe($.changed('styles', {extension: '.scss'}))
    .pipe($.sass({
      style: 'expanded',
      sourcemap: true,
      precision: 10,
      errLogToConsole: true
    }))
    .on('error', console.error.bind(console))
    .pipe($.autoprefixer({browsers: AUTOPREFIXER_BROWSERS}))
    .pipe(gulp.dest('public/css'))
    .pipe($.size({title: 'styles'}))
    .pipe(reload({stream: true}));
});


gulp.task('serve', ['styles'], function () {
  browserSync({
    notify: true,
    proxy: '192.168.33.10'
  });
  gulp.watch(['app/**/*.php'], reload);
  gulp.watch(['public/scss/**/*.{scss,css}'], ['styles']);
  gulp.watch(['public/js/**/*.js'], reload);
  gulp.watch(['public/images/**/*'], reload);
  reload();
});

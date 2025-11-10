var gulp = require('gulp'),
    concat = require('gulp-concat'),
    sass = require('gulp-sass')(require('sass')),
    gulpIgnore = require('gulp-ignore'),
    sourcemaps = require('gulp-sourcemaps'),
    minify = require('gulp-minify'),
    terser = require('gulp-terser'),
    rename = require("gulp-rename"),
    fs = require('fs'),
    path = require("path"),
    multiDest = require('gulp-multi-dest'),
    rename = require("gulp-rename"),
    zip = require('gulp-zip'),
    babel = require('gulp-babel'),
    jshint = require('gulp-jshint'),
    map = require('map-stream');

var sassPaths = [];

var client_script_files = [
    'js/YCT.js',

    // 3rd Party Jquery
    'js/3rd-party/modal.js',
    'js/3rd-party/simple-pagination.js',
    //'js/3rd-party/autoNumeric.js',

    // 3rd Party
    'js/3rd-party/readmore.js',

    // Our Libs.
    'js/yatco-boss-api-client.js',

    // Our
    'js/yachts-filling-in-inputs.js',
    'js/yachts-filling-in-categories.js',
    'js/yachts-filling-in-locations.js',
    'js/yachts.js',

    'js/whatever.js',
    'js/leads.js',
];

var yacht_details_script_files = [
    'js/3rd-party/flickity.js',

    'js/lightgallery-dist/lightgallery.umd.js',
    'js/lightgallery-dist/plugins/thumbnail/lg-thumbnail.umd.js',
    'js/lightgallery-dist/plugins/zoom/lg-zoom.umd.js',
    'js/lightgallery-dist/plugins/video/lg-video.umd.js',
    'js/lightgallery-dist/plugins/rotate/lg-rotate.umd.js',
    //'js/dist/plugins/share/lg-share.umd.js',

    //'js/3rd-party/lightgallery-all.js',
    'js/single-yacht.js'
];

var exitOnJshintError = map(function (file, cb) {
    if (!file.jshint.success) {
        console.error('jshint failed');
        process.exit(1);
    }
});

// CLIENT TASKS
gulp.task('client-sass', function () {
    return gulp.src('scss/client-side.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            includePaths: sassPaths,
            outputStyle: 'compressed'
        })
            .on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/css'));
});

gulp.task('client-sass-no-maps', function () {
    return gulp.src('scss/client-side.scss')
        //.pipe(sourcemaps.init())
        .pipe(sass({
            includePaths: sassPaths,
            outputStyle: 'compressed'
        })
            .on('error', sass.logError))
        //.pipe(sourcemaps.write())
        .pipe(rename("client-side.noMaps.css"))
        .pipe(gulp.dest('build/css'));
});

gulp.task('hint', function () {
    return gulp.src('js/*.js')
        .pipe(jshint({
            esversion: 6
        }))
        .pipe(jshint.reporter('jshint-stylish'))
        .pipe(jshint.reporter('fail'));
});

gulp.task('client-js', function () {
    return gulp.src(client_script_files)
        .pipe(sourcemaps.init())
        .pipe(concat('client-side.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/js'));
});

gulp.task('client-minify-js', function () {
    return gulp.src(client_script_files)
        .pipe(concat('client-side.noMaps.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(terser({
            compress: { drop_console: false, },
            output: { comments: false },
            //ie8: true,
        }))
        .pipe(gulp.dest('build/js'));
});

gulp.task('yacht-details-client-js', function () {
    return gulp.src(yacht_details_script_files)
        .pipe(sourcemaps.init())
        .pipe(concat('yacht-details-client-side.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/js'));
});

gulp.task('yacht-details-client-minify-js', function () {
    return gulp.src(yacht_details_script_files)
        .pipe(concat('yacht-details-client-side.noMaps.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(terser({
            compress: { drop_console: false, },
            output: { comments: false },
            //ie8: true,
        }))
        .pipe(gulp.dest('build/js'));
});

gulp.task('render-client', gulp.series('client-sass', 'client-sass-no-maps', 'client-js', 'client-minify-js', 'yacht-details-client-js', 'yacht-details-client-minify-js', 'hint'));

// Copy Plugin To All Other WordPress Project On LocalHost
gulp.task('copy-to-others', function () {

    var root_path = '/var/www/html';

    var arrayOfRoot = fs.readdirSync(root_path);

    var copy_to_dirs = [];

    arrayOfRoot.forEach(function (item) {
        if (fs.statSync(root_path + "/" + item).isDirectory()) {

            var dir = root_path + "/" + item;

            if (fs.existsSync(dir + '/wp-config.php') && item != 'yatco-connect') {

                copy_to_dirs.push(dir + '/wp-content/plugins/yatco-connect');

            } else {

            }

        }
    });

    return gulp.src([
        '/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/*',
        '/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/**/*',
        '!/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/node_modules/**',
        '!/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/.git/**',
    ]).pipe(
        multiDest(copy_to_dirs)
    );
});

// The Watch
gulp.task('watch', function () {
    gulp.watch(
        [
            'scss/*', 'scss/**/*', 'js/*', 'js/**/*'
        ],
        gulp.task('render-client')
    );
});

// Zipping Up, Up And Away
gulp.task('shipping', function () {
    return gulp.src([
        '/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/.gitignore',
        '/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/*',
        '/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/**/**',
        '!/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/node_modules/**',
        '!/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/.git/**',
        '!/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/.git',
        '!/var/www/html/yatco-connect/wp-content/plugins/yatco-connect/build/yatco-connect.zip',
    ])
        .pipe(
            rename(
                function (path) {
                    path.dirname = 'yatco-connect/' + path.dirname;
                }
            )
        )
        .pipe(zip('yatco-connect.zip'))
        .pipe(gulp.dest('build/'));
});

gulp.task('zip-up', gulp.series('shipping'));
gulp.task('zipup', gulp.series('shipping'));

// THE DEFAULT
gulp.task('default', gulp.series('render-client'));
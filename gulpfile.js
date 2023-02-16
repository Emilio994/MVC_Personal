// Path
const path = require('path');
global.BUILD_DIR = process.env.NODE_ENV === 'preview' ? 
    path.join(__dirname,'preview','assets') :
    path.join(__dirname,'app','public','assets') ;
global.SOURCE_DIR = path.join(__dirname,'src');

// Gulp Modules
const { watch , series , parallel, } = require('gulp');

// Tasks
const httpServer = require('./gulp_tasks/server/server');
const serverReload = require('./gulp_tasks/server/reload');
const copyHtml = require('./gulp_tasks/html/copyHtml');
const copyFonts = require('./gulp_tasks/fonts/copyFonts');
const copyImages = require('./gulp_tasks/images/copyImages');
const compileJs = require('./gulp_tasks/js/compileJs');
const compileCss = require('./gulp_tasks/css/compileCss');
const minifyJs = require('./gulp_tasks/js/minifyJs');
const minifyCss = require('./gulp_tasks/css/minifyCss');
const clean = require('./gulp_tasks/cleaners/clean');


const watchTask = () => {
    watch(`src/js/**/*.js`,compileJs);
    watch(`src/scss/**/*.scss`,compileCss);
    watch(`src/images/**/*.*`,copyImages);
    watch(`src/fonts/**/*.*`,copyFonts);
} 

const watchTaskPreview = () => {
    watch(`src/js/**/*.js`,series(compileJs,serverReload));
    watch(`src/scss/**/*.scss`,series(compileCss,serverReload));
    watch(`src/fonts/**/*.*`,series(copyFonts,serverReload));
    watch(`src/views/**/*.html`,series(copyHtml,serverReload));
    watch(`src/images/**/*.*`,series(copyImages,serverReload));
}

module.exports.dev = series(
    clean,
    parallel(
        compileJs,
        compileCss,
        copyFonts,
        copyImages
    ),
    watchTask
);

module.exports.build = series(
    clean,
    parallel(
        series(
            compileCss,
            minifyCss
        ),
        series(
            compileJs,
            minifyJs
        ),
        copyFonts,
        copyImages
    )
);

module.exports.preview = series(
    httpServer,
    clean,
    parallel(
        copyHtml,
        compileJs,
        compileCss,
        copyFonts,
        copyImages
    ),
    watchTaskPreview,
)
const { src, dest } = require('gulp');
const path = require('path');
const { createGulpEsbuild } = require('gulp-esbuild');
const esbuid = createGulpEsbuild({
    incremental : true,
    piping : true
});


module.exports = function compileJs() {
    return src(path.join(SOURCE_DIR,'js','index.js'))
    .pipe(
        esbuid({
            outfile: 'index.js',
            bundle: true,
        })
    )
    .pipe(dest(path.join(BUILD_DIR,'js')));
};
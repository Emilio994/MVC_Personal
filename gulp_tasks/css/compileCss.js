const { src, dest } = require('gulp');
const sass = require('gulp-sass')(require('sass'));

module.exports = function compileCss() {
    return src(`${SOURCE_DIR}/scss/**/*.scss`)
    .pipe(sass().on(`error`, sass.logError))
    .pipe(dest(`${BUILD_DIR}/css`));
};
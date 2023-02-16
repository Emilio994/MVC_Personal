const { src, dest } = require('gulp');
const cleanCss = require('gulp-clean-css');

module.exports= function minifyCss() {
    return src(`${BUILD_DIR}/css/*.css`)
    .pipe(cleanCss())
    .pipe(dest(`${BUILD_DIR}/css`));
}
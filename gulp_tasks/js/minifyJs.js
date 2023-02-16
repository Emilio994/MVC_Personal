const path = require('path');
const { src, dest } = require('gulp');
const uglify = require('gulp-uglify');

module.exports = function minifyJs() {
    return src(path.join(BUILD_DIR,'js','index.js'),{allowEmpty:true})
    .pipe(uglify())
    .pipe(dest(`${BUILD_DIR}/js`))
}   
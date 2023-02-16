const {src , dest} = require('gulp');
const path = require('path');

module.exports = function copyHtml(){
    return src(`${SOURCE_DIR}/views/**/*.html`)
    .pipe(dest(path.resolve(BUILD_DIR,'..')));
}
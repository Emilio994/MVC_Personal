const {src , dest} = require('gulp');
const path = require('path');

module.exports = function copyFonts(){
    return src(`${SOURCE_DIR}/fonts/**/*.*`)
    .pipe(dest(path.resolve(BUILD_DIR,'css','fonts')));
}
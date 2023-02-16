const {src , dest} = require('gulp');
const path = require('path');

module.exports = function copyImages(){
    return src(`${SOURCE_DIR}/images/**/*.*`)
    .pipe(dest(path.join(BUILD_DIR,'images')));
}
const browserSync = require('browser-sync');
const path = require('path');

module.exports = function serve(cb) {
    browserSync.init({
        server: {
          baseDir: path.resolve(BUILD_DIR,'..'),
          index: 'index.html'
        },
        notify: false,
        injectChanges: true
    });
    cb();
}
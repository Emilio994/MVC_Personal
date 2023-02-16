const browserSync = require('browser-sync');

module.exports = function reload(cb) {
    browserSync.reload();
    cb();
}
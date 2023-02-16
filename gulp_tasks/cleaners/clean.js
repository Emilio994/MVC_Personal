const del = require('delete');
const cleanDirs = process.env.NODE_ENV === 'preview' ?

    [   
        `${BUILD_DIR}/../views/*`,
        `${BUILD_DIR}/js/*`,
        `${BUILD_DIR}/css/*`
    ] :

    [   
        `${BUILD_DIR}/js/*`,
        `${BUILD_DIR}/css/*`
    ] ;

module.exports = async function clean() {
    await del(cleanDirs);
};

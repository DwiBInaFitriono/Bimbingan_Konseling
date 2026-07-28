const vercelPhp = require('vercel-php');
const { FileBlob } = require('@vercel/build-utils');

exports.version = 3;

exports.build = async (options) => {
    // Run the original builder
    const result = await vercelPhp.build(options);
    
    // Fix the launcher.launcher bug for Node 20
    if (result.output && result.output.handler === 'launcher.launcher') {
        // Create an index.js that exports the named export 'launcher' from launcher.js
        const wrapperContent = `
            const launcher = require('./launcher.js').launcher;
            module.exports = launcher;
        `;
        result.output.files['index.js'] = new FileBlob({ data: wrapperContent });
        
        // Change the handler to index.js
        result.output.handler = 'index.js';
    }
    
    return result;
};

exports.prepareCache = vercelPhp.prepareCache;
exports.shouldServe = vercelPhp.shouldServe;

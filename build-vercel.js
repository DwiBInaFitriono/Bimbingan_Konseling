const fs = require('fs');
const path = require('path');
const libphp = require('@libphp/almalinux-9-v85');

// Monkey-patch libphp.getFiles to ensure Linux forward slashes on Windows
const origGetFiles = libphp.getFiles;
libphp.getFiles = async function() {
    const files = await origGetFiles.call(this);
    const normalized = {};
    for (const [k, v] of Object.entries(files)) {
        normalized[k.replace(/\\/g, '/')] = v;
    }
    return normalized;
};

const vercelPhp = require('vercel-php');

async function main() {
    console.log('🚀 Starting custom Vercel Build Output API v3 generator...');
    const workPath = process.cwd();

    // 1. Run vercel-php builder
    const result = await vercelPhp.build({
        files: {},
        entrypoint: 'api/index.php',
        workPath: workPath,
        config: {},
        meta: { isDev: false }
    });

    const funcDir = path.join(workPath, '.vercel/output/functions/api/index.func');
    fs.mkdirSync(funcDir, { recursive: true });

    // 2. Copy lambda files
    console.log('📦 Writing function files to .vercel/output...');
    const lambdaFiles = result.output.files;
    for (const [relPath, fileObj] of Object.entries(lambdaFiles)) {
        const normalizedPath = relPath.replace(/\\/g, '/');

        // Ignore git, node_modules, and vercel cache
        if (
            normalizedPath.startsWith('user/.git/') ||
            normalizedPath.startsWith('user/node_modules/') ||
            normalizedPath.startsWith('user/.vercel/')
        ) {
            continue;
        }

        const destPath = path.join(funcDir, normalizedPath);
        fs.mkdirSync(path.dirname(destPath), { recursive: true });

        if (fileObj.fsPath) {
            if (fs.existsSync(fileObj.fsPath)) {
                try {
                    const realPath = fs.realpathSync(fileObj.fsPath);
                    const stat = fs.statSync(realPath);
                    if (stat.isDirectory()) {
                        fs.cpSync(realPath, destPath, { recursive: true, dereference: true });
                    } else {
                        fs.copyFileSync(realPath, destPath);
                    }
                } catch(e) {
                    try {
                        fs.copyFileSync(fileObj.fsPath, destPath);
                    } catch(err) {}
                }
            }
        } else if (fileObj.data) {
            fs.writeFileSync(destPath, fileObj.data);
        }
        if (fileObj.mode) {
            try { fs.chmodSync(destPath, fileObj.mode); } catch (e) {}
        }
    }

    // 3. Create index.js wrapper to resolve launcher.launcher bug, NaN event bug, and catch startup errors
    console.log('🔧 Creating index.js entrypoint wrapper...');
    const indexJsContent = `
const dns = require('dns');
if (dns.setDefaultResultOrder) dns.setDefaultResultOrder('ipv4first');
if (!process.env.LAMBDA_TASK_ROOT) process.env.LAMBDA_TASK_ROOT = '/var/task';
if (!process.env.NOW_ENTRYPOINT) process.env.NOW_ENTRYPOINT = 'api/index.php';

const { launcher: origLauncher } = require('./launcher.js');

module.exports = async function launcher(event, context) {
    try {
        const normalizedEvent = { ...event };
        normalizedEvent.path = normalizedEvent.path || normalizedEvent.url || normalizedEvent.rawPath || '/';
        normalizedEvent.httpMethod = normalizedEvent.httpMethod || normalizedEvent.method || (normalizedEvent.requestContext && normalizedEvent.requestContext.http && normalizedEvent.requestContext.http.method) || 'GET';
        
        const headers = { ...(normalizedEvent.headers || {}) };
        headers['connection'] = 'close';
        headers['Connection'] = 'close';
        normalizedEvent.headers = headers;

        normalizedEvent.host = normalizedEvent.host || headers.host || headers.Host || '127.0.0.1:3000';
        return await origLauncher(normalizedEvent, context);
    } catch (err) {
        console.error('PHP Launcher Exception:', err);
        return {
            statusCode: 500,
            headers: { 'content-type': 'text/plain' },
            body: Buffer.from('Server Error: ' + (err.stack || err)).toString('base64'),
            encoding: 'base64'
        };
    }
};
`;
    fs.writeFileSync(path.join(funcDir, 'index.js'), indexJsContent);

    // 4. Create .vc-config.json
    const vcConfig = {
        runtime: "nodejs20.x",
        handler: "index.js",
        launcherType: "Nodejs",
        environment: {
            LAMBDA_TASK_ROOT: "/var/task",
            NOW_ENTRYPOINT: "api/index.php",
            NOW_PHP_DEV: "0"
        }
    };
    fs.writeFileSync(path.join(funcDir, '.vc-config.json'), JSON.stringify(vcConfig, null, 2));

    // 5. Create .vercel/output/config.json
    const outputConfig = {
        version: 3,
        routes: [
            { src: "/build/(.*)", dest: "/public/build/$1" },
            { src: "/assets/(.*)", dest: "/public/assets/$1" },
            { src: "/(css|js|images|storage)/(.*)", dest: "/public/$1/$2" },
            { src: "/(.*)", dest: "/api/index" }
        ]
    };
    fs.writeFileSync(path.join(workPath, '.vercel/output/config.json'), JSON.stringify(outputConfig, null, 2));

    console.log('✅ Successfully generated Vercel Build Output API v3 bundle!');
}

main().catch(err => {
    console.error('❌ Failed to generate vercel output:', err);
    process.exit(1);
});
